<?php

namespace App\Livewire\Member\Shortlists;

use App\Livewire\Concerns\HandlesMemberCardActions;
use App\Models\Shortlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use HandlesMemberCardActions;

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

    public function removeShortlist(int $targetUserId): void
    {
        $this->toggleShortlist($targetUserId);
    }

    public function render()
    {
        $user = Auth::user();

        // Filter out blocked users
        $blockedIds = $user->getBlockedUserIds();

        $shortlists = Shortlist::with(['shortlistedUser.memberProfile'])
            ->where('user_id', $user->id)
            ->whereNotIn('shortlisted_user_id', $blockedIds)
            ->latest()
            ->paginate(12);

        return view('livewire.member.shortlists.index', [
            'shortlists' => $shortlists,
        ])->layout('components.layouts.app', ['title' => 'My Shortlist']);
    }
}
