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
            <div class="card" style="border-radius: 1.25rem; text-align: center; padding: 2.5rem 2rem; background: #ECFDF5; border: 1px solid #A7F3D0;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👑</div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #065F46; margin-bottom: 0.5rem;">
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
            <div class="card" style="border-radius: 1.25rem; padding: 2.25rem 2rem; background: #FFFFFF; border: 1px solid var(--border-pink); box-shadow: var(--shadow-lg);">
                <div style="border-bottom: 1px solid var(--border-pink); padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
                    <span style="background: var(--primary-light); color: var(--primary); font-size: 0.8rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 9999px; text-transform: uppercase; border: 1px solid var(--border-pink);">
                        ORDER SUMMARY
                    </span>
                    <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin-top: 0.5rem; margin-bottom: 0.25rem;">
                        Checkout: {{ $plan->name }} Plan
                    </h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                        Secure SSLCommerz Encrypted Payment Gateway
                    </p>
                </div>

                {{-- Plan Details Table --}}
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-pink);">
                        <span style="color: var(--text-muted);">Selected Country Scope:</span>
                        <strong style="color: var(--text-dark);">{{ $plan->country_scope === 'BD' ? 'Bangladesh (BD)' : 'International (' . $country . ')' }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-pink);">
                        <span style="color: var(--text-muted);">Service Product:</span>
                        <strong style="color: var(--text-dark);">{{ $plan->name }} Digital Membership</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-pink);">
                        <span style="color: var(--text-muted);">Stock & Availability:</span>
                        <span style="display: inline-flex; align-items: center; gap: 0.4rem; color: #065F46; font-weight: 700; font-size: 0.85rem; background: #DEF7EC; padding: 0.2rem 0.65rem; border-radius: 9999px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span>
                            Available / In Stock (Instant Digital Access)
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-pink);">
                        <span style="color: var(--text-muted);">Quantity:</span>
                        <strong>1 License ({{ $plan->duration_days }} Days Access)</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-pink);">
                        <span style="color: var(--text-muted);">Billing Currency:</span>
                        <strong>{{ $plan->currency }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-pink);">
                        <span style="color: var(--text-muted);">Payment Gateway:</span>
                        <strong style="color: #065F46; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <svg class="w-4 h-4 text-emerald-600 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>SSLCommerz Encrypted Gateway</span>
                        </strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding-top: 0.5rem; font-size: 1.3rem; font-weight: 800; color: var(--primary);">
                        <span>Total Payable Amount:</span>
                        <span>{{ $plan->formatted_price }}</span>
                    </div>
                </div>

                {{-- Supported Payment Methods Visual Logos --}}
                <div style="background: #FFFFFF; border: 1px solid var(--border-pink); padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>Accepted Payment Methods</span>
                        <span style="color: #059669; font-weight: 800;">Instant Digital Activation</span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
                        <span title="bKash" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/bkash.png') }}" alt="bKash" style="height: 24px; width: auto; object-fit: contain;"></span>
                        <span title="Nagad" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/nagad.png') }}" alt="Nagad" style="height: 22px; width: auto; object-fit: contain;"></span>
                        <span title="Rocket" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/rocket.png') }}" alt="Rocket" style="height: 24px; width: auto; object-fit: contain;"></span>
                        <span title="Upay" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/upay.png') }}" alt="Upay" style="height: 24px; width: auto; object-fit: contain;"></span>
                        <span title="Visa" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/visa.png') }}" alt="Visa" style="height: 22px; width: auto; object-fit: contain;"></span>
                        <span title="Mastercard" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/mastercard.png') }}" alt="Mastercard" style="height: 22px; width: auto; object-fit: contain;"></span>
                        <span title="AMEX" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/amex.png') }}" alt="AMEX" style="height: 22px; width: auto; object-fit: contain;"></span>
                        <span title="DBBL Nexus" style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 0.5rem; padding: 0.25rem 0.6rem; display: inline-flex; align-items: center; justify-content: center; height: 34px;"><img src="{{ asset('images/gateways/nexus.png') }}" alt="Nexus" style="height: 20px; width: auto; object-fit: contain;"></span>
                    </div>
                </div>

                {{-- Security Guarantee Banner --}}
                <div style="background: var(--primary-light); border: 1px solid var(--border-pink); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.875rem; color: var(--text-main); line-height: 1.5; display: flex; align-items: center; gap: 0.6rem;">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span><strong>Security Guarantee:</strong> Your transaction is encrypted and processed via SSLCommerz. We never store credit card numbers, CVV, PIN, or banking passwords.</span>
                </div>

                {{-- MANDATORY Compliance Checkbox (Terms, Privacy, Return & Refund Policy) --}}
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem 1.15rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; font-size: 0.875rem; color: #334155; line-height: 1.5; user-select: none;">
                        <input type="checkbox" wire:model.live="agreeTerms" style="margin-top: 0.2rem; width: 1.15rem; height: 1.15rem; accent-color: #E11D48; cursor: pointer; flex-shrink: 0;">
                        <span>
                            I have read, understood and agree to the 
                            <a href="/terms-and-conditions" target="_blank" style="color: #E11D48; font-weight: 700; text-decoration: underline;">Terms & Conditions</a>, 
                            <a href="/privacy-policy" target="_blank" style="color: #E11D48; font-weight: 700; text-decoration: underline;">Privacy Policy</a>, and 
                            <a href="/refund-policy" target="_blank" style="color: #E11D48; font-weight: 700; text-decoration: underline;">Return and Refund Policy</a> (7 to 10 working days settlement timeline).
                        </span>
                    </label>
                    @if (! $agreeTerms)
                        <div style="font-size: 0.75rem; color: #B45309; font-weight: 600; margin-top: 0.5rem; padding-left: 1.9rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span>⚠️</span> You must check this box to confirm agreement before placing your order.
                        </div>
                    @endif
                </div>

                <div>
                    <button wire:click="initiatePayment" wire:loading.attr="disabled" type="button" class="btn btn-primary" style="width: 100%; padding: 0.95rem 1.5rem; font-size: 1.05rem; box-shadow: var(--shadow-md); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; opacity: {{ $agreeTerms ? '1' : '0.6' }}; cursor: {{ $agreeTerms ? 'pointer' : 'not-allowed' }};">
                        <span wire:loading.remove style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Proceed to SSLCommerz Payment ({{ $plan->formatted_price }})</span>
                        </span>
                        <span wire:loading style="display: none; align-items: center; gap: 0.5rem;">
                            <span>Connecting to SSLCommerz...</span>
                        </span>
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
                        Return to Membership Plans
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
