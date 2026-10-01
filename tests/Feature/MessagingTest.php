<?php

namespace Tests\Feature;

use App\Livewire\Member\Messages\Index;
use App\Livewire\Member\Messages\Show;
use App\Models\Conversation;
use App\Models\Interest;
use App\Models\MembershipPlan;
use App\Models\MemberProfile;
use App\Models\Message;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed membership plan if empty
        if (MembershipPlan::count() === 0) {
            MembershipPlan::create([
                'name' => 'Weekly',
                'slug' => 'weekly-bdt',
                'country_scope' => 'BD',
                'amount' => 100.00,
                'currency' => 'BDT',
                'billing_interval' => 'weekly',
                'duration_days' => 7,
                'is_active' => true,
            ]);
        }
    }

    private function createVerifiedUser(array $userAttrs = [], array $profileAttrs = []): User
    {
        $user = User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ], $userAttrs));

        MemberProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => 'Member',
            'last_name' => (string) $user->id,
            'country' => 'Bangladesh',
            'is_profile_visible' => true,
        ], $profileAttrs));

        return $user;
    }

    private function makeUserPremium(User $user): Subscription
    {
        $plan = MembershipPlan::first();
        $txn = PaymentTransaction::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'transaction_id' => 'TXN_PREMIUM_' . $user->id . '_' . uniqid(),
            'amount' => $plan->amount,
            'currency' => $plan->currency,
            'status' => 'paid',
        ]);

        return Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
            'payment_transaction_id' => $txn->id,
        ]);
    }

    // -------------------------------------------------------------
    // ACCESS TESTS
    // -------------------------------------------------------------

    public function test_guest_cannot_access_messages_page(): void
    {
        $response = $this->get('/member/messages');
        $response->assertRedirect(route('login'));
    }

    public function test_unverified_user_cannot_access_messages_page(): void
    {
        $user = User::factory()->create(['email_verified_at' => null, 'is_active' => true]);
        $this->actingAs($user);

        $response = $this->get('/member/messages');
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_inactive_user_cannot_access_messages(): void
    {
        $user = $this->createVerifiedUser(['is_active' => false]);
        $this->actingAs($user);

        $response = $this->get('/member/messages');
        $response->assertStatus(403);
    }

    // -------------------------------------------------------------
    // MATCH & ELIGIBILITY TESTS
    // -------------------------------------------------------------

    public function test_unmatched_users_cannot_start_conversation(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $this->actingAs($userA);

        Livewire::test(Index::class)
            ->call('startConversation', $userB->id)
            ->assertSee('You can only message members with whom you have an accepted mutual match');

        $this->assertDatabaseCount('conversations', 0);
    }

    public function test_pending_interest_cannot_start_conversation(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        Interest::create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'status' => 'pending',
        ]);

        $this->actingAs($userA);

        Livewire::test(Index::class)
            ->call('startConversation', $userB->id)
            ->assertSee('You can only message members with whom you have an accepted mutual match');

        $this->assertDatabaseCount('conversations', 0);
    }

    public function test_rejected_interest_cannot_start_conversation(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        Interest::create([
            'sender_id' => $userA->id,
            'receiver_id' => $userB->id,
            'status' => 'rejected',
        ]);

        $this->actingAs($userA);

        Livewire::test(Index::class)
            ->call('startConversation', $userB->id)
            ->assertSee('You can only message members with whom you have an accepted mutual match');

        $this->assertDatabaseCount('conversations', 0);
    }

    public function test_accepted_mutual_match_can_start_conversation(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        UserMatch::createMatch($userA->id, $userB->id);

        $this->actingAs($userA);

        Livewire::test(Index::class)
            ->call('startConversation', $userB->id)
            ->assertRedirect();

        $this->assertDatabaseCount('conversations', 1);
    }

    // -------------------------------------------------------------
    // PREMIUM REQUIREMENT TESTS
    // -------------------------------------------------------------

    public function test_user_without_active_subscription_cannot_send_messages(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        $this->actingAs($userA);

        Livewire::test(Show::class, ['conversation' => $conversation])
            ->set('messageBody', 'Hello there')
            ->call('sendMessage')
            ->assertSee('An active Premium membership subscription is required to send messages');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_user_with_active_subscription_can_send_messages(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $this->makeUserPremium($userA);
        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        $this->actingAs($userA);

        Livewire::test(Show::class, ['conversation' => $conversation])
            ->set('messageBody', 'Assalamu Alaikum brother')
            ->call('sendMessage')
            ->assertDontSee('Premium membership subscription is required');

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $userA->id,
            'body' => 'Assalamu Alaikum brother',
        ]);
    }

    public function test_request_data_tampering_cannot_bypass_premium_requirement(): void
    {
        $userA = $this->createVerifiedUser(); // Non-premium
        $userB = $this->createVerifiedUser();

        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        $this->actingAs($userA);

        // Attempting to invoke sendMessage directly via Livewire
        Livewire::test(Show::class, ['conversation' => $conversation])
            ->set('messageBody', 'Hacked message attempt')
            ->call('sendMessage')
            ->assertSee('An active Premium membership subscription is required');

        $this->assertDatabaseCount('messages', 0);
    }

    // -------------------------------------------------------------
    // PRIVACY & AUTHORIZATION TESTS
    // -------------------------------------------------------------

    public function test_user_cannot_access_another_users_conversation(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();
        $userC = $this->createVerifiedUser();

        UserMatch::createMatch($userA->id, $userB->id);
        $conversationAB = Conversation::getOrCreateBetween($userA->id, $userB->id);

        // UserC attempts to view UserA & UserB's conversation
        $this->actingAs($userC);

        Livewire::test(Show::class, ['conversation' => $conversationAB])
            ->assertStatus(403);
    }

    public function test_user_cannot_send_message_as_another_user(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();
        $userC = $this->createVerifiedUser();

        $this->makeUserPremium($userC);
        UserMatch::createMatch($userA->id, $userB->id);
        $conversationAB = Conversation::getOrCreateBetween($userA->id, $userB->id);

        $this->actingAs($userC);

        $response = $this->get('/member/messages/' . $conversationAB->id);
        $response->assertStatus(403);

        $this->assertDatabaseCount('messages', 0);
    }

    // -------------------------------------------------------------
    // MESSAGE LOGIC, VALIDATION & PERSISTENCE
    // -------------------------------------------------------------

    public function test_empty_or_whitespace_message_is_rejected(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $this->makeUserPremium($userA);
        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        $this->actingAs($userA);

        Livewire::test(Show::class, ['conversation' => $conversation])
            ->set('messageBody', '   ')
            ->call('sendMessage')
            ->assertSee('Message content cannot be empty');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_valid_message_is_saved_and_updates_conversation_last_message(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $this->makeUserPremium($userA);
        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        $this->actingAs($userA);

        Livewire::test(Show::class, ['conversation' => $conversation])
            ->set('messageBody', 'Hello brother!')
            ->call('sendMessage');

        $message = Message::first();
        $this->assertNotNull($message);
        $this->assertEquals('Hello brother!', $message->body);
        $this->assertEquals($userA->id, $message->sender_id);

        $this->assertEquals($message->id, $conversation->fresh()->last_message_id);
        $this->assertNotNull($conversation->fresh()->last_message_at);
    }

    public function test_opening_conversation_marks_received_messages_as_read(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $this->makeUserPremium($userA);
        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        // UserA sends message to UserB
        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userA->id,
            'body' => 'Unread message for UserB',
        ]);
        $conversation->update(['last_message_id' => $msg->id, 'last_message_at' => $msg->created_at]);

        $this->assertNull($msg->fresh()->read_at);
        $this->assertEquals(1, $userB->fresh()->unreadMessagesCount());

        // UserB opens the conversation
        $this->actingAs($userB);
        Livewire::test(Show::class, ['conversation' => $conversation]);

        $this->assertNotNull($msg->fresh()->read_at);
        $this->assertEquals(0, $userB->fresh()->unreadMessagesCount());
    }

    public function test_duplicate_conversations_between_same_two_users_are_prevented(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        UserMatch::createMatch($userA->id, $userB->id);

        $conv1 = Conversation::getOrCreateBetween($userA->id, $userB->id);
        $conv2 = Conversation::getOrCreateBetween($userB->id, $userA->id);

        $this->assertEquals($conv1->id, $conv2->id);
        $this->assertDatabaseCount('conversations', 1);
    }

    public function test_messages_persist_in_database(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $this->makeUserPremium($userA);
        UserMatch::createMatch($userA->id, $userB->id);
        $conversation = Conversation::getOrCreateBetween($userA->id, $userB->id);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userA->id,
            'body' => 'Persistent message',
        ]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Persistent message',
        ]);
    }
}
