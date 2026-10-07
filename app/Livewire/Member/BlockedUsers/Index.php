<?php

namespace App\Livewire\Member\BlockedUsers;

use App\Models\Block;
use App\Models\Conversation;
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

        if (! Auth::user()->is_active) {
            abort(403, 'Your account is inactive.');
        }
    }

    public function unblock(int $blockedUserId): void
    {
        $authId = Auth::id();
        $unblocked = Block::unblockUser($authId, $blockedUserId);

        if ($unblocked) {
            session()->flash('status', 'Member has been unblocked successfully. You can now communicate with them again.');
        }
    }

    public function render()
    {
        $userId = Auth::id();

        $blockedRecords = Block::with(['blocked.memberProfile', 'blocked.primaryProfilePhoto'])
            ->where('blocker_id', $userId)
            ->latest()
            ->paginate(12);

        $blockedUserIds = $blockedRecords->pluck('blocked_id')->toArray();

        // Check if there is an existing conversation with any of the blocked users
        $conversations = Conversation::forUser($userId)
            ->where(function ($q) use ($blockedUserIds) {
                $q->whereIn('user_one_id', $blockedUserIds)
                  ->orWhereIn('user_two_id', $blockedUserIds);
            })
            ->get()
            ->keyBy(function ($conv) use ($userId) {
                return $conv->user_one_id === $userId ? $conv->user_two_id : $conv->user_one_id;
            });

        return view('livewire.member.blocked-users.index', [
            'blockedRecords' => $blockedRecords,
            'conversations' => $conversations,
        ])->layout('components.layouts.app', ['title' => 'Blocked Members']);
    }
}
