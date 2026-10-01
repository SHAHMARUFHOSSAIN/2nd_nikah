<?php

namespace App\Livewire\Member\Messages;

use App\Models\Conversation;
use App\Models\UserMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

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

        if ($user->hasBlockedOrIsBlockedBy($partnerId)) {
            session()->flash('error', 'You cannot communicate with this user due to privacy blocking.');
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

        // Fetch conversations
        $query = Conversation::with(['userOne.memberProfile', 'userTwo.memberProfile', 'lastMessage'])
            ->forUser($user->id);

        $conversations = $query->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        // Filter out conversations where partner is blocked or matches search
        $conversations = $conversations->reject(function ($conv) use ($user) {
            $partner = $conv->getPartnerUser($user->id);
            if (! $partner || $user->hasBlockedOrIsBlockedBy($partner->id)) {
                return true;
            }

            if (! empty(trim($this->search))) {
                $term = mb_strtolower(trim($this->search));
                $partnerName = mb_strtolower($partner->memberProfile?->full_name ?: $partner->name);
                return strpos($partnerName, $term) === false;
            }

            return false;
        });

        // Find all active mutual matches for starting new chat
        $matches = UserMatch::with(['userOne.memberProfile', 'userTwo.memberProfile'])
            ->forUser($user->id)
            ->get()
            ->reject(function ($match) use ($user) {
                $partner = $match->getPartnerUser($user->id);
                return ! $partner || $user->hasBlockedOrIsBlockedBy($partner->id);
            });

        return view('livewire.member.messages.index', [
            'conversations' => $conversations,
            'matches' => $matches,
            'isPremium' => $user->isPremium(),
        ])->layout('components.layouts.app', ['title' => 'Messages & Conversations']);
    }
}
