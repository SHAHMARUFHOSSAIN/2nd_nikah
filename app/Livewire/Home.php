<?php

namespace App\Livewire;

use App\Livewire\Concerns\HandlesMemberCardActions;
use App\Models\MemberProfile;
use Livewire\Component;

class Home extends Component
{
    use HandlesMemberCardActions;

    public function render()
    {
        // Real discoverable active members query from MySQL
        $recentMembers = MemberProfile::discoverable()
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.home', [
            'recentMembers' => $recentMembers,
        ])->layout('components.layouts.app', ['title' => 'Home']);
    }
}
