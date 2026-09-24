<?php

namespace Tests\Feature;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_visible_member_appears_in_members_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Visible',
            'last_name' => 'Member',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members');

        $response->assertStatus(200);
        $response->assertSee('Visible Member');
    }

    public function test_unverified_member_does_not_appear_in_members_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_active' => true,
            'is_admin' => false,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Unverified',
            'last_name' => 'Member',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members');

        $response->assertStatus(200);
        $response->assertDontSee('Unverified Member');
    }

    public function test_invisible_member_does_not_appear_in_members_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Hidden',
            'last_name' => 'Member',
            'is_profile_visible' => false,
        ]);

        $response = $this->get('/members');

        $response->assertStatus(200);
        $response->assertDontSee('Hidden Member');
    }

    public function test_inactive_member_does_not_appear_in_members_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => false,
            'is_admin' => false,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Inactive',
            'last_name' => 'Member',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members');

        $response->assertStatus(200);
        $response->assertDontSee('Inactive Member');
    }

    public function test_admin_user_does_not_appear_in_members_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => true,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members');

        $response->assertStatus(200);
        $response->assertDontSee('Admin User');
    }

    public function test_member_without_profile_does_not_appear_in_members_list(): void
    {
        $user = User::factory()->create([
            'name' => 'NoProfile User',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $response = $this->get('/members');

        $response->assertStatus(200);
        $response->assertDontSee('NoProfile User');
    }

    public function test_public_profile_details_page_works_for_discoverable_member(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Discoverable',
            'last_name' => 'Profile',
            'is_profile_visible' => true,
            'about_me' => 'This is a public bio statement.',
        ]);

        $response = $this->get('/members/' . $profile->id);

        $response->assertStatus(200);
        $response->assertSee('Discoverable Profile');
        $response->assertSee('This is a public bio statement.');
    }

    public function test_hidden_member_profile_returns_404_not_found(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Secret',
            'last_name' => 'Profile',
            'is_profile_visible' => false,
        ]);

        $response = $this->get('/members/' . $profile->id);

        $response->assertStatus(404);
    }

    public function test_unverified_profile_returns_404_not_found(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Unverified',
            'last_name' => 'User',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members/' . $profile->id);

        $response->assertStatus(404);
    }

    public function test_inactive_member_returns_404_not_found(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => false,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Deactivated',
            'last_name' => 'User',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members/' . $profile->id);

        $response->assertStatus(404);
    }

    public function test_phone_number_is_not_exposed_publicly(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'PrivatePhone',
            'last_name' => 'User',
            'phone' => '+8801999887766',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members/' . $profile->id);

        $response->assertStatus(200);
        $response->assertDontSee('+8801999887766');
    }

    public function test_email_address_is_not_exposed_publicly(): void
    {
        $user = User::factory()->create([
            'email' => 'private_member_email@example.com',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'PrivateEmail',
            'last_name' => 'User',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/members/' . $profile->id);

        $response->assertStatus(200);
        $response->assertDontSee('private_member_email@example.com');
    }

    public function test_age_is_calculated_dynamically_from_date_of_birth(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'AgeTest',
            'date_of_birth' => now()->subYears(30)->format('Y-m-d'),
            'is_profile_visible' => true,
        ]);

        $this->assertEquals(30, $profile->age);
    }

    public function test_homepage_recent_members_uses_real_database_records(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        $profile = MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'HomepageMember',
            'is_profile_visible' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('HomepageMember');
    }

    public function test_pagination_works_on_members_index(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $u = User::factory()->create([
                'email_verified_at' => now(),
                'is_active' => true,
                'is_admin' => false,
            ]);

            MemberProfile::create([
                'user_id' => $u->id,
                'first_name' => "PaginatedMember{$i}",
                'is_profile_visible' => true,
            ]);
        }

        $response = $this->get('/members');

        $response->assertStatus(200);
    }
}
