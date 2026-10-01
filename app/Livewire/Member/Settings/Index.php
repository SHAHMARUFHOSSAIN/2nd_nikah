<?php

namespace App\Livewire\Member\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Index extends Component
{
    public bool $isProfileVisible = true;
    public bool $isActive = true;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->memberProfile) {
            $this->isProfileVisible = (bool) $user->memberProfile->is_profile_visible;
        }
        if ($user) {
            $this->isActive = (bool) $user->is_active;
        }
    }

    public function toggleProfileVisibility(): void
    {
        $user = Auth::user();
        if ($user && $user->memberProfile) {
            $user->memberProfile->update([
                'is_profile_visible' => ! $this->isProfileVisible,
            ]);
            $this->isProfileVisible = ! $this->isProfileVisible;
            session()->flash('message', 'Profile visibility updated successfully.');
        }
    }

    public function toggleAccountActive(): void
    {
        $user = Auth::user();
        if ($user) {
            $newStatus = ! $this->isActive;
            $user->update([
                'is_active' => $newStatus,
            ]);
            $this->isActive = $newStatus;

            if (! $newStatus) {
                session()->flash('message', 'Your account has been deactivated. You can reactivate it anytime by changing this setting.');
            } else {
                session()->flash('message', 'Your account has been reactivated successfully.');
            }
        }
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The provided password does not match your current password.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password_message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.member.settings.index')
            ->layout('layouts.app', ['title' => 'Account Settings']);
    }
}
