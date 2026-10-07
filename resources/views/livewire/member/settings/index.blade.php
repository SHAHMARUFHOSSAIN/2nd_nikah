<div class="container" style="max-width: 800px; margin: 2rem auto;">
    
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.5rem;">
            Account & Privacy Settings
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Manage your profile discoverability, account status, and security preferences.
        </p>
    </div>

    @if (session()->has('message'))
        <div class="alert-success" style="margin-bottom: 1.5rem;">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('password_message'))
        <div class="alert-success" style="margin-bottom: 1.5rem;">
            {{ session('password_message') }}
        </div>
    @endif

    {{-- Section 1: Privacy & Discoverability --}}
    <div class="card" style="border-radius: 1.25rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 1rem; border-bottom: 1px solid var(--border-warm); padding-bottom: 0.75rem;">
            Profile Privacy & Discoverability
        </h3>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div>
                <strong style="display: block; font-size: 1rem; color: var(--text-main); margin-bottom: 0.25rem;">
                    Profile Visibility
                </strong>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0; max-width: 500px;">
                    When hidden, your profile will not appear in member searches, directory lists, or recommendations.
                </p>
            </div>

            <button wire:click="toggleProfileVisibility" type="button" class="btn {{ $isProfileVisible ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.5rem 1.25rem; min-width: 140px;">
                {{ $isProfileVisible ? '✓ Visible (Public)' : '🔒 Hidden (Private)' }}
            </button>
        </div>
    </div>

    {{-- Section 2: Account Status & Deactivation --}}
    <div class="card" style="border-radius: 1.25rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 1rem; border-bottom: 1px solid var(--border-warm); padding-bottom: 0.75rem;">
            Account Status & Deactivation
        </h3>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div>
                <strong style="display: block; font-size: 1rem; color: var(--text-main); margin-bottom: 0.25rem;">
                    Deactivate Account
                </strong>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0; max-width: 500px;">
                    Deactivating your account temporarily hides your entire profile and pauses notifications without deleting your data.
                </p>
            </div>

            <button wire:click="toggleAccountActive" wire:confirm="Are you sure you want to {{ $isActive ? 'deactivate' : 'reactivate' }} your account?" type="button" class="btn {{ $isActive ? 'btn-outline' : 'btn-primary' }}" style="padding: 0.5rem 1.25rem; min-width: 140px; {{ $isActive ? 'color: #9B1C1C; border-color: #F87171;' : '' }}">
                {{ $isActive ? 'Deactivate Account' : 'Reactivate Account' }}
            </button>
        </div>
    </div>

    {{-- Section: Blocked Members --}}
    <div class="card" style="border-radius: 1.25rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 1rem; border-bottom: 1px solid var(--border-warm); padding-bottom: 0.75rem;">
            Blocked Members (ব্লক করা সদস্যবৃন্দ)
        </h3>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div>
                <strong style="display: block; font-size: 1rem; color: var(--text-main); margin-bottom: 0.25rem;">
                    Manage Blocked Profiles
                </strong>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0; max-width: 500px;">
                    Review all members you have blocked. You can unblock them at any time to resume communication or view past messages.
                </p>
            </div>

            <a href="{{ route('member.blocked.index') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem; min-width: 140px; text-decoration: none; text-align: center;">
                🚫 View Blocked List
            </a>
        </div>
    </div>

    {{-- Section 3: Password Change --}}
    <div class="card" style="border-radius: 1.25rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 1rem; border-bottom: 1px solid var(--border-warm); padding-bottom: 0.75rem;">
            Change Password
        </h3>

        <form wire:submit.prevent="updatePassword" style="max-width: 450px;">
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.35rem;">Current Password</label>
                <input type="password" wire:model="current_password" class="form-control" style="width: 100%; padding: 0.65rem; border-radius: 0.5rem; border: 1px solid var(--border-warm);">
                @error('current_password') <span style="color: #DC2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.35rem;">New Password</label>
                <input type="password" wire:model="password" class="form-control" style="width: 100%; padding: 0.65rem; border-radius: 0.5rem; border: 1px solid var(--border-warm);">
                @error('password') <span style="color: #DC2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.35rem;">Confirm New Password</label>
                <input type="password" wire:model="password_confirmation" class="form-control" style="width: 100%; padding: 0.65rem; border-radius: 0.5rem; border: 1px solid var(--border-warm);">
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 600;">
                Update Password
            </button>
        </form>
    </div>

</div>
