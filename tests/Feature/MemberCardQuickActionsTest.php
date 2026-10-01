<?php

namespace Tests\Feature;

use App\Models\Interest;
use App\Models\MemberProfile;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MemberCardQuickActionsTest extends TestCase
{
    use RefreshDatabase;

    private function createMember(array $userAttrs = [], array $profileAttrs = []): User
    {
        $user = User::factory()->create(array_merge([
            'is_active' => true,
            'is_admin' => false,
            'email_verified_at' => now(),
        ], $userAttrs));

        MemberProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'Member',
            'gender' => 'female',
            'date_of_birth' => '1995-05-15',
            'marital_status' => 'Never Married',
            'religion' => 'Islam',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'is_profile_complete' => true,
            'is_profile_visible' => true,
        ], $profileAttrs));

        return $user;
    }

    public function test_1_guest_is_redirected_to_login_on_interest_or_shortlist(): void
    {
        $targetUser = $this->createMember();

        Livewire::test(\App\Livewire\Members\Index::class)
            ->call('sendInterest', $targetUser->id)
            ->assertRedirect(route('login'));

        Livewire::test(\App\Livewire\Members\Index::class)
            ->call('toggleShortlist', $targetUser->id)
            ->assertRedirect(route('login'));
    }

    public function test_2_eligible_user_can_send_interest_from_member_card(): void
    {
        $sender = $this->createMember(['email' => 'sender@example.com']);
        $receiver = $this->createMember(['email' => 'receiver@example.com']);

        Livewire::actingAs($sender)
            ->test(\App\Livewire\Members\Index::class)
            ->call('sendInterest', $receiver->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('interests', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);
    }

    public function test_3_duplicate_interest_sending_is_prevented(): void
    {
        $sender = $this->createMember(['email' => 'sender@example.com']);
        $receiver = $this->createMember(['email' => 'receiver@example.com']);

        Interest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        Livewire::actingAs($sender)
            ->test(\App\Livewire\Members\Index::class)
            ->call('sendInterest', $receiver->id);

        $this->assertEquals(1, Interest::where('sender_id', $sender->id)->where('receiver_id', $receiver->id)->count());
    }

    public function test_4_user_can_toggle_shortlist_on_member_card(): void
    {
        $user = $this->createMember(['email' => 'user@example.com']);
        $target = $this->createMember(['email' => 'target@example.com']);

        // Toggle ON
        Livewire::actingAs($user)
            ->test(\App\Livewire\Members\Index::class)
            ->call('toggleShortlist', $target->id);

        $this->assertTrue($user->hasShortlisted($target->id));

        // Toggle OFF
        Livewire::actingAs($user)
            ->test(\App\Livewire\Members\Index::class)
            ->call('toggleShortlist', $target->id);

        $this->assertFalse($user->hasShortlisted($target->id));
    }

    public function test_5_self_shortlist_is_prevented(): void
    {
        $user = $this->createMember(['email' => 'user@example.com']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Members\Index::class)
            ->call('toggleShortlist', $user->id);

        $this->assertFalse($user->hasShortlisted($user->id));
    }

    public function test_6_self_interest_is_prevented(): void
    {
        $user = $this->createMember(['email' => 'user@example.com']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Members\Index::class)
            ->call('sendInterest', $user->id);

        $this->assertEquals(0, Interest::where('sender_id', $user->id)->count());
    }

    public function test_7_message_button_appears_only_when_matched_and_links_appropriately(): void
    {
        $user = $this->createMember(['email' => 'user@example.com']);
        $partner = $this->createMember(['email' => 'partner@example.com']);

        // Create mutual match
        UserMatch::createMatch($user->id, $partner->id);

        // A. Non-Premium User with Match:
        $responseNonPremium = $this->actingAs($user)->get('/members');
        $responseNonPremium->assertStatus(200);
        $responseNonPremium->assertSee('Upgrade to Premium to Message');

        // B. Premium User with Match:
        $plan = MembershipPlan::create([
            'name' => 'Premium Gold',
            'slug' => 'premium-gold',
            'country_scope' => 'BD',
            'amount' => 2000,
            'currency' => 'BDT',
            'duration_days' => 30,
            'billing_interval' => 'monthly',
            'is_active' => true,
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(29),
        ]);

        $responsePremium = $this->actingAs($user->fresh())->get('/members');
        $responsePremium->assertStatus(200);
        $responsePremium->assertSee('Send Message');
    }

    public function test_8_blocked_users_cannot_interact(): void
    {
        $user = $this->createMember(['email' => 'user@example.com']);
        $blockedUser = $this->createMember(['email' => 'blocked@example.com']);

        \App\Models\Block::blockUser($user->id, $blockedUser->id);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Members\Index::class)
            ->call('sendInterest', $blockedUser->id);

        $this->assertEquals(0, Interest::where('sender_id', $user->id)->where('receiver_id', $blockedUser->id)->count());
    }
}
