<?php

namespace App\Livewire\Member\Interests;

use App\Models\Interest;
use App\Models\UserMatch;
use App\Notifications\InterestAcceptedNotification;
use App\Notifications\InterestRejectedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Received extends Component
{
    use WithPagination;

    public function accept(int $interestId): void
    {
        $interest = Interest::where('id', $interestId)
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        DB::transaction(function () use ($interest) {
            $interest->update(['status' => 'accepted']);
            UserMatch::createMatch($interest->sender_id, $interest->receiver_id);

            if ($interest->sender) {
                $interest->sender->notify(new InterestAcceptedNotification($interest));
            }
        });

        session()->flash('message', 'Interest request accepted! You are now connected.');
    }

    public function reject(int $interestId): void
    {
        $interest = Interest::where('id', $interestId)
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $interest->update(['status' => 'rejected']);

        if ($interest->sender) {
            $interest->sender->notify(new InterestRejectedNotification($interest));
        }

        session()->flash('message', 'Interest request declined.');
    }

    public function render()
    {
        $interests = Interest::with(['sender.memberProfile'])
            ->where('receiver_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.member.interests.received', [
            'interests' => $interests,
        ])->layout('components.layouts.app', ['title' => 'Received Interests']);
    }
}
