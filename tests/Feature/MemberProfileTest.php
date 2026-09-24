<?php

namespace Tests\Feature;

use App\Livewire\Member\Profile;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MemberProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_verified_member_can_access_profile_page(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/member/profile');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/member/profile');

        $response->assertRedirect('/login');
    }

    public function test_member_can_create_and_update_own_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('first_name', 'Tariq')
            ->set('last_name', 'Hassan')
            ->set('date_of_birth', '1990-05-15')
            ->set('gender', 'male')
            ->set('marital_status', 'Never Married')
            ->set('religion', 'Islam')
            ->set('city', 'Dhaka')
            ->set('country', 'Bangladesh')
            ->set('phone', '+8801700000000')
            ->set('height', 175)
            ->set('education', 'B.Sc. Engineering')
            ->set('occupation', 'Software Developer')
            ->set('about_me', 'Looking for a pious and respectful life partner.')
            ->call('saveProfile');

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $user->id,
            'first_name' => 'Tariq',
            'last_name' => 'Hassan',
            'gender' => 'male',
            'city' => 'Dhaka',
            'height' => 175,
        ]);
    }

    public function test_member_cannot_update_another_members_profile(): void
    {
        $userA = User::factory()->create(['email_verified_at' => now()]);
        $userB = User::factory()->create(['email_verified_at' => now()]);

        $profileB = MemberProfile::create([
            'user_id' => $userB->id,
            'first_name' => 'Original B',
        ]);

        // User A acts on their own profile component without access to User B's profile mutation
        Livewire::actingAs($userA)
            ->test(Profile::class)
            ->set('first_name', 'User A Modified Name')
            ->call('saveProfile');

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $userB->id,
            'first_name' => 'Original B',
        ]);
    }

    public function test_profile_data_persists_in_mysql(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Persisted',
            'last_name' => 'Member',
            'city' => 'Chittagong',
        ]);

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $user->id,
            'first_name' => 'Persisted',
            'city' => 'Chittagong',
        ]);
    }

    public function test_validation_rejects_invalid_profile_data(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('first_name', '')
            ->set('gender', 'invalid_gender_value')
            ->call('saveProfile')
            ->assertHasErrors(['first_name', 'gender']);
    }

    public function test_profile_completion_percentage_calculated_accurately(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        // 2 out of 13 fields filled = (2/13)*100 = 15%
        $this->assertEquals(15, $profile->calculateCompletionPercentage());

        $profile->update([
            'date_of_birth' => '1992-01-01',
            'gender' => 'female',
            'marital_status' => 'Divorced',
            'religion' => 'Islam',
            'city' => 'Sylhet',
            'country' => 'Bangladesh',
            'phone' => '+8801800000000',
            'height' => 162,
            'education' => 'Masters',
            'occupation' => 'Doctor',
            'about_me' => 'Sincere bio',
        ]);

        // 13 out of 13 fields filled = 100%
        $this->assertEquals(100, $profile->calculateCompletionPercentage());
    }

    public function test_profile_visibility_persists(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('first_name', 'Visibility')
            ->set('last_name', 'Test')
            ->set('date_of_birth', '1995-01-01')
            ->set('gender', 'female')
            ->set('marital_status', 'Never Married')
            ->set('religion', 'Islam')
            ->set('is_profile_visible', false)
            ->call('saveProfile');

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $user->id,
            'is_profile_visible' => false,
        ]);
    }

    public function test_profile_photo_upload_validation_works(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $file = File::image('avatar.jpg');

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('first_name', 'Photo')
            ->set('last_name', 'Tester')
            ->set('date_of_birth', '1995-01-01')
            ->set('gender', 'male')
            ->set('marital_status', 'Never Married')
            ->set('religion', 'Islam')
            ->set('photo', $file)
            ->call('saveProfile');

        $profile = MemberProfile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile->profile_photo_path);
        Storage::disk('public')->assertExists($profile->profile_photo_path);
    }

    public function test_admin_can_view_member_profiles_in_filament(): void
    {
        $admin = User::factory()->create([
            'email_verified_at' => now(),
            'is_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/member-profiles');

        $response->assertStatus(200);
    }
}
