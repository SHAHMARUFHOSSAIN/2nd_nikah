<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        return view('livewire.dashboard', [
            'user' => $user,
        ])->layout('components.layouts.app', ['title' => 'Member Dashboard']);
    }
}
