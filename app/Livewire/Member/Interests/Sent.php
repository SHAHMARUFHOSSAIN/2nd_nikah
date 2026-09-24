<?php

namespace App\Livewire\Member\Interests;

use App\Models\Interest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Sent extends Component
{
    use WithPagination;

    public function cancel(int $interestId): void
    {
        $interest = Interest::where('id', $interestId)
            ->where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $interest->update(['status' => 'cancelled']);

        session()->flash('message', 'Interest request cancelled.');
    }

    public function render()
    {
        $interests = Interest::with(['receiver.memberProfile'])
            ->where('sender_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.member.interests.sent', [
            'interests' => $interests,
        ])->layout('components.layouts.app', ['title' => 'Sent Interests']);
    }
}
