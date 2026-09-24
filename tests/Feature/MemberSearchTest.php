<?php

namespace Tests\Feature;

use App\Livewire\Members\Search;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MemberSearchTest extends TestCase
{
    use RefreshDatabase;

    private function createDiscoverableUser(array $userAttributes = [], array $profileAttributes = []): MemberProfile
    {
        $user = User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ], $userAttributes));

        return MemberProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => 'Valid',
            'last_name' => 'Member',
            'is_profile_visible' => true,
        ], $profileAttributes));
    }

    public function test_1_search_page_loads(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('Discover Matrimonial Members');
    }

    public function test_2_verified_visible_active_non_admin_members_appear(): void
    {
        $profile = $this->createDiscoverableUser([], ['first_name' => 'DiscoverableMember']);

        Livewire::test(Search::class)
            ->assertSee('DiscoverableMember');
    }

    public function test_3_unverified_members_do_not_appear(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_active' => true,
            'is_admin' => false,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'UnverifiedMember',
            'is_profile_visible' => true,
        ]);

        Livewire::test(Search::class)
            ->assertDontSee('UnverifiedMember');
    }

    public function test_4_hidden_members_do_not_appear(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'HiddenMember',
            'is_profile_visible' => false,
        ]);

        Livewire::test(Search::class)
            ->assertDontSee('HiddenMember');
    }

    public function test_5_inactive_members_do_not_appear(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => false,
            'is_admin' => false,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'InactiveMember',
            'is_profile_visible' => true,
        ]);

        Livewire::test(Search::class)
            ->assertDontSee('InactiveMember');
    }

    public function test_6_admin_members_do_not_appear(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => true,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'AdminMember',
            'is_profile_visible' => true,
        ]);

        Livewire::test(Search::class)
            ->assertDontSee('AdminMember');
    }

    public function test_7_members_without_profiles_do_not_appear(): void
    {
        User::factory()->create([
            'name' => 'NoProfileUser',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ]);

        Livewire::test(Search::class)
            ->assertDontSee('NoProfileUser');
    }

    public function test_8_gender_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'MaleUser', 'gender' => 'male']);
        $this->createDiscoverableUser([], ['first_name' => 'FemaleUser', 'gender' => 'female']);

        Livewire::test(Search::class)
            ->set('gender', 'male')
            ->assertSee('MaleUser')
            ->assertDontSee('FemaleUser');
    }

    public function test_9_religion_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'MuslimUser', 'religion' => 'Islam']);
        $this->createDiscoverableUser([], ['first_name' => 'HinduUser', 'religion' => 'Hinduism']);

        Livewire::test(Search::class)
            ->set('religion', 'Islam')
            ->assertSee('MuslimUser')
            ->assertDontSee('HinduUser');
    }

    public function test_10_marital_status_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'DivorcedUser', 'marital_status' => 'Divorced']);
        $this->createDiscoverableUser([], ['first_name' => 'SingleUser', 'marital_status' => 'Never Married']);

        Livewire::test(Search::class)
            ->set('marital_status', 'Divorced')
            ->assertSee('DivorcedUser')
            ->assertDontSee('SingleUser');
    }

    public function test_11_city_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'DhakaResident', 'city' => 'Dhaka']);
        $this->createDiscoverableUser([], ['first_name' => 'SylhetResident', 'city' => 'Sylhet']);

        Livewire::test(Search::class)
            ->set('city', 'Dhaka')
            ->assertSee('DhakaResident')
            ->assertDontSee('SylhetResident');
    }

    public function test_12_country_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'BDUser', 'country' => 'Bangladesh']);
        $this->createDiscoverableUser([], ['first_name' => 'UKUser', 'country' => 'United Kingdom']);

        Livewire::test(Search::class)
            ->set('country', 'Bangladesh')
            ->assertSee('BDUser')
            ->assertDontSee('UKUser');
    }

    public function test_13_education_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'EngineerUser', 'education' => 'B.Sc in Software Engineering']);
        $this->createDiscoverableUser([], ['first_name' => 'DoctorUser', 'education' => 'MBBS Medical Doctor']);

        Livewire::test(Search::class)
            ->set('education', 'Engineering')
            ->assertSee('EngineerUser')
            ->assertDontSee('DoctorUser');
    }

    public function test_14_children_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'ParentUser', 'children_count' => 2]);
        $this->createDiscoverableUser([], ['first_name' => 'NoChildrenUser', 'children_count' => 0]);

        Livewire::test(Search::class)
            ->set('children', 'yes')
            ->assertSee('ParentUser')
            ->assertDontSee('NoChildrenUser')
            ->set('children', 'no')
            ->assertSee('NoChildrenUser')
            ->assertDontSee('ParentUser');
    }

    public function test_15_height_range_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'TallUser', 'height' => 185]);
        $this->createDiscoverableUser([], ['first_name' => 'ShortUser', 'height' => 155]);

        Livewire::test(Search::class)
            ->set('min_height', '180')
            ->set('max_height', '190')
            ->assertSee('TallUser')
            ->assertDontSee('ShortUser');
    }

    public function test_16_minimum_age_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'Age35User', 'date_of_birth' => now()->subYears(35)->format('Y-m-d')]);
        $this->createDiscoverableUser([], ['first_name' => 'Age22User', 'date_of_birth' => now()->subYears(22)->format('Y-m-d')]);

        Livewire::test(Search::class)
            ->set('min_age', '30')
            ->assertSee('Age35User')
            ->assertDontSee('Age22User');
    }

    public function test_17_maximum_age_filter_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'Age28User', 'date_of_birth' => now()->subYears(28)->format('Y-m-d')]);
        $this->createDiscoverableUser([], ['first_name' => 'Age45User', 'date_of_birth' => now()->subYears(45)->format('Y-m-d')]);

        Livewire::test(Search::class)
            ->set('max_age', '30')
            ->assertSee('Age28User')
            ->assertDontSee('Age45User');
    }

    public function test_18_combined_filters_work_together(): void
    {
        $this->createDiscoverableUser([], [
            'first_name' => 'PerfectMatch',
            'gender' => 'female',
            'religion' => 'Islam',
            'marital_status' => 'Divorced',
            'city' => 'Dhaka',
            'children_count' => 1,
            'height' => 165,
            'date_of_birth' => now()->subYears(32)->format('Y-m-d'),
        ]);

        $this->createDiscoverableUser([], [
            'first_name' => 'MismatchUser',
            'gender' => 'female',
            'religion' => 'Islam',
            'marital_status' => 'Never Married',
            'city' => 'Chittagong',
            'children_count' => 0,
            'height' => 175,
            'date_of_birth' => now()->subYears(24)->format('Y-m-d'),
        ]);

        Livewire::test(Search::class)
            ->set('gender', 'female')
            ->set('religion', 'Islam')
            ->set('marital_status', 'Divorced')
            ->set('city', 'Dhaka')
            ->set('children', 'yes')
            ->set('min_age', '30')
            ->set('max_age', '35')
            ->assertSee('PerfectMatch')
            ->assertDontSee('MismatchUser');
    }

    public function test_19_sorting_works(): void
    {
        $this->createDiscoverableUser([], ['first_name' => 'YoungerUser', 'date_of_birth' => now()->subYears(25)->format('Y-m-d')]);
        $this->createDiscoverableUser([], ['first_name' => 'OlderUser', 'date_of_birth' => now()->subYears(40)->format('Y-m-d')]);

        Livewire::test(Search::class)
            ->set('sort', 'youngest')
            ->assertSeeInOrder(['YoungerUser', 'OlderUser'])
            ->set('sort', 'oldest_age')
            ->assertSeeInOrder(['OlderUser', 'YoungerUser']);
    }

    public function test_20_pagination_works(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $this->createDiscoverableUser([], ['first_name' => "SearchPaginatedUser{$i}"]);
        }

        Livewire::test(Search::class)
            ->assertSee('Showing')
            ->assertSee('12')
            ->assertSee('15')
            ->call('gotoPage', 2)
            ->assertSee('SearchPaginatedUser15');
    }

    public function test_21_empty_results_state_works(): void
    {
        Livewire::test(Search::class)
            ->set('city', 'NonExistentCity12345')
            ->assertSee('No members found')
            ->assertSee('Try adjusting your search filters to discover more members.')
            ->assertSee('Clear Filters');
    }
}
