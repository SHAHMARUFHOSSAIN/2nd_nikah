<?php

namespace App\Livewire\Concerns;

use App\Models\Interest;
use App\Models\Shortlist;
use App\Models\User;
use App\Notifications\InterestReceivedNotification;
use Illuminate\Support\Facades\Auth;

trait HandlesMemberCardActions
{
    /**
     * Send interest proposal to target member from card.
     */
    public function sendInterest(int $targetUserId): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $user = Auth::user();

        // Sender eligibility
        if (! $user->hasVerifiedEmail() || ! $user->is_active || $user->is_admin || ! $user->memberProfile) {
            session()->flash('error', 'You are not eligible to send interest requests.');
            return;
        }

        // Prevent self-interest
        if ($user->id === $targetUserId) {
            session()->flash('error', 'You cannot send an interest to yourself.');
            return;
        }

        // Target user eligibility
        $targetUser = User::find($targetUserId);
        $targetProfile = $targetUser?->memberProfile;

        if (! $targetUser || ! $targetUser->email_verified_at || ! $targetUser->is_active || $targetUser->is_admin || ! $targetProfile?->is_profile_visible) {
            session()->flash('error', 'This member is not available to receive interest.');
            return;
        }

        // Block check
        if ($user->hasBlockedOrIsBlockedBy($targetUserId)) {
            session()->flash('error', 'Action not allowed.');
            return;
        }

        // Prevent duplicate interest
        $existing = Interest::getActiveRelationship($user->id, $targetUserId);

        if ($existing) {
            if ($existing->status === 'accepted') {
                session()->flash('info', 'You are already connected with this member.');
            } elseif ($existing->sender_id === $user->id) {
                session()->flash('info', 'You have already sent an interest proposal to this member.');
            } else {
                session()->flash('info', 'This member has already sent you an interest proposal.');
            }
            return;
        }

        // Create Interest record
        $interest = Interest::create([
            'sender_id' => $user->id,
            'receiver_id' => $targetUserId,
            'status' => 'pending',
        ]);

        // Send Database Notification
        $targetUser->notify(new InterestReceivedNotification($interest));

        session()->flash('message', 'Interest request sent successfully!');
    }

    /**
     * Toggle shortlist state for target member from card.
     */
    public function toggleShortlist(int $targetUserId): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $userId = Auth::id();

        if ($userId === $targetUserId) {
            session()->flash('error', 'You cannot shortlist yourself.');
            return;
        }

        if (Auth::user()->hasShortlisted($targetUserId)) {
            Shortlist::where('user_id', $userId)->where('shortlisted_user_id', $targetUserId)->delete();
            session()->flash('message', 'Profile removed from your shortlist.');
        } else {
            Shortlist::create(['user_id' => $userId, 'shortlisted_user_id' => $targetUserId]);
            session()->flash('message', 'Profile added to your shortlist!');
        }
    }
}
