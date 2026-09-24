<?php

namespace App\Livewire\Members;

use App\Models\MemberProfile;
use Livewire\Component;

class Show extends Component
{
    public MemberProfile $memberProfile;

    public function mount(MemberProfile $memberProfile): void
    {
        // Enforce strict discoverability security rule
        if (! $memberProfile->is_profile_visible
            || ! $memberProfile->user
            || ! $memberProfile->user->email_verified_at
            || ! $memberProfile->user->is_active
            || $memberProfile->user->is_admin) {
            abort(404);
        }

        $this->memberProfile = $memberProfile;
    }

    public function render()
    {
        return view('livewire.members.show', [
            'profile' => $this->memberProfile,
        ])->layout('components.layouts.app', [
            'title' => $this->memberProfile->full_name . ' - Member Profile',
        ]);
    }
}
