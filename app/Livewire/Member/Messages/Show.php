<?php

namespace App\Livewire\Member\Messages;

use App\Models\Block;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Report;
use App\Models\UserMatch;
use App\Models\WhatsappShareRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Conversation $conversation;
    public string $messageBody = '';
    public $attachment = null;
    public ?int $replyToMessageId = null;
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    // Block state flags
    public bool $isBlockedByMe = false;
    public bool $isBlockedByPartner = false;

    // Block & Report Modal states
    public bool $showBlockModal = false;
    public bool $showReportModal = false;
    public string $reportReason = 'Inappropriate behavior';
    public string $reportDetails = '';

    public function mount(Conversation $conversation): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $user = Auth::user();

        if (! $user->hasVerifiedEmail()) {
            $this->redirect(route('verification.notice'));
            return;
        }

        if (! $user->is_active) {
            abort(403, 'Your account is inactive.');
        }

        $userId = $user->id;

        // 1. Participant Check
        if (! $conversation->isParticipant($userId)) {
            abort(403, 'Unauthorized access to conversation.');
        }

        $partner = $conversation->getPartnerUser($userId);
        if (! $partner) {
            abort(404, 'Conversation partner not found.');
        }

        // 2. Block Check - record states without 403 so blocked user can be viewed and unblocked
        $this->isBlockedByMe = $user->hasBlocked($partner->id);
        $this->isBlockedByPartner = $user->isBlockedBy($partner->id);

        // 3. Mutual Match Check
        $isMatched = UserMatch::where('user_one_id', min($conversation->user_one_id, $conversation->user_two_id))
            ->where('user_two_id', max($conversation->user_one_id, $conversation->user_two_id))
            ->exists();

        if (! $isMatched) {
            abort(403, 'Communication is only permitted between mutual accepted matches.');
        }

        $this->conversation = $conversation;

        // Mark incoming unread messages as read
        $this->conversation->markAsReadFor($userId);
    }

    public function setReplyTo(int $messageId): void
    {
        $message = Message::where('conversation_id', $this->conversation->id)
            ->where('id', $messageId)
            ->first();

        if ($message && ! $message->isDeleted()) {
            $this->replyToMessageId = $message->id;
        }
    }

    public function clearReplyTo(): void
    {
        $this->replyToMessageId = null;
    }

    public function removeAttachment(): void
    {
        $this->attachment = null;
    }

    public function sendMessage(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->hasVerifiedEmail()) {
            $this->errorMessage = 'You must be logged in and email-verified to send messages.';
            return;
        }

        // Re-verify eligibility
        if (! $this->conversation->isParticipant($user->id)) {
            $this->errorMessage = 'Unauthorized action.';
            return;
        }

        $partner = $this->conversation->getPartnerUser($user->id);
        if ($this->isBlockedByMe) {
            $this->errorMessage = 'You have blocked this member. Please unblock them to send messages.';
            return;
        }

        if ($this->isBlockedByPartner) {
            $this->errorMessage = 'Cannot send message because communication has been restricted.';
            return;
        }

        if (! $user->isPremium()) {
            $this->errorMessage = 'An active Premium membership subscription is required to send messages.';
            return;
        }

        $body = trim($this->messageBody);

        // Validation for attachment if present
        if ($this->attachment) {
            $this->validate([
                'attachment' => 'image|max:5120|mimes:jpg,jpeg,png,webp',
            ], [
                'attachment.image' => 'The attachment must be a valid image file.',
                'attachment.max' => 'The attachment size must not exceed 5 MB.',
                'attachment.mimes' => 'Allowed image formats are JPG, JPEG, PNG, and WEBP.',
            ]);
        }

        if (empty($body) && ! $this->attachment) {
            $this->errorMessage = 'Message content cannot be empty.';
            return;
        }

        if (! empty($body) && mb_strlen($body) > 2000) {
            $this->errorMessage = 'Message exceeds maximum character length of 2000.';
            return;
        }

        $this->errorMessage = null;
        $attachmentPath = null;
        $attachmentMime = null;
        $attachmentOriginalName = null;
        $type = 'text';

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('chat_attachments', 'local');
            $attachmentMime = $this->attachment->getClientMimeType() ?: 'image/jpeg';
            $attachmentOriginalName = $this->attachment->getClientOriginalName();
            $type = 'image';
        }

        if ($this->replyToMessageId) {
            $parentMessage = Message::where('conversation_id', $this->conversation->id)
                ->where('id', $this->replyToMessageId)
                ->first();
            if ($parentMessage) {
                $type = $this->attachment ? 'image' : 'reply';
            } else {
                $this->replyToMessageId = null;
            }
        }

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $user->id,
            'type' => $type,
            'body' => $body ?: ($type === 'image' ? 'Sent an image' : ''),
            'attachment_path' => $attachmentPath,
            'attachment_mime' => $attachmentMime,
            'attachment_original_name' => $attachmentOriginalName,
            'reply_to_message_id' => $this->replyToMessageId,
        ]);

        $this->conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => $message->created_at,
        ]);

        $this->messageBody = '';
        $this->attachment = null;
        $this->replyToMessageId = null;

        $this->dispatch('message-sent');
    }

    public function pollMessages(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->conversation->markAsReadFor(Auth::id());
        $this->dispatch('messages-polled');
    }

    public function deleteMessage(int $messageId): void
    {
        $userId = Auth::id();

        $message = Message::where('conversation_id', $this->conversation->id)
            ->where('id', $messageId)
            ->first();

        if (! $message || $message->sender_id !== $userId) {
            $this->errorMessage = 'Unable to delete message.';
            return;
        }

        if ($message->isDeleted()) {
            return;
        }

        // Delete physical attachment if existing
        if (! empty($message->attachment_path) && Storage::disk('local')->exists($message->attachment_path)) {
            Storage::disk('local')->delete($message->attachment_path);
        }

        $message->update([
            'type' => 'deleted',
            'body' => 'This message was deleted.',
            'attachment_path' => null,
            'attachment_mime' => null,
            'attachment_original_name' => null,
            'deleted_at' => now(),
        ]);

        $this->successMessage = 'Message deleted.';
    }

    public function requestWhatsApp(): void
    {
        $user = Auth::user();

        if (! $user->isPremium()) {
            $this->errorMessage = 'An active Premium membership subscription is required to request WhatsApp/Phone details.';
            return;
        }

        $partner = $this->conversation->getPartnerUser($user->id);
        if ($user->hasBlockedOrIsBlockedBy($partner->id)) {
            $this->errorMessage = 'Cannot request contact details due to block restriction.';
            return;
        }

        // Check if existing active request
        $existing = WhatsappShareRequest::where('conversation_id', $this->conversation->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existing) {
            if ($existing->isPending()) {
                $this->errorMessage = 'A WhatsApp contact request is already pending.';
            } else if ($existing->isAccepted()) {
                $this->errorMessage = 'WhatsApp contact details have already been shared.';
            }
            return;
        }

        $req = WhatsappShareRequest::create([
            'requester_id' => $user->id,
            'receiver_id' => $partner->id,
            'conversation_id' => $this->conversation->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $msg = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $user->id,
            'type' => 'whatsapp_request',
            'body' => 'Requested WhatsApp / Phone contact details.',
        ]);

        $this->conversation->update([
            'last_message_id' => $msg->id,
            'last_message_at' => $msg->created_at,
        ]);

        $this->dispatch('message-sent');
        $this->successMessage = 'WhatsApp contact request sent to ' . ($partner->memberProfile?->full_name ?: $partner->name) . '.';
    }

    public function acceptWhatsApp(int $requestId): void
    {
        $userId = Auth::id();

        $req = WhatsappShareRequest::where('id', $requestId)
            ->where('conversation_id', $this->conversation->id)
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (! $req) {
            $this->errorMessage = 'Invalid or expired WhatsApp request.';
            return;
        }

        $req->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        $msg = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $userId,
            'type' => 'whatsapp_request',
            'body' => 'Accepted WhatsApp / Phone contact exchange.',
        ]);

        $this->conversation->update([
            'last_message_id' => $msg->id,
            'last_message_at' => $msg->created_at,
        ]);

        $this->dispatch('message-sent');
        $this->successMessage = 'WhatsApp contact exchange accepted!';
    }

    public function rejectWhatsApp(int $requestId): void
    {
        $userId = Auth::id();

        $req = WhatsappShareRequest::where('id', $requestId)
            ->where('conversation_id', $this->conversation->id)
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (! $req) {
            $this->errorMessage = 'Invalid request.';
            return;
        }

        $req->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        $this->successMessage = 'WhatsApp contact request declined.';
    }

    public function cancelWhatsApp(int $requestId): void
    {
        $userId = Auth::id();

        $req = WhatsappShareRequest::where('id', $requestId)
            ->where('conversation_id', $this->conversation->id)
            ->where('requester_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (! $req) {
            $this->errorMessage = 'Invalid request.';
            return;
        }

        $req->update([
            'status' => 'cancelled',
            'responded_at' => now(),
        ]);

        $this->successMessage = 'WhatsApp contact request cancelled.';
    }

    public function confirmBlock(): void
    {
        $this->showBlockModal = true;
    }

    public function cancelBlock(): void
    {
        $this->showBlockModal = false;
    }

    public function blockPartner(): void
    {
        $user = Auth::user();
        $partner = $this->conversation->getPartnerUser($user->id);

        if (! $partner) {
            return;
        }

        Block::firstOrCreate([
            'blocker_id' => $user->id,
            'blocked_id' => $partner->id,
        ]);

        $this->isBlockedByMe = true;
        $this->showBlockModal = false;
        $this->successMessage = ($partner->memberProfile?->full_name ?: $partner->name) . ' has been blocked. You can unblock them at any time.';
    }

    public function unblockPartner(): void
    {
        $user = Auth::user();
        $partner = $this->conversation->getPartnerUser($user->id);

        if (! $partner) {
            return;
        }

        Block::unblockUser($user->id, $partner->id);

        $this->isBlockedByMe = false;
        $this->successMessage = ($partner->memberProfile?->full_name ?: $partner->name) . ' has been unblocked successfully.';
    }

    public function confirmReport(): void
    {
        $this->showReportModal = true;
    }

    public function cancelReport(): void
    {
        $this->showReportModal = false;
    }

    public function reportPartner(): void
    {
        $user = Auth::user();
        $partner = $this->conversation->getPartnerUser($user->id);

        if (! $partner) {
            return;
        }

        Report::create([
            'reporter_id' => $user->id,
            'reported_user_id' => $partner->id,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails,
            'status' => 'pending',
        ]);

        $this->showReportModal = false;
        $this->reportDetails = '';
        $this->successMessage = 'Your report has been submitted to site administration.';
    }

    public function render()
    {
        $userId = Auth::id();

        // Mark incoming unread messages as read
        $this->conversation->markAsReadFor($userId);

        $messages = Message::with(['sender', 'replyTo', 'replyTo.sender'])
            ->where('conversation_id', $this->conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $partner = $this->conversation->getPartnerUser($userId);
        $partnerProfile = $partner?->memberProfile;

        $whatsappRequest = WhatsappShareRequest::where('conversation_id', $this->conversation->id)
            ->latest('id')
            ->first();

        // Find the first unread received message for rendering the unread divider
        $firstUnreadReceivedMessageId = null;
        foreach ($messages as $msg) {
            if ($msg->sender_id !== $userId && $msg->read_at === null) {
                $firstUnreadReceivedMessageId = $msg->id;
                break;
            }
        }

        // Fetch all conversations for desktop sidebar
        $conversations = Conversation::with(['userOne.memberProfile', 'userTwo.memberProfile', 'lastMessage'])
            ->forUser($userId)
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->reject(function ($conv) use ($userId) {
                $p = $conv->getPartnerUser($userId);
                return ! $p || Auth::user()->hasBlockedOrIsBlockedBy($p->id);
            });

        return view('livewire.member.messages.show', [
            'messages' => $messages,
            'partner' => $partner,
            'partnerProfile' => $partnerProfile,
            'isPremium' => Auth::user()->isPremium(),
            'whatsappRequest' => $whatsappRequest,
            'firstUnreadId' => $firstUnreadReceivedMessageId,
            'conversations' => $conversations,
        ])->layout('components.layouts.app', ['title' => 'Chat with ' . ($partnerProfile?->full_name ?: $partner?->name)]);
    }
}
