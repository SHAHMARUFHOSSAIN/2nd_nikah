<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function resendNotification()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        try {
            Auth::user()->sendEmailVerificationNotification();
            session()->flash('status', 'verification-link-sent');
        } catch (\Throwable $e) {
            logger()->error('Verification email delivery failed: ' . $e->getMessage());
            session()->flash('error', 'Verification email sending failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email')
            ->layout('components.layouts.app', ['title' => 'Verify Your Email']);
    }
}
