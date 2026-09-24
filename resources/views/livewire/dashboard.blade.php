<div class="container">
    <div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F7 100%); border-color: var(--primary-light);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">Member Account</span>
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

    {{-- System Status & Empty State Modules --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.15rem;">Account Status</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.95rem;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-warm);">
                    <span style="color: var(--text-muted);">Member ID:</span>
                    <strong>#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-warm);">
                    <span style="color: var(--text-muted);">Account Created:</span>
                    <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Account Role:</span>
                    <strong>{{ $user->is_admin ? 'Administrator' : 'Standard Member' }}</strong>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.15rem;">Matrimonial Features</h3>
            <div class="empty-state" style="padding: 1.5rem 1rem;">
                <div class="empty-state-title" style="font-size: 1.05rem;">Foundation Phase Active</div>
                <div class="empty-state-desc" style="font-size: 0.85rem;">
                    Platform foundation is verified and ready. Advanced matrimonial modules (Profile creation, Matches, Messaging, Subscriptions) will be activated in upcoming phases.
                </div>
            </div>
        </div>
    </div>
</div>
