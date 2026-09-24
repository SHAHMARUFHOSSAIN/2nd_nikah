<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 650px; margin: 0 auto;">
        
        {{-- Navigation Back Button --}}
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('membership.index') }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                &larr; Back to Membership Plans
            </a>
        </div>

        @if ($errorMessage)
            <div class="alert-error">
                {{ $errorMessage }}
            </div>
        @endif

        @if ($hasActiveSub && $activeSub)
            <div class="card" style="border-radius: 1.5rem; text-align: center; padding: 2.5rem 2rem; background: #DEF7EC; border: 1px solid #BCF0DA;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👑</div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #03543F; margin-bottom: 0.5rem;">
                    Active Premium Membership Exists
                </h2>
                <p style="color: #046C4E; font-size: 0.95rem; max-width: 480px; margin: 0 auto 1.5rem; line-height: 1.6;">
                    You are currently subscribed to the <strong>{{ $activeSub->membershipPlan->name }} Plan</strong>. Your active membership expires on <strong>{{ $activeSub->ends_at->format('M d, Y') }}</strong> ({{ $activeSub->days_remaining }} days remaining).
                </p>
                <div style="display: flex; justify-content: center; gap: 1rem;">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('member.payments') }}" class="btn btn-outline" style="background: #FFFFFF;">
                        Payment History
                    </a>
                </div>
            </div>
        @elseif ($plan)
            {{-- Order Summary Card --}}
            <div class="card" style="border-radius: 1.5rem; padding: 2.25rem 2rem; background: #FFFFFF; box-shadow: var(--shadow-lg);">
                <div style="border-bottom: 1px solid var(--border-warm); padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
                    <span style="background: var(--primary-light); color: var(--primary); font-size: 0.8rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 9999px; text-transform: uppercase;">
                        Order Summary
                    </span>
                    <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--bg-wine); margin-top: 0.5rem; margin-bottom: 0.25rem;">
                        Checkout: {{ $plan->name }} Plan
                    </h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                        Secure SSLCommerz Payment Gateway Integration
                    </p>
                </div>

                {{-- Plan Details Table --}}
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Selected Country Scope:</span>
                        <strong style="color: var(--bg-wine);">{{ $plan->country_scope === 'BD' ? 'Bangladesh (BD)' : 'International (' . $country . ')' }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Membership Plan:</span>
                        <strong>{{ $plan->name }} ({{ $plan->duration_days }} Days)</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Billing Currency:</span>
                        <strong>{{ $plan->currency }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm);">
                        <span style="color: var(--text-muted);">Payment Gateway:</span>
                        <strong style="color: #03543F;">🔒 SSLCommerz Gateway</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-top: 0.5rem; font-size: 1.25rem; font-weight: 800; color: var(--primary);">
                        <span>Total Payable Amount:</span>
                        <span>{{ $plan->formatted_price }}</span>
                    </div>
                </div>

                {{-- Checkout Note & Button --}}
                <div style="background: var(--bg-warm); border: 1px solid var(--border-warm); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.75rem; font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                    🛡️ <strong>Security Guarantee:</strong> Your transaction is encrypted and processed via SSLCommerz. We never store credit card numbers, CVV, PIN, or banking passwords.
                </div>

                <div>
                    <button wire:click="initiatePayment" type="button" class="btn btn-primary" style="width: 100%; padding: 0.9rem 1.5rem; font-size: 1.05rem; box-shadow: var(--shadow-md);">
                        Proceed to SSLCommerz Payment ({{ $plan->formatted_price }})
                    </button>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">⚠️</div>
                <h3 class="empty-state-title">Invalid Membership Plan</h3>
                <p class="empty-state-desc">The requested membership plan could not be found.</p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('membership.index') }}" class="btn btn-primary">
                        View Membership Plans
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
