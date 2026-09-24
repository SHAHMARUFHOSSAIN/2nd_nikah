<?php

namespace App\Livewire;

use App\Models\MemberProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $profile = null;
        $completionPercentage = 0;

        if (! $user->is_admin) {
            $profile = MemberProfile::firstOrCreate(['user_id' => $user->id]);
            $completionPercentage = $profile->calculateCompletionPercentage();
        }

        return view('livewire.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'completionPercentage' => $completionPercentage,
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
