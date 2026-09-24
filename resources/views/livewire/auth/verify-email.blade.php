<div class="auth-container">
    <div class="auth-card" style="text-align: center;">
        <div class="empty-state-icon" style="margin-bottom: 1rem;">
            ✉️
        </div>
        
        <h1 style="font-size: 1.5rem; margin-bottom: 0.75rem;">Verify Your Email Address</h1>
        
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
            Thanks for registering with <strong>{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}</strong>! Before getting started, please check your email and click the verification link we sent to:
            <br>
            <strong style="color: var(--text-main);">{{ auth()->user()->email }}</strong>
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert-success" style="text-align: left; margin-bottom: 1rem;">
                A new verification link has been sent to your email address.
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert-error" style="text-align: left; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif

        <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 2rem;">
            <button wire:click="resendNotification" wire:loading.attr="disabled" class="btn btn-primary" style="width: 100%;">
                <span wire:loading.remove>Resend Verification Email</span>
                <span wire:loading>Sending verification email...</span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width: 100%;">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
