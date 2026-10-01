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
        $unreadMessagesCount = 0;

        $activeSubscription = null;
        $isPremium = false;

        $photoCount = 0;

        if (! $user->is_admin) {
            $profile = MemberProfile::firstOrCreate(['user_id' => $user->id]);
            $completionPercentage = $profile->calculateCompletionPercentage();
            $photoCount = $user->profilePhotos()->count();

            $sentInterestsCount = Interest::where('sender_id', $user->id)->count();
            $receivedInterestsCount = Interest::where('receiver_id', $user->id)->count();
            $pendingReceivedCount = Interest::where('receiver_id', $user->id)->where('status', 'pending')->count();
            $connectionsCount = UserMatch::forUser($user->id)->count();
            $unreadMessagesCount = $user->unreadMessagesCount();

            $activeSubscription = $user->activeSubscription;
            $isPremium = $user->isPremium();
        }

        return view('livewire.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'completionPercentage' => $completionPercentage,
            'photoCount' => $photoCount,
            'sentInterestsCount' => $sentInterestsCount,
            'receivedInterestsCount' => $receivedInterestsCount,
            'pendingReceivedCount' => $pendingReceivedCount,
            'connectionsCount' => $connectionsCount,
            'unreadMessagesCount' => $unreadMessagesCount,
            'activeSubscription' => $activeSubscription,
            'isPremium' => $isPremium,
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
