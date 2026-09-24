<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- Page Header --}}
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <span style="background: var(--primary-light); color: var(--primary); font-size: 0.85rem; font-weight: 700; padding: 0.3rem 0.85rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em;">
                Premium Membership
            </span>
            <h1 style="font-size: 2.3rem; font-weight: 700; color: var(--bg-wine); margin-top: 0.5rem; margin-bottom: 0.5rem;">
                Choose Your Matrimonial Plan
            </h1>
            <p style="color: var(--text-muted); font-size: 1.05rem; max-width: 600px; margin: 0 auto;">
                Dignified, trustworthy premium plans to help you connect with serious matrimonial candidates.
            </p>
        </div>

        {{-- Country Selector Card --}}
        <div class="card" style="padding: 1.5rem 2rem; border-radius: 1.25rem; margin-bottom: 2.5rem; background: #FFFFFF; box-shadow: var(--shadow-md);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.2rem;">
                        🌍 Select Your Country of Residence
                    </h3>
                    <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">
                        Pricing and currency will automatically adapt to your country scope.
                    </p>
                </div>

                <div style="min-width: 240px;">
                    <select wire:model.live="country" class="form-input" style="padding: 0.65rem 1rem; font-weight: 600;">
                        <option value="Bangladesh">🇧🇩 Bangladesh (BDT ৳)</option>
                        <option value="United States">🇺🇸 United States (USD $)</option>
                        <option value="United Kingdom">🇬🇧 United Kingdom (USD $)</option>
                        <option value="Canada">🇨🇦 Canada (USD $)</option>
                        <option value="Saudi Arabia">🇸🇦 Saudi Arabia (USD $)</option>
                        <option value="United Arab Emirates">🇦🇪 United Arab Emirates (USD $)</option>
                        <option value="Australia">🇦🇺 Australia (USD $)</option>
                        <option value="Other International">🌐 Other International (USD $)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Pricing Cards Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            @foreach ($plans as $plan)
                <div class="card" style="border-radius: 1.5rem; padding: 2.25rem 2rem; display: flex; flex-direction: column; justify-content: space-between; background: #FFFFFF; border: 2px solid {{ $plan->billing_interval === 'monthly' ? 'var(--primary)' : 'var(--border-warm)' }}; position: relative; box-shadow: {{ $plan->billing_interval === 'monthly' ? 'var(--shadow-lg)' : 'var(--shadow-md)' }};">
                    
                    @if ($plan->billing_interval === 'monthly')
                        <span style="position: absolute; top: -14px; right: 24px; background: var(--primary); color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.85rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: var(--shadow-sm);">
                            Most Popular
                        </span>
                    @endif

                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.75rem;">
                            <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                                {{ $plan->name }}
                            </h2>
                            <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); background: var(--bg-warm); padding: 0.2rem 0.6rem; border-radius: 6px;">
                                {{ $plan->duration_days }} Days
                            </span>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <span style="font-size: 2.5rem; font-weight: 800; color: var(--primary); line-height: 1;">
                                {{ $plan->formatted_price }}
                            </span>
                            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">
                                / {{ $plan->billing_interval }}
                            </span>
                        </div>

                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.75rem; line-height: 1.6;">
                            {{ $plan->description }}
                        </p>

                        <ul style="list-style: none; margin-bottom: 2rem; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.92rem; color: var(--text-main);">
                            <li style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="color: #03543F; font-weight: 700;">✓</span> Full access to send Interest proposals
                            </li>
                            <li style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="color: #03543F; font-weight: 700;">✓</span> Direct connections upon acceptance
                            </li>
                            <li style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="color: #03543F; font-weight: 700;">✓</span> Priority verified member badge
                            </li>
                            <li style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="color: #03543F; font-weight: 700;">✓</span> SSLCommerz secure checkout
                            </li>
                        </ul>
                    </div>

                    <div>
                        <button wire:click="selectPlan('{{ $plan->slug }}')" type="button" class="btn {{ $plan->billing_interval === 'monthly' ? 'btn-primary' : 'btn-outline' }}" style="width: 100%; padding: 0.8rem 1.5rem; font-size: 1rem;">
                            Select {{ $plan->name }} Plan
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
