<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Log in to your {{ \App\Models\Setting::get('site_name', '2nd Nikah') }} account</p>
        </div>

        @if (session()->has('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="login">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" wire:model.defer="email" class="form-input" placeholder="yourname@example.com" required autofocus>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                    <a href="{{ route('password.request') }}" style="font-size: 0.85rem;">Forgot password?</a>
                </div>
                <input type="password" id="password" wire:model.defer="password" class="form-input" placeholder="Your account password" required>
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" id="remember" wire:model="remember" style="accent-color: var(--primary); width: 16px; height: 16px;">
                <label for="remember" style="font-size: 0.9rem; color: var(--text-main); font-weight: 500; cursor: pointer;">Keep me logged in</label>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Log In
                </button>
            </div>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Don't have an account yet? <a href="{{ route('register') }}" style="font-weight: 600;">Create Account</a>
        </div>
    </div>
</div>
