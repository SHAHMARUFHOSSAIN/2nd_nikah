<?php

namespace Tests\Feature;

use App\Livewire\Member\Profile;
use App\Livewire\Member\Profile\Photos;
use App\Livewire\Members\Show;
use App\Models\Conversation;
use App\Models\Interest;
use App\Models\MemberProfile;
use App\Models\MembershipPlan;
use App\Models\ProfilePhoto;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
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
            'first_name' => 'Tariq',
            'last_name' => 'Rahman',
            'gender' => 'male',
            'date_of_birth' => '1992-04-12',
            'marital_status' => 'Never Married',
            'religion' => 'Islam',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'is_profile_complete' => true,
            'is_profile_visible' => true,
        ], $profileAttrs));

        return $user;
    }

    public function test_1_member_can_update_first_name_last_name_and_basic_details(): void
    {
        $user = $this->createMember();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('first_name', 'Tariqul')
            ->set('last_name', 'Islam')
            ->set('date_of_birth', '1992-04-12')
            ->set('gender', 'male')
            ->set('marital_status', 'Never Married')
            ->set('religion', 'Islam')
            ->set('city', 'Chittagong')
            ->set('education', 'M.Sc. in Physics')
            ->call('saveProfile')
            ->assertHasNoErrors()
            ->assertSee('Your member profile has been updated successfully!');

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $user->id,
            'first_name' => 'Tariqul',
            'last_name' => 'Islam',
            'city' => 'Chittagong',
            'education' => 'M.Sc. in Physics',
        ]);
    }

    public function test_2_photo_upload_respects_max_6_photos_limit(): void
    {
        Storage::fake('public');

        $user = $this->createMember();

        // Create 6 existing photos
        for ($i = 1; $i <= 6; $i++) {
            ProfilePhoto::create([
                'user_id' => $user->id,
                'path' => "profile-photos/test_{$i}.jpg",
                'is_primary' => ($i === 1),
                'sort_order' => $i - 1,
            ]);
        }

        $newFile = UploadedFile::fake()->image('seventh.jpg');

        Livewire::actingAs($user)
            ->test(Photos::class)
            ->set('newPhotos', [$newFile])
            ->call('savePhotos')
            ->assertSee('Maximum gallery limit is 6 photos');

        $this->assertEquals(6, $user->profilePhotos()->count());
    }

    public function test_3_setting_primary_photo_syncs_to_member_profile_table(): void
    {
        Storage::fake('public');

        $user = $this->createMember();

        $photo1 = ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => 'profile-photos/p1.jpg',
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $photo2 = ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => 'profile-photos/p2.jpg',
            'is_primary' => false,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(Photos::class)
            ->call('setPrimary', $photo2->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('profile_photos', [
            'id' => $photo2->id,
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('profile_photos', [
            'id' => $photo1->id,
            'is_primary' => false,
        ]);

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $user->id,
            'profile_photo_path' => 'profile-photos/p2.jpg',
        ]);
    }

    public function test_4_deleting_primary_photo_sets_fallback_primary_photo(): void
    {
        Storage::fake('public');

        $user = $this->createMember();

        $photo1 = ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => 'profile-photos/p1.jpg',
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $photo2 = ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => 'profile-photos/p2.jpg',
            'is_primary' => false,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(Photos::class)
            ->call('deletePhoto', $photo1->id)
            ->assertHasNoErrors();

        $this->assertEquals(1, $user->profilePhotos()->count());

        $this->assertDatabaseHas('profile_photos', [
            'id' => $photo2->id,
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $user->id,
            'profile_photo_path' => 'profile-photos/p2.jpg',
        ]);
    }

    public function test_5_photo_reordering_works_correctly(): void
    {
        $user = $this->createMember();

        $photo1 = ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => 'profile-photos/p1.jpg',
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $photo2 = ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => 'profile-photos/p2.jpg',
            'is_primary' => false,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(Photos::class)
            ->call('moveDown', $photo1->id);

        $this->assertEquals(1, $photo1->fresh()->sort_order);
        $this->assertEquals(0, $photo2->fresh()->sort_order);
    }

    public function test_6_public_profile_displays_gallery_photos_and_action_bar_rules(): void
    {
        $member = $this->createMember();
        $viewer = $this->createMember();

        ProfilePhoto::create([
            'user_id' => $member->id,
            'path' => 'profile-photos/g1.jpg',
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        ProfilePhoto::create([
            'user_id' => $member->id,
            'path' => 'profile-photos/g2.jpg',
            'is_primary' => false,
            'sort_order' => 1,
        ]);

        $member->syncPrimaryPhotoToMemberProfile();
        $member->memberProfile->refresh();

        Livewire::actingAs($viewer)
            ->test(Show::class, ['memberProfile' => $member->memberProfile])
            ->assertSee('profile-photos/g1.jpg', false)
            ->assertSee('profile-photos/g2.jpg', false)
            ->assertSee('Send Interest')
            ->assertSee('☆ Shortlist');
    }

    public function test_7_public_profile_shows_message_button_only_for_mutual_matches_with_premium_routing(): void
    {
        $member = $this->createMember();

        // Non-premium viewer with mutual match
        $freeViewer = $this->createMember();
        UserMatch::createMatch($freeViewer->id, $member->id);

        Livewire::actingAs($freeViewer)
            ->test(Show::class, ['memberProfile' => $member->memberProfile])
            ->assertSee('💬 Message (Upgrade)');

        // Premium viewer with mutual match
        $premiumViewer = $this->createMember();
        $plan = MembershipPlan::firstOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'amount' => 1000,
                'currency' => 'BDT',
                'billing_interval' => 'monthly',
                'duration_days' => 30,
                'is_active' => true,
                'country_scope' => 'BD',
            ]
        );
        Subscription::create([
            'user_id' => $premiumViewer->id,
            'membership_plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'status' => 'active',
            'payment_method' => 'sslcommerz',
            'amount_paid' => 1000,
        ]);
        UserMatch::createMatch($premiumViewer->id, $member->id);

        Livewire::actingAs($premiumViewer)
            ->test(Show::class, ['memberProfile' => $member->memberProfile])
            ->assertSee('💬 Message Member')
            ->call('openMessage')
            ->assertRedirect();
    }
}
