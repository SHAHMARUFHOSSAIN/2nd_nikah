<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SettingSeeder::class);
    }

    /** @test */
    public function mobile_bottom_nav_renders_for_guest(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('mobile-bottom-nav');
        $response->assertSee('Home');
        $response->assertSee('Search');
        $response->assertSee('Members');
        $response->assertSee('Messages');
        $response->assertSee('Log In');
    }

    /** @test */
    public function mobile_bottom_nav_renders_for_authenticated_user(): void
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('mobile-bottom-nav');
        $response->assertSee('Profile');
    }

    /** @test */
    public function mobile_unread_messages_badge_renders_when_unread_messages_exist(): void
    {
        $user1 = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $user2 = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);

        $conversation = Conversation::create([
            'user_one_id' => min($user1->id, $user2->id),
            'user_two_id' => max($user1->id, $user2->id),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user2->id,
            'body' => 'Hello unread message',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user1)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('mobile-nav-badge');
        $response->assertSee('1');
    }

    /** @test */
    public function mobile_drawer_contains_secondary_links_and_admin_link_when_authorized(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_active' => true, 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('mobile-drawer-panel');
        $response->assertSee('Admin Panel');
        $response->assertSee('Account Settings');
    }
}
