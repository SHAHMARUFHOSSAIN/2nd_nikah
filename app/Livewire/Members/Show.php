<?php

namespace App\Livewire\Members;

use App\Models\Interest;
use App\Models\MemberProfile;
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

    public function mount(MemberProfile $memberProfile): void
    {
        // Enforce strict discoverability security rule
        if (! $memberProfile->is_profile_visible
            || ! $memberProfile->user
            || ! $memberProfile->user->email_verified_at
            || ! $memberProfile->user->is_active
            || $memberProfile->user->is_admin) {
            abort(404);
        }

        $this->memberProfile = $memberProfile;
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

    public function render()
    {
        $existingInterest = null;

        if (Auth::check()) {
            $existingInterest = Interest::where(function ($q) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $this->memberProfile->user_id);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->memberProfile->user_id)->where('receiver_id', Auth::id());
            })->latest()->first();
        }

        return view('livewire.members.show', [
            'profile' => $this->memberProfile,
            'existingInterest' => $existingInterest,
        ])->layout('components.layouts.app', [
            'title' => $this->memberProfile->full_name . ' - Member Profile',
        ]);
    }
}
