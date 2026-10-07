<?php

namespace App\Livewire\Members;

use App\Models\Interest;
use App\Models\MemberProfile;
use App\Models\ProfileVisit;
use App\Models\UserMatch;
use App\Notifications\InterestAcceptedNotification;
use App\Notifications\InterestReceivedNotification;
use App\Notifications\InterestRejectedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public MemberProfile $memberProfile;

    public bool $isShortlisted = false;
    public bool $isBlocked = false;
    public bool $showReportModal = false;
    public string $reportReason = 'Inappropriate Content';
    public string $reportDescription = '';

    public function mount(MemberProfile $memberProfile): void
    {
        $isOwner = Auth::check() && Auth::id() === $memberProfile->user_id;

        // Enforce strict discoverability security rule
        if ((! $isOwner && ! $memberProfile->is_profile_visible)
            || ! $memberProfile->user
            || ! $memberProfile->user->email_verified_at
            || ! $memberProfile->user->is_active
            || $memberProfile->user->is_admin) {
            abort(404);
        }

        // Block check
        if (Auth::check()) {
            if (Auth::user()->isBlockedBy($memberProfile->user_id)) {
                abort(404);
            }

            // Record profile visit if not blocked
            if (! Auth::user()->hasBlocked($memberProfile->user_id)) {
                ProfileVisit::recordVisit(Auth::id(), $memberProfile->user_id);
            }
            $this->isShortlisted = Auth::user()->hasShortlisted($memberProfile->user_id);
            $this->isBlocked = Auth::user()->hasBlocked($memberProfile->user_id);
        }

        $this->memberProfile = $memberProfile;
    }

    public function toggleShortlist(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $userId = Auth::id();
        $targetId = $this->memberProfile->user_id;

        if ($userId === $targetId) {
            session()->flash('error', 'You cannot shortlist yourself.');
            return;
        }

        if (Auth::user()->hasShortlisted($targetId)) {
            \App\Models\Shortlist::where('user_id', $userId)->where('shortlisted_user_id', $targetId)->delete();
            $this->isShortlisted = false;
            session()->flash('message', 'Profile removed from your shortlist.');
        } else {
            \App\Models\Shortlist::create(['user_id' => $userId, 'shortlisted_user_id' => $targetId]);
            $this->isShortlisted = true;
            session()->flash('message', 'Profile added to your shortlist!');
        }
    }

    public function toggleBlock(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $userId = Auth::id();
        $targetId = $this->memberProfile->user_id;

        if ($userId === $targetId) {
            session()->flash('error', 'You cannot block yourself.');
            return;
        }

        if (Auth::user()->hasBlocked($targetId)) {
            \App\Models\Block::unblockUser($userId, $targetId);
            $this->isBlocked = false;
            session()->flash('message', 'Member unblocked.');
        } else {
            \App\Models\Block::blockUser($userId, $targetId);
            $this->isBlocked = true;
            session()->flash('message', 'Member blocked successfully.');
            $this->redirect(route('members.index'));
            return;
        }
    }

    public function openReportModal(): void
    {
        $this->showReportModal = true;
    }

    public function closeReportModal(): void
    {
        $this->showReportModal = false;
    }

    public function submitReport(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $userId = Auth::id();
        $targetId = $this->memberProfile->user_id;

        if ($userId === $targetId) {
            session()->flash('error', 'You cannot report yourself.');
            return;
        }

        $existingPending = \App\Models\Report::where('reporter_id', $userId)
            ->where('reported_user_id', $targetId)
            ->whereIn('status', ['pending', 'reviewing'])
            ->exists();

        if ($existingPending) {
            session()->flash('error', 'You already have an active report under review for this member.');
            $this->showReportModal = false;
            return;
        }

        \App\Models\Report::create([
            'reporter_id' => $userId,
            'reported_user_id' => $targetId,
            'reason' => $this->reportReason,
            'description' => trim($this->reportDescription),
            'status' => 'pending',
        ]);

        $this->showReportModal = false;
        $this->reportDescription = '';
        session()->flash('message', 'Report submitted successfully. Our team will review your report.');
    }

    public function sendInterest(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $user = Auth::user();

        // Server-side eligibility checks for Sender
        if (! $user->hasVerifiedEmail() || ! $user->is_active || $user->is_admin || ! $user->memberProfile) {
            session()->flash('error', 'You are not eligible to send interest requests.');
            return;
        }

        // Server-side eligibility checks for Receiver
        $receiverUser = $this->memberProfile->user;
        if (! $receiverUser || ! $receiverUser->email_verified_at || ! $receiverUser->is_active || $receiverUser->is_admin || ! $this->memberProfile->is_profile_visible) {
            session()->flash('error', 'This member is not available to receive interest.');
            return;
        }

        // Prevent self-interest
        if ($user->id === $receiverUser->id) {
            session()->flash('error', 'You cannot send an interest to yourself.');
            return;
        }

        // Prevent duplicate interest
        $existing = Interest::getActiveRelationship($user->id, $receiverUser->id);

        if ($existing) {
            if ($existing->status === 'accepted') {
                session()->flash('info', 'You are already connected with this member.');
            } elseif ($existing->sender_id === $user->id) {
                session()->flash('info', 'You have already sent an interest proposal to this member.');
            } else {
                session()->flash('info', 'This member has already sent you an interest proposal. Please accept their request.');
            }
            return;
        }

        // Create real Interest record
        $interest = Interest::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverUser->id,
            'status' => 'pending',
        ]);

        // Send Database Notification
        $receiverUser->notify(new InterestReceivedNotification($interest));

        session()->flash('message', 'Interest request sent successfully!');
    }

    public function acceptInterest(): void
    {
        if (! Auth::check()) {
            return;
        }

        $interest = Interest::where('sender_id', $this->memberProfile->user_id)
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (! $interest) {
            return;
        }

        DB::transaction(function () use ($interest) {
            $interest->update(['status' => 'accepted']);
            UserMatch::createMatch($interest->sender_id, $interest->receiver_id);

            if ($interest->sender) {
                $interest->sender->notify(new InterestAcceptedNotification($interest));
            }
        });

        session()->flash('message', 'Interest request accepted! You are now connected.');
    }

    public function rejectInterest(): void
    {
        if (! Auth::check()) {
            return;
        }

        $interest = Interest::where('sender_id', $this->memberProfile->user_id)
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (! $interest) {
            return;
        }

        $interest->update(['status' => 'rejected']);

        if ($interest->sender) {
            $interest->sender->notify(new InterestRejectedNotification($interest));
        }

        session()->flash('message', 'Interest request declined.');
    }

    public function cancelInterest(): void
    {
        if (! Auth::check()) {
            return;
        }

        $interest = Interest::where('sender_id', Auth::id())
            ->where('receiver_id', $this->memberProfile->user_id)
            ->where('status', 'pending')
            ->first();

        if (! $interest) {
            return;
        }

        $interest->update(['status' => 'cancelled']);

        session()->flash('message', 'Interest proposal cancelled.');
    }

    public function openMessage()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'));
        }

        $user = Auth::user();
        $targetUserId = $this->memberProfile->user_id;

        if ($user->id === $targetUserId) {
            return;
        }

        if (! UserMatch::hasMutualMatch($user->id, $targetUserId)) {
            session()->flash('error', 'You can only message members with whom you have a mutual match.');
            return;
        }

        if (! $user->isPremium()) {
            session()->flash('info', 'Messaging requires an active Premium membership. Upgrade today to unlock direct conversations!');
            return $this->redirect(route('membership.index'));
        }

        $conversation = \App\Models\Conversation::getOrCreateBetween($user->id, $targetUserId);
        if ($conversation) {
            return $this->redirect(route('member.messages.show', $conversation->id));
        }

        session()->flash('error', 'Unable to open conversation.');
    }

    public function render()
    {
        $existingInterest = null;
        $isMutualMatch = false;
        $messageUrl = null;

        if (Auth::check()) {
            $user = Auth::user();
            $targetUserId = $this->memberProfile->user_id;

            $existingInterest = Interest::where(function ($q) use ($targetUserId) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $targetUserId);
            })->orWhere(function ($q) use ($targetUserId) {
                $q->where('sender_id', $targetUserId)->where('receiver_id', Auth::id());
            })->latest()->first();

            $isMutualMatch = UserMatch::hasMutualMatch($user->id, $targetUserId);

            if ($isMutualMatch) {
                if ($user->isPremium()) {
                    $conversation = \App\Models\Conversation::getOrCreateBetween($user->id, $targetUserId);
                    if ($conversation) {
                        $messageUrl = route('member.messages.show', $conversation->id);
                    }
                } else {
                    $messageUrl = route('membership.index');
                }
            }
        }

        $profilePhotos = $this->memberProfile->user?->profilePhotos()
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->get() ?? collect();

        return view('livewire.members.show', [
            'profile' => $this->memberProfile,
            'existingInterest' => $existingInterest,
            'isMutualMatch' => $isMutualMatch,
            'messageUrl' => $messageUrl,
            'profilePhotos' => $profilePhotos,
        ])->layout('components.layouts.app', [
            'title' => $this->memberProfile->full_name . ' - Member Profile',
        ]);
    }
}
