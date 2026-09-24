<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Create Your Account</h1>
            <p>Join {{ \App\Models\Setting::get('site_name', '2nd Nikah') }} to begin your matrimonial search</p>
        </div>

        @if (session()->has('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="register">
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" wire:model.defer="name" class="form-input" placeholder="e.g. Abdullah Khan" required autofocus>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" wire:model.defer="email" class="form-input" placeholder="yourname@example.com" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" wire:model.defer="password" class="form-input" placeholder="At least 8 characters" required>
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" class="form-input" placeholder="Repeat your password" required>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Create Account
                </button>
            </div>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="font-weight: 600;">Log In</a>
        </div>
    </div>
</div>
