<?php

namespace App\Livewire\Auth;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function register()
    {
        if (! Setting::get('registration_enabled', true)) {
            session()->flash('error', 'New user registrations are currently paused by system administrator.');
            return;
        }

        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => false,
            'is_active' => true,
        ]);

        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            logger()->error('Verification email delivery failed: ' . $e->getMessage());
            session()->flash('error', 'Account created, but verification email could not be sent. Please check your SMTP credentials in .env.');
        }

        Auth::login($user);

        if (Setting::get('email_verification_required', true) && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.app', ['title' => 'Create Account']);
    }
}
