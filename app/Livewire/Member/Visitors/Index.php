<?php

namespace App\Livewire\Member\Visitors;

use App\Models\ProfileVisit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function mount(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        if (! Auth::user()->hasVerifiedEmail()) {
            $this->redirect(route('verification.notice'));
            return;
        }
    }

    public function render()
    {
        $user = Auth::user();
        $blockedIds = $user->getBlockedUserIds();

        $visitors = ProfileVisit::with(['visitor.memberProfile'])
            ->where('visited_user_id', $user->id)
            ->whereNotIn('visitor_id', $blockedIds)
            ->orderByDesc('last_visited_at')
            ->paginate(12);

        return view('livewire.member.visitors.index', [
            'visitors' => $visitors,
        ])->layout('components.layouts.app', ['title' => 'Profile Visitors']);
    }
}
