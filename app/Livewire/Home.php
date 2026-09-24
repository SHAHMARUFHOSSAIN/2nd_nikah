<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        // Real active members query
        $recentMembers = User::where('is_admin', false)
            ->whereNotNull('email_verified_at')
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.home', [
            'recentMembers' => $recentMembers,
        ])->layout('components.layouts.app', ['title' => 'Home']);
    }
}
