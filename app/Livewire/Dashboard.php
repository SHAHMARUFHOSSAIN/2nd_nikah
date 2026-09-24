<?php

namespace App\Livewire;

use App\Models\Interest;
use App\Models\MemberProfile;
use App\Models\UserMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $profile = null;
        $completionPercentage = 0;

        $sentInterestsCount = 0;
        $receivedInterestsCount = 0;
        $pendingReceivedCount = 0;
        $connectionsCount = 0;

        if (! $user->is_admin) {
            $profile = MemberProfile::firstOrCreate(['user_id' => $user->id]);
            $completionPercentage = $profile->calculateCompletionPercentage();

            $sentInterestsCount = Interest::where('sender_id', $user->id)->count();
            $receivedInterestsCount = Interest::where('receiver_id', $user->id)->count();
            $pendingReceivedCount = Interest::where('receiver_id', $user->id)->where('status', 'pending')->count();
            $connectionsCount = UserMatch::forUser($user->id)->count();
        }

        return view('livewire.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'completionPercentage' => $completionPercentage,
            'sentInterestsCount' => $sentInterestsCount,
            'receivedInterestsCount' => $receivedInterestsCount,
            'pendingReceivedCount' => $pendingReceivedCount,
            'connectionsCount' => $connectionsCount,
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
