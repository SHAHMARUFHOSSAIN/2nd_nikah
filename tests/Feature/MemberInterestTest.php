<?php

namespace Tests\Feature;

use App\Livewire\Member\Connections\Index as ConnectionsIndex;
use App\Livewire\Member\Interests\Received;
use App\Livewire\Member\Interests\Sent;
use App\Livewire\Members\Show;
use App\Models\Interest;
use App\Models\MemberProfile;
use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MemberInterestTest extends TestCase
{
    use RefreshDatabase;

    private function createDiscoverableMember(array $userAttrs = [], array $profileAttrs = []): MemberProfile
    {
        $user = User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ], $userAttrs));

        return MemberProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => 'Valid',
            'last_name' => 'Member',
            'is_profile_visible' => true,
        ], $profileAttrs));
    }

    public function test_1_authenticated_verified_user_can_send_interest(): void
    {
        $senderProfile = $this->createDiscoverableMember();
        $receiverProfile = $this->createDiscoverableMember();

        $this->actingAs($senderProfile->user);

        Livewire::test(Show::class, ['memberProfile' => $receiverProfile])
            ->call('sendInterest');

        $this->assertDatabaseHas('interests', [
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);
    }

    public function test_2_unauthenticated_user_cannot_send_interest(): void
    {
        $receiverProfile = $this->createDiscoverableMember();

        Livewire::test(Show::class, ['memberProfile' => $receiverProfile])
            ->call('sendInterest')
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('interests', 0);
    }

    public function test_3_unverified_user_cannot_send_interest(): void
    {
        $unverifiedUser = User::factory()->create([
            'email_verified_at' => null,
            'is_active' => true,
            'is_admin' => false,
        ]);
        MemberProfile::create(['user_id' => $unverifiedUser->id, 'first_name' => 'Unverified', 'is_profile_visible' => true]);

        $receiverProfile = $this->createDiscoverableMember();

        $this->actingAs($unverifiedUser);

        Livewire::test(Show::class, ['memberProfile' => $receiverProfile])
            ->call('sendInterest');

        $this->assertDatabaseCount('interests', 0);
    }

    public function test_4_inactive_user_cannot_send_interest(): void
    {
        $inactiveUser = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => false,
            'is_admin' => false,
        ]);
        MemberProfile::create(['user_id' => $inactiveUser->id, 'first_name' => 'Inactive', 'is_profile_visible' => true]);

        $receiverProfile = $this->createDiscoverableMember();

        $this->actingAs($inactiveUser);

        Livewire::test(Show::class, ['memberProfile' => $receiverProfile])
            ->call('sendInterest');

        $this->assertDatabaseCount('interests', 0);
    }

    public function test_5_admin_cannot_send_interest(): void
    {
        $adminUser = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => true,
        ]);
        MemberProfile::create(['user_id' => $adminUser->id, 'first_name' => 'Admin', 'is_profile_visible' => true]);

        $receiverProfile = $this->createDiscoverableMember();

        $this->actingAs($adminUser);

        Livewire::test(Show::class, ['memberProfile' => $receiverProfile])
            ->call('sendInterest');

        $this->assertDatabaseCount('interests', 0);
    }

    public function test_6_user_cannot_send_interest_to_self(): void
    {
        $profile = $this->createDiscoverableMember();

        $this->actingAs($profile->user);

        Livewire::test(Show::class, ['memberProfile' => $profile])
            ->call('sendInterest');

        $this->assertDatabaseCount('interests', 0);
    }

    public function test_7_cannot_send_interest_to_hidden_member(): void
    {
        $senderProfile = $this->createDiscoverableMember();

        $hiddenUser = User::factory()->create(['email_verified_at' => now(), 'is_active' => true, 'is_admin' => false]);
        $hiddenProfile = MemberProfile::create(['user_id' => $hiddenUser->id, 'first_name' => 'Hidden', 'is_profile_visible' => false]);

        $this->actingAs($senderProfile->user);

        $this->get('/members/' . $hiddenProfile->id)->assertStatus(404);
        $this->assertDatabaseCount('interests', 0);
    }

    public function test_8_cannot_send_interest_to_unverified_member(): void
    {
        $senderProfile = $this->createDiscoverableMember();

        $unverifiedUser = User::factory()->create(['email_verified_at' => null, 'is_active' => true, 'is_admin' => false]);
        $unverifiedProfile = MemberProfile::create(['user_id' => $unverifiedUser->id, 'first_name' => 'Unverified', 'is_profile_visible' => true]);

        $this->actingAs($senderProfile->user);

        $this->get('/members/' . $unverifiedProfile->id)->assertStatus(404);
        $this->assertDatabaseCount('interests', 0);
    }

    public function test_9_cannot_send_interest_to_inactive_member(): void
    {
        $senderProfile = $this->createDiscoverableMember();

        $inactiveUser = User::factory()->create(['email_verified_at' => now(), 'is_active' => false, 'is_admin' => false]);
        $inactiveProfile = MemberProfile::create(['user_id' => $inactiveUser->id, 'first_name' => 'Inactive', 'is_profile_visible' => true]);

        $this->actingAs($senderProfile->user);

        $this->get('/members/' . $inactiveProfile->id)->assertStatus(404);
        $this->assertDatabaseCount('interests', 0);
    }

    public function test_10_cannot_send_interest_to_admin(): void
    {
        $senderProfile = $this->createDiscoverableMember();

        $adminUser = User::factory()->create(['email_verified_at' => now(), 'is_active' => true, 'is_admin' => true]);
        $adminProfile = MemberProfile::create(['user_id' => $adminUser->id, 'first_name' => 'Admin', 'is_profile_visible' => true]);

        $this->actingAs($senderProfile->user);

        $this->get('/members/' . $adminProfile->id)->assertStatus(404);
        $this->assertDatabaseCount('interests', 0);
    }

    public function test_11_cannot_duplicate_pending_interest(): void
    {
        $senderProfile = $this->createDiscoverableMember();
        $receiverProfile = $this->createDiscoverableMember();

        Interest::create([
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($senderProfile->user);

        Livewire::test(Show::class, ['memberProfile' => $receiverProfile])
            ->call('sendInterest');

        $this->assertDatabaseCount('interests', 1);
    }

    public function test_12_reverse_duplicate_relationship_handled_correctly(): void
    {
        $userAProfile = $this->createDiscoverableMember();
        $userBProfile = $this->createDiscoverableMember();

        // User B has sent interest to User A
        Interest::create([
            'sender_id' => $userBProfile->user_id,
            'receiver_id' => $userAProfile->user_id,
            'status' => 'pending',
        ]);

        // User A tries to send interest to User B
        $this->actingAs($userAProfile->user);

        Livewire::test(Show::class, ['memberProfile' => $userBProfile])
            ->call('sendInterest');

        $this->assertDatabaseCount('interests', 1);
    }

    public function test_13_receiver_sees_pending_received_interest(): void
    {
        $senderProfile = $this->createDiscoverableMember([], ['first_name' => 'SenderMember']);
        $receiverProfile = $this->createDiscoverableMember();

        Interest::create([
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($receiverProfile->user);

        Livewire::test(Received::class)
            ->assertSee('SenderMember');
    }

    public function test_14_sender_sees_pending_sent_interest(): void
    {
        $senderProfile = $this->createDiscoverableMember();
        $receiverProfile = $this->createDiscoverableMember([], ['first_name' => 'ReceiverMember']);

        Interest::create([
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($senderProfile->user);

        Livewire::test(Sent::class)
            ->assertSee('ReceiverMember');
    }

    public function test_15_and_16_and_17_receiver_can_accept_and_creates_match(): void
    {
        $senderProfile = $this->createDiscoverableMember();
        $receiverProfile = $this->createDiscoverableMember();

        $interest = Interest::create([
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($receiverProfile->user);

        Livewire::test(Received::class)
            ->call('accept', $interest->id);

        $this->assertDatabaseHas('interests', [
            'id' => $interest->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('matches', [
            'user_one_id' => min($senderProfile->user_id, $receiverProfile->user_id),
            'user_two_id' => max($senderProfile->user_id, $receiverProfile->user_id),
        ]);
    }

    public function test_18_duplicate_match_cannot_be_created(): void
    {
        $profileA = $this->createDiscoverableMember();
        $profileB = $this->createDiscoverableMember();

        UserMatch::createMatch($profileA->user_id, $profileB->user_id);
        UserMatch::createMatch($profileA->user_id, $profileB->user_id);

        $this->assertDatabaseCount('matches', 1);
    }

    public function test_19_20_21_receiver_can_reject_and_does_not_create_match(): void
    {
        $senderProfile = $this->createDiscoverableMember();
        $receiverProfile = $this->createDiscoverableMember();

        $interest = Interest::create([
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($receiverProfile->user);

        Livewire::test(Received::class)
            ->call('reject', $interest->id);

        $this->assertDatabaseHas('interests', [
            'id' => $interest->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseCount('matches', 0);
    }

    public function test_22_sender_can_cancel_pending_interest(): void
    {
        $senderProfile = $this->createDiscoverableMember();
        $receiverProfile = $this->createDiscoverableMember();

        $interest = Interest::create([
            'sender_id' => $senderProfile->user_id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($senderProfile->user);

        Livewire::test(Sent::class)
            ->call('cancel', $interest->id);

        $this->assertDatabaseHas('interests', [
            'id' => $interest->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_23_24_25_26_unauthorized_user_cannot_manipulate_others_interest(): void
    {
        $userA = $this->createDiscoverableMember();
        $userB = $this->createDiscoverableMember();
        $userC = $this->createDiscoverableMember();

        $interest = Interest::create([
            'sender_id' => $userA->user_id,
            'receiver_id' => $userB->user_id,
            'status' => 'pending',
        ]);

        // User C tries to accept/reject User A -> User B interest
        $this->actingAs($userC->user);

        try {
            Livewire::test(Received::class)->call('accept', $interest->id);
        } catch (\Throwable $e) {
            // Caught model exception or 404 response
        }

        // Verify Interest remains pending and no match was created
        $this->assertDatabaseHas('interests', [
            'id' => $interest->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseCount('matches', 0);
    }

    public function test_27_connections_page_shows_only_current_users_matches(): void
    {
        $userA = $this->createDiscoverableMember([], ['first_name' => 'UserAPartner']);
        $userB = $this->createDiscoverableMember([], ['first_name' => 'UserBPartner']);
        $userC = $this->createDiscoverableMember([], ['first_name' => 'UserCPartner']);

        UserMatch::createMatch($userA->user_id, $userB->user_id);

        $this->actingAs($userA->user);

        Livewire::test(ConnectionsIndex::class)
            ->assertSee('UserBPartner')
            ->assertDontSee('UserCPartner');
    }

    public function test_28_dashboard_counts_are_real(): void
    {
        $userA = $this->createDiscoverableMember();
        $userB = $this->createDiscoverableMember();

        Interest::create([
            'sender_id' => $userB->user_id,
            'receiver_id' => $userA->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($userA->user);

        $this->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('1 Received');
    }

    public function test_29_phone_and_email_are_not_exposed_in_interests_or_connections(): void
    {
        $senderUser = User::factory()->create(['email' => 'secret_sender_email@example.com', 'email_verified_at' => now(), 'is_active' => true]);
        $senderProfile = MemberProfile::create(['user_id' => $senderUser->id, 'first_name' => 'Sender', 'phone' => '+8801700998877', 'is_profile_visible' => true]);

        $receiverProfile = $this->createDiscoverableMember();

        Interest::create([
            'sender_id' => $senderUser->id,
            'receiver_id' => $receiverProfile->user_id,
            'status' => 'pending',
        ]);

        $this->actingAs($receiverProfile->user);

        $this->get('/member/interests/received')
            ->assertStatus(200)
            ->assertDontSee('secret_sender_email@example.com')
            ->assertDontSee('+8801700998877');
    }
}
