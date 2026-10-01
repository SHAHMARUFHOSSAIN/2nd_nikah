<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\Conversation;
use App\Models\MemberProfile;
use App\Models\Message;
use App\Models\MembershipPlan;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserMatch;
use App\Models\WhatsappShareRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdvancedMessagingTest extends TestCase
{
    use RefreshDatabase;

    protected User $userOne;
    protected User $userTwo;
    protected Conversation $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user 1
        $this->userOne = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        MemberProfile::create([
            'user_id' => $this->userOne->id,
            'full_name' => 'Member One',
            'phone' => '+8801700000001',
            'gender' => 'male',
            'date_of_birth' => '1995-01-01',
            'marital_status' => 'never_married',
            'religion' => 'Islam',
            'country' => 'Bangladesh',
            'city' => 'Dhaka',
        ]);

        // Create user 2
        $this->userTwo = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        MemberProfile::create([
            'user_id' => $this->userTwo->id,
            'full_name' => 'Member Two',
            'phone' => '+8801700000002',
            'gender' => 'female',
            'date_of_birth' => '1997-01-01',
            'marital_status' => 'never_married',
            'religion' => 'Islam',
            'country' => 'Bangladesh',
            'city' => 'Dhaka',
        ]);

        // Create mutual match
        UserMatch::create([
            'user_one_id' => min($this->userOne->id, $this->userTwo->id),
            'user_two_id' => max($this->userOne->id, $this->userTwo->id),
            'matched_at' => now(),
        ]);

        // Create conversation
        $this->conversation = Conversation::create([
            'user_one_id' => min($this->userOne->id, $this->userTwo->id),
            'user_two_id' => max($this->userOne->id, $this->userTwo->id),
        ]);
    }

    protected function makeUserPremium(User $user): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Premium Plan',
            'slug' => 'premium-plan-' . uniqid(),
            'amount' => 1000,
            'currency' => 'BDT',
            'country_scope' => 'BD',
            'billing_interval' => 'monthly',
            'duration_days' => 30,
            'is_active' => true,
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'amount_paid' => 1000,
            'currency' => 'BDT',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'status' => 'active',
        ]);
    }

    public function test_premium_user_can_send_text_message(): void
    {
        $this->makeUserPremium($this->userOne);

        Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->set('messageBody', 'Hello Member Two!')
            ->call('sendMessage')
            ->assertHasNoErrors()
            ->assertSet('errorMessage', null);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->userOne->id,
            'body' => 'Hello Member Two!',
            'type' => 'text',
        ]);
    }

    public function test_non_premium_user_cannot_send_message(): void
    {
        Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->set('messageBody', 'Hello Member Two!')
            ->call('sendMessage')
            ->assertSet('errorMessage', 'An active Premium membership subscription is required to send messages.');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_unauthorized_user_cannot_access_conversation(): void
    {
        $outsider = User::factory()->create(['email_verified_at' => now(), 'is_active' => true]);

        $this->actingAs($outsider)
            ->get(route('member.messages.show', $this->conversation->id))
            ->assertStatus(403);
    }

    public function test_blocked_user_cannot_access_conversation(): void
    {
        Block::create([
            'blocker_id' => $this->userOne->id,
            'blocked_id' => $this->userTwo->id,
        ]);

        $this->actingAs($this->userOne)
            ->get(route('member.messages.show', $this->conversation->id))
            ->assertStatus(403);
    }

    public function test_private_image_upload_and_secure_streaming(): void
    {
        Storage::fake('local');
        $this->makeUserPremium($this->userOne);

        $file = UploadedFile::fake()->image('chat_photo.jpg', 600, 600);

        Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->set('attachment', $file)
            ->set('messageBody', 'Check out my picture')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $message = Message::firstWhere('conversation_id', $this->conversation->id);
        $this->assertNotNull($message);
        $this->assertEquals('image', $message->type);
        $this->assertNotNull($message->attachment_path);

        // Authorized participant (userOne) can access private attachment
        $this->actingAs($this->userOne)
            ->get(route('member.messages.attachment', $message->id))
            ->assertStatus(200);

        // Authorized participant (userTwo) can also access private attachment
        $this->actingAs($this->userTwo)
            ->get(route('member.messages.attachment', $message->id))
            ->assertStatus(200);

        // Outsider user receives 403
        $outsider = User::factory()->create(['email_verified_at' => now(), 'is_active' => true]);
        $this->actingAs($outsider)
            ->get(route('member.messages.attachment', $message->id))
            ->assertStatus(403);
    }

    public function test_sender_can_delete_message(): void
    {
        $this->makeUserPremium($this->userOne);

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->userOne->id,
            'body' => 'Secret message',
            'type' => 'text',
        ]);

        Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->call('deleteMessage', $message->id);

        $message->refresh();
        $this->assertEquals('deleted', $message->type);
        $this->assertEquals('This message was deleted.', $message->body);
        $this->assertNotNull($message->deleted_at);
    }

    public function test_whatsapp_request_lifecycle(): void
    {
        $this->makeUserPremium($this->userOne);

        // User 1 sends WhatsApp request to User 2
        $test = Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->call('requestWhatsApp');

        $this->assertStringContainsString('WhatsApp contact request sent to', $test->get('successMessage'));

        $request = WhatsappShareRequest::firstWhere('conversation_id', $this->conversation->id);
        $this->assertNotNull($request);
        $this->assertEquals('pending', $request->status);
        $this->assertEquals($this->userOne->id, $request->requester_id);
        $this->assertEquals($this->userTwo->id, $request->receiver_id);

        // User 2 accepts request
        Livewire::actingAs($this->userTwo)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->call('acceptWhatsApp', $request->id)
            ->assertSet('successMessage', 'WhatsApp contact exchange accepted!');

        $request->refresh();
        $this->assertEquals('accepted', $request->status);
        $this->assertNotNull($request->responded_at);
    }

    public function test_whatsapp_request_rejection(): void
    {
        $this->makeUserPremium($this->userOne);

        $request = WhatsappShareRequest::create([
            'requester_id' => $this->userOne->id,
            'receiver_id' => $this->userTwo->id,
            'conversation_id' => $this->conversation->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        Livewire::actingAs($this->userTwo)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->call('rejectWhatsApp', $request->id)
            ->assertSet('successMessage', 'WhatsApp contact request declined.');

        $request->refresh();
        $this->assertEquals('rejected', $request->status);
    }

    public function test_whatsapp_request_cancellation(): void
    {
        $this->makeUserPremium($this->userOne);

        $request = WhatsappShareRequest::create([
            'requester_id' => $this->userOne->id,
            'receiver_id' => $this->userTwo->id,
            'conversation_id' => $this->conversation->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation])
            ->call('cancelWhatsApp', $request->id)
            ->assertSet('successMessage', 'WhatsApp contact request cancelled.');

        $request->refresh();
        $this->assertEquals('cancelled', $request->status);
    }

    public function test_opening_conversation_marks_received_messages_as_read(): void
    {
        $msg = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->userTwo->id,
            'body' => 'Unread hello',
            'type' => 'text',
            'read_at' => null,
        ]);

        Livewire::actingAs($this->userOne)
            ->test(\App\Livewire\Member\Messages\Show::class, ['conversation' => $this->conversation]);

        $msg->refresh();
        $this->assertNotNull($msg->read_at);
    }
}
