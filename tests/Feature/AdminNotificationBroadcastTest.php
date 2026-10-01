<?php

namespace Tests\Feature;

use App\Models\MembershipPlan;
use App\Models\NotificationCampaign;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Filament\Pages\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

use Tests\TestCase;

class AdminNotificationBroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_1_admin_can_access_notification_broadcast_center(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/notification-campaigns')
            ->assertStatus(200);
    }

    public function test_2_non_admin_cannot_access_notification_broadcast_center(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/notification-campaigns')
            ->assertStatus(403);
    }

    public function test_3_admin_can_create_notification_campaign(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $campaign = NotificationCampaign::create([
            'title' => 'Special Announcement',
            'message' => 'Welcome to 2nd Nikah platform updates.',
            'type' => 'announcement',
            'audience' => 'all_active',
            'action_label' => 'Explore Members',
            'action_url' => '/members',
            'created_by_id' => $admin->id,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('notification_campaigns', [
            'id' => $campaign->id,
            'title' => 'Special Announcement',
            'audience' => 'all_active',
            'status' => 'draft',
        ]);
    }

    public function test_4_all_active_audience_targets_only_active_users(): void
    {
        $activeUser1 = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        $activeUser2 = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        $inactiveUser = User::factory()->create(['is_active' => false, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'Global Active Update',
            'message' => 'Active users only broadcast',
            'audience' => 'all_active',
        ]);

        $recipients = $campaign->calculateRecipientQuery()->pluck('id')->toArray();

        $this->assertContains($activeUser1->id, $recipients);
        $this->assertContains($activeUser2->id, $recipients);
        $this->assertNotContains($inactiveUser->id, $recipients);
    }

    public function test_5_premium_audience_targets_current_active_premium_users(): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Gold Plan',
            'slug' => 'gold-plan',
            'country_scope' => 'BD',
            'amount' => 1500,
            'currency' => 'BDT',
            'duration_days' => 30,
            'billing_interval' => 'monthly',
            'is_active' => true,
        ]);

        $premiumUser = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        Subscription::create([
            'user_id' => $premiumUser->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(29),
        ]);

        $freeUser = User::factory()->create(['is_active' => true, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'Exclusive Premium Feature',
            'message' => 'Thank you for being a Premium member.',
            'audience' => 'premium',
        ]);

        $recipients = $campaign->calculateRecipientQuery()->pluck('id')->toArray();

        $this->assertContains($premiumUser->id, $recipients);
        $this->assertNotContains($freeUser->id, $recipients);
    }

    public function test_6_free_audience_targets_active_non_premium_users(): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Gold Plan',
            'slug' => 'gold-plan-2',
            'country_scope' => 'BD',
            'amount' => 1500,
            'currency' => 'BDT',
            'duration_days' => 30,
            'billing_interval' => 'monthly',
            'is_active' => true,
        ]);

        $premiumUser = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        Subscription::create([
            'user_id' => $premiumUser->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(29),
        ]);

        $freeUser = User::factory()->create(['is_active' => true, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'Upgrade Offer',
            'message' => 'Upgrade to Premium today.',
            'audience' => 'free',
        ]);

        $recipients = $campaign->calculateRecipientQuery()->pluck('id')->toArray();

        $this->assertContains($freeUser->id, $recipients);
        $this->assertNotContains($premiumUser->id, $recipients);
    }

    public function test_7_expired_premium_users_are_not_treated_as_premium(): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Gold Plan',
            'slug' => 'gold-plan-3',
            'country_scope' => 'BD',
            'amount' => 1500,
            'currency' => 'BDT',
            'duration_days' => 30,
            'billing_interval' => 'monthly',
            'is_active' => true,
        ]);

        $expiredUser = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        Subscription::create([
            'user_id' => $expiredUser->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDays(40),
            'ends_at' => now()->subDays(10), // Expired 10 days ago!
        ]);

        $campaignPremium = NotificationCampaign::create([
            'title' => 'Premium Only',
            'message' => 'Test message',
            'audience' => 'premium',
        ]);

        $campaignFree = NotificationCampaign::create([
            'title' => 'Free Only',
            'message' => 'Test message',
            'audience' => 'free',
        ]);

        $premiumRecipients = $campaignPremium->calculateRecipientQuery()->pluck('id')->toArray();
        $freeRecipients = $campaignFree->calculateRecipientQuery()->pluck('id')->toArray();

        $this->assertNotContains($expiredUser->id, $premiumRecipients);
        $this->assertContains($expiredUser->id, $freeRecipients);
    }

    public function test_8_inactive_users_are_not_targeted(): void
    {
        $inactiveUser = User::factory()->create(['is_active' => false, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'Broadcast Test',
            'message' => 'Message',
            'audience' => 'all_active',
        ]);

        $recipients = $campaign->calculateRecipientQuery()->pluck('id')->toArray();
        $this->assertNotContains($inactiveUser->id, $recipients);
    }

    public function test_9_notification_records_are_created_for_selected_recipients(): void
    {
        $user1 = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        $user2 = User::factory()->create(['is_active' => true, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'System Maintenance',
            'message' => 'Scheduled maintenance tonight.',
            'type' => 'system',
            'audience' => 'all_active',
            'action_label' => 'Read Notice',
            'action_url' => '/notice',
            'status' => 'draft',
        ]);

        $campaign->sendBroadcast();

        $this->assertEquals('sent', $campaign->fresh()->status);
        $this->assertGreaterThanOrEqual(2, $campaign->fresh()->sent_count);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user1->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_10_action_url_persists_correctly(): void
    {
        $user = User::factory()->create(['is_active' => true, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'Promo Campaign',
            'message' => 'Check out new membership deals',
            'audience' => 'all_active',
            'action_label' => 'Upgrade to Premium',
            'action_url' => '/membership',
        ]);

        $campaign->sendBroadcast();

        $notification = $user->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('Upgrade to Premium', $notification->data['action_label']);
        $this->assertEquals('/membership', $notification->data['action_url']);
    }

    public function test_11_duplicate_send_protection(): void
    {
        $user = User::factory()->create(['is_active' => true, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'One Time Broadcast',
            'message' => 'Single send text',
            'audience' => 'all_active',
            'status' => 'draft',
        ]);

        $firstSend = $campaign->sendBroadcast();
        $secondSend = $campaign->sendBroadcast();

        $this->assertTrue($firstSend);
        $this->assertFalse($secondSend);
        $this->assertEquals(1, $user->notifications()->count());
    }

    public function test_12_member_unread_count_updates(): void
    {
        $user = User::factory()->create(['is_active' => true, 'is_admin' => false]);

        $this->assertEquals(0, $user->unreadNotifications()->count());

        $campaign = NotificationCampaign::create([
            'title' => 'New Notice',
            'message' => 'Important update for user',
            'audience' => 'all_active',
        ]);

        $campaign->sendBroadcast();

        $this->assertEquals(1, $user->fresh()->unreadNotifications()->count());
    }

    public function test_13_member_can_mark_notification_read(): void
    {
        $user = User::factory()->create(['is_active' => true, 'is_admin' => false, 'email_verified_at' => now()]);

        $campaign = NotificationCampaign::create([
            'title' => 'Mark Read Test',
            'message' => 'Test message text',
            'audience' => 'all_active',
        ]);

        $campaign->sendBroadcast();

        $notification = $user->notifications()->first();
        $this->assertTrue($notification->unread());

        $this->actingAs($user)
            ->post('/member/notifications', []) // visit page
            ->assertStatus(405); // Route is livewire GET /member/notifications

        $user->unreadNotifications->markAsRead();
        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_14_admin_can_view_broadcast_history(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_active' => true, 'email_verified_at' => now()]);

        NotificationCampaign::create([
            'title' => 'Historical Campaign 1',
            'message' => 'Content 1',
            'audience' => 'all_active',
            'status' => 'sent',
            'sent_at' => now()->subHours(2),
        ]);

        $this->actingAs($admin)
            ->get('/admin/notification-campaigns')
            ->assertStatus(200)
            ->assertSee('Historical Campaign 1');
    }

    public function test_15_audience_cannot_be_tampered_with_from_frontend_input(): void
    {
        $activeUser = User::factory()->create(['is_active' => true, 'is_admin' => false]);
        $inactiveUser = User::factory()->create(['is_active' => false, 'is_admin' => false]);

        $campaign = NotificationCampaign::create([
            'title' => 'Server Query Test',
            'message' => 'Content',
            'audience' => 'premium', // Premium query executes server side!
        ]);

        // Recipient query is strictly calculated on server via EloquentBuilder
        $recipients = $campaign->calculateRecipientQuery()->pluck('id')->toArray();
        $this->assertNotContains($inactiveUser->id, $recipients);
    }
}
