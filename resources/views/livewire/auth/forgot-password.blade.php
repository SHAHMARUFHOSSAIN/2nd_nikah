<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Reset Your Password</h1>
            <p>Enter your email address to receive a password reset link</p>
        </div>

        @if (session()->has('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetLink">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" wire:model.defer="email" class="form-input" placeholder="yourname@example.com" required autofocus>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Send Password Reset Link
                </button>
            </div>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Remembered your password? <a href="{{ route('login') }}" style="font-weight: 600;">Back to Login</a>
        </div>
    </div>
</div>
