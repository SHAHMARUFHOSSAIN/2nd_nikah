<?php

namespace Tests\Feature;

use App\Livewire\Cms\PageShow;
use App\Livewire\Member\Notifications\Index as NotificationsIndex;
use App\Livewire\Member\Settings\Index as SettingsIndex;
use App\Livewire\Member\Shortlists\Index as ShortlistsIndex;
use App\Livewire\Member\Visitors\Index as VisitorsIndex;
use App\Livewire\Members\Show as MemberShow;
use App\Models\Block;
use App\Models\CmsPage;
use App\Models\MemberProfile;
use App\Models\ProfileVisit;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Shortlist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

use Livewire\Livewire;
use Tests\TestCase;

class Phase7Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SettingSeeder::class);
        $this->seed(\Database\Seeders\CmsPageSeeder::class);
    }

    private function createVerifiedMember(array $userAttributes = [], array $profileAttributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ], $userAttributes));

        MemberProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => 'Member',
            'last_name' => 'User',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'divorced',
            'religion' => 'Islam',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'is_profile_complete' => true,
            'is_profile_visible' => true,
        ], $profileAttributes));

        return $user->fresh(['memberProfile']);
    }

    /** @test */
    public function member_can_view_notifications_page(): void
    {
        $user = $this->createVerifiedMember();

        $response = $this->actingAs($user)->get(route('member.notifications.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function member_can_block_and_unblock_another_user(): void
    {
        $userA = $this->createVerifiedMember(['email' => 'usera@example.com']);
        $userB = $this->createVerifiedMember(['email' => 'userb@example.com']);

        $this->actingAs($userA);

        Livewire::test(MemberShow::class, ['memberProfile' => $userB->memberProfile])
            ->call('toggleBlock')
            ->assertRedirect(route('members.index'));

        $this->assertTrue($userA->hasBlocked($userB->id));

        // Attempting to visit userB profile as userA should now result in 404
        $this->actingAs($userA)->get(route('members.show', $userB->memberProfile))
            ->assertStatus(404);

        // Unblock userB directly via model helper
        Block::unblockUser($userA->id, $userB->id);
        $this->assertFalse($userA->hasBlocked($userB->id));
    }

    /** @test */
    public function blocked_users_are_excluded_from_member_directory(): void
    {
        $userA = $this->createVerifiedMember(['email' => 'usera@example.com'], ['first_name' => 'Alice']);
        $userB = $this->createVerifiedMember(['email' => 'userb@example.com'], ['first_name' => 'Bob']);

        Block::blockUser($userA->id, $userB->id);

        $response = $this->actingAs($userA)->get(route('members.index'));

        $response->assertStatus(200);
        $response->assertSee('Alice User');
        $response->assertDontSee('Bob User');
    }

    /** @test */
    public function member_can_submit_report_against_another_user(): void
    {
        $reporter = $this->createVerifiedMember(['email' => 'reporter@example.com']);
        $target = $this->createVerifiedMember(['email' => 'target@example.com']);

        $this->actingAs($reporter);

        Livewire::test(MemberShow::class, ['memberProfile' => $target->memberProfile])
            ->set('reportReason', 'Inappropriate Content')
            ->set('reportDescription', 'Testing report submission')
            ->call('submitReport');

        $this->assertDatabaseHas('reports', [
            'reporter_id' => $reporter->id,
            'reported_user_id' => $target->id,
            'reason' => 'Inappropriate Content',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function member_cannot_submit_duplicate_pending_reports(): void
    {
        $reporter = $this->createVerifiedMember(['email' => 'reporter2@example.com']);
        $target = $this->createVerifiedMember(['email' => 'target2@example.com']);

        Report::create([
            'reporter_id' => $reporter->id,
            'reported_user_id' => $target->id,
            'reason' => 'Spam',
            'status' => 'pending',
        ]);

        $this->actingAs($reporter);

        Livewire::test(MemberShow::class, ['memberProfile' => $target->memberProfile])
            ->set('reportReason', 'Spam')
            ->call('submitReport')
            ->assertHasNoErrors();

        $this->assertEquals(1, Report::where('reporter_id', $reporter->id)->where('reported_user_id', $target->id)->count());
    }

    /** @test */
    public function member_can_toggle_shortlist_on_a_profile(): void
    {
        $userA = $this->createVerifiedMember(['email' => 'usera3@example.com']);
        $userB = $this->createVerifiedMember(['email' => 'userb3@example.com']);

        $this->actingAs($userA);

        Livewire::test(MemberShow::class, ['memberProfile' => $userB->memberProfile])
            ->call('toggleShortlist')
            ->assertSet('isShortlisted', true);

        $this->assertTrue($userA->hasShortlisted($userB->id));

        Livewire::test(MemberShow::class, ['memberProfile' => $userB->memberProfile])
            ->call('toggleShortlist')
            ->assertSet('isShortlisted', false);

        $this->assertFalse($userA->hasShortlisted($userB->id));
    }

    /** @test */
    public function member_shortlists_page_renders_shortlisted_profiles(): void
    {
        $userA = $this->createVerifiedMember(['email' => 'usera4@example.com']);
        $userB = $this->createVerifiedMember(['email' => 'userb4@example.com']);

        Shortlist::create([
            'user_id' => $userA->id,
            'shortlisted_user_id' => $userB->id,
        ]);

        $response = $this->actingAs($userA)->get(route('member.shortlists.index'));

        $response->assertStatus(200);
        $response->assertSee($userB->memberProfile->full_name);
    }

    /** @test */
    public function visiting_a_member_profile_records_a_profile_visit(): void
    {
        $visitor = $this->createVerifiedMember(['email' => 'visitor@example.com']);
        $visited = $this->createVerifiedMember(['email' => 'visited@example.com']);

        $this->actingAs($visitor)->get(route('members.show', $visited->memberProfile));

        $this->assertDatabaseHas('profile_visits', [
            'visitor_id' => $visitor->id,
            'visited_user_id' => $visited->id,
        ]);

        $response = $this->actingAs($visited)->get(route('member.visitors.index'));
        $response->assertStatus(200);
        $response->assertSee($visitor->memberProfile->full_name);
    }

    /** @test */
    public function member_settings_toggle_profile_visibility_and_account_status(): void
    {
        $user = $this->createVerifiedMember();

        $this->actingAs($user);

        Livewire::test(SettingsIndex::class)
            ->call('toggleProfileVisibility')
            ->assertSet('isProfileVisible', false);

        $this->assertFalse((bool) $user->memberProfile->fresh()->is_profile_visible);

        Livewire::test(SettingsIndex::class)
            ->call('toggleAccountActive')
            ->assertSet('isActive', false);

        $this->assertFalse((bool) $user->fresh()->is_active);
    }

    /** @test */
    public function member_settings_can_update_password(): void
    {
        $user = $this->createVerifiedMember(['password' => Hash::make('old-password-123')]);

        $this->actingAs($user);

        Livewire::test(SettingsIndex::class)
            ->set('current_password', 'old-password-123')
            ->set('password', 'new-secure-password-456')
            ->set('password_confirmation', 'new-secure-password-456')
            ->call('updatePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('new-secure-password-456', $user->fresh()->password));
    }

    /** @test */
    public function published_cms_pages_are_accessible_via_slug(): void
    {
        $page = CmsPage::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<p>These are the official terms of service.</p>',
            'is_published' => true,
        ]);

        $response = $this->get('/terms-of-service');
        $response->assertStatus(200);
        $response->assertSee('Terms of Service');
        $response->assertSee('official terms of service');
    }

    /** @test */
    public function unpublished_cms_pages_return_404(): void
    {
        CmsPage::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'content' => '<p>Draft content</p>',
            'is_published' => false,
        ]);

        $response = $this->get('/draft-page');
        $response->assertStatus(404);
    }

    /** @test */
    public function settings_key_value_store_works_correctly(): void
    {
        Setting::set('site_name', '2nd Nikah Production');
        $this->assertEquals('2nd Nikah Production', Setting::get('site_name'));

        Setting::set('custom_key', 'custom_value', 'general', 'string', 'Custom key');
        $this->assertEquals('custom_value', Setting::get('custom_key'));
    }
}
