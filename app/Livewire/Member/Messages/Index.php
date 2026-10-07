<?php

namespace App\Livewire\Member\Messages;

use App\Models\Block;
use App\Models\Conversation;
use App\Models\UserMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $filter = 'all'; // 'all', 'active', 'blocked'

    public function setFilter(string $filter): void
    {
        $this->filter = in_array($filter, ['all', 'active', 'blocked']) ? $filter : 'all';
    }

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

    public function startConversation(int $partnerId)
    {
        $authId = Auth::id();
        $user = Auth::user();

        if ($authId === $partnerId) {
            session()->flash('error', 'You cannot message yourself.');
            return;
        }

        if ($user->isBlockedBy($partnerId)) {
            session()->flash('error', 'You cannot communicate with this user.');
            return;
        }

        // Mutual match verification
        $isMatched = UserMatch::where('user_one_id', min($authId, $partnerId))
            ->where('user_two_id', max($authId, $partnerId))
            ->exists();

        if (! $isMatched) {
            session()->flash('error', 'You can only message members with whom you have an accepted mutual match.');
            return;
        }

        $conversation = Conversation::getOrCreateBetween($authId, $partnerId);

        if (! $conversation) {
            session()->flash('error', 'Unable to initiate conversation.');
            return;
        }

        return redirect()->route('member.messages.show', $conversation->id);
    }

    public function render()
    {
        $user = Auth::user();

        // Fetch all conversations
        $rawConversations = Conversation::with(['userOne.memberProfile', 'userTwo.memberProfile', 'lastMessage'])
            ->forUser($user->id)
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        $activeCount = 0;
        $blockedCount = 0;

        foreach ($rawConversations as $conv) {
            $partner = $conv->getPartnerUser($user->id);
            if ($partner && $user->hasBlockedOrIsBlockedBy($partner->id)) {
                $blockedCount++;
            } else {
                $activeCount++;
            }
        }

        // Filter conversations based on search & tab
        $conversations = $rawConversations->filter(function ($conv) use ($user) {
            $partner = $conv->getPartnerUser($user->id);
            if (! $partner) {
                return false;
            }

            $isBlocked = $user->hasBlockedOrIsBlockedBy($partner->id);

            if ($this->filter === 'active' && $isBlocked) {
                return false;
            }

            if ($this->filter === 'blocked' && ! $isBlocked) {
                return false;
            }

            if (! empty(trim($this->search))) {
                $term = mb_strtolower(trim($this->search));
                $partnerName = mb_strtolower($partner->memberProfile?->full_name ?: $partner->name);
                return strpos($partnerName, $term) !== false;
            }

            return true;
        });

        // Find all active mutual matches for starting new chat (excluding blocked)
        $matches = UserMatch::with(['userOne.memberProfile', 'userTwo.memberProfile'])
            ->forUser($user->id)
            ->get()
            ->reject(function ($match) use ($user) {
                $partner = $match->getPartnerUser($user->id);
                return ! $partner || $user->hasBlockedOrIsBlockedBy($partner->id);
            });

        $totalBlockedUsers = Block::where('blocker_id', $user->id)->count();

        return view('livewire.member.messages.index', [
            'conversations' => $conversations,
            'matches' => $matches,
            'isPremium' => $user->isPremium(),
            'activeCount' => $activeCount,
            'blockedCount' => $blockedCount,
            'totalBlockedUsers' => $totalBlockedUsers,
            'rawTotalCount' => $rawConversations->count(),
        ])->layout('components.layouts.app', ['title' => 'Messages & Conversations']);
    }
}
