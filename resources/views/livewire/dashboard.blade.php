<div class="container">
    {{-- Header Banner --}}
    <div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F7 100%); border-color: var(--primary-light); border-radius: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">
                    {{ $user->is_admin ? 'Administrator Panel' : 'Member Account' }}
                </span>
                <h1 style="font-size: 1.8rem; margin-top: 0.25rem;">Welcome, {{ $user->name }}</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Account Email: {{ $user->email }}</p>
            </div>

            <div>
                @if($user->email_verified_at)
                    <span style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                        ✓ Email Verified
                    </span>
                @else
                    <a href="{{ route('verification.notice') }}" style="background: #FDF6B2; color: #723B10; border: 1px solid #FCE96A; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                        ⚠️ Email Pending Verification
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(!$user->is_admin)
        {{-- Normal Member Profile Status & Action Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            
            {{-- Profile Status Card --}}
            <div class="card" style="border-radius: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.15rem; color: var(--bg-wine);">Matrimonial Profile Status</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">{{ $completionPercentage }}% Complete</span>
                </div>

                <div style="width: 100%; height: 10px; background: #E5E0DC; border-radius: 9999px; overflow: hidden; margin-bottom: 1.5rem;">
                    <div style="width: {{ $completionPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); transition: width 0.4s ease;"></div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Profile Visibility:</span>
                        @if($profile && $profile->is_profile_visible)
                            <strong style="color: #03543F;">● Visible on Platform</strong>
                        @else
                            <strong style="color: #9B1C1C;">○ Hidden / Private</strong>
                        @endif
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Profile Photo:</span>
                        <strong>{{ ($profile && $profile->profile_photo_path) ? 'Uploaded' : 'Not Uploaded' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Profile Completion Status:</span>
                        <strong>{{ ($profile && $profile->is_profile_complete) ? '100% Complete' : 'Incomplete' }}</strong>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; text-align: right;">
                    <a href="{{ route('member.profile') }}" class="btn btn-primary" style="width: 100%;">
                        {{ $completionPercentage > 0 ? 'Edit Member Profile' : 'Complete Member Profile' }}
                    </a>
                </div>
            </div>

            {{-- Account Information Card --}}
            <div class="card" style="border-radius: 1.5rem;">
                <h3 style="margin-bottom: 1rem; font-size: 1.15rem; color: var(--bg-wine);">Account Information</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Member ID:</span>
                        <strong>#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Registered On:</span>
                        <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Account Type:</span>
                        <strong>Standard Matrimonial Member</strong>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Admin User Banner --}}
        <div class="card" style="border-radius: 1.5rem; text-align: center; padding: 3rem 1.5rem;">
            <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Administrator Control Panel</h2>
            <p style="color: var(--text-muted); max-width: 500px; margin: 0 auto 1.5rem;">
                You are logged in with an Administrator account. Access system settings, member profile management, and database control from the Filament Admin area.
            </p>
            <a href="/admin" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                Go to Filament Admin Panel
            </a>
        </div>
    @endif
</div>
