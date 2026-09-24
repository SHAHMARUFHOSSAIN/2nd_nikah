<?php

namespace App\Livewire\Member\Connections;

use App\Models\UserMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $matches = UserMatch::with(['userOne.memberProfile', 'userTwo.memberProfile'])
            ->forUser(Auth::id())
            ->latest('matched_at')
            ->paginate(10);

        return view('livewire.member.connections.index', [
            'matches' => $matches,
        ])->layout('components.layouts.app', ['title' => 'My Connections']);
    }
}
