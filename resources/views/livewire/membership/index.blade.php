<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14 space-y-10">
    
    {{-- Page Header --}}
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 border border-rose-200/80 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-2xs">
            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <span>PREMIUM MEMBERSHIP</span>
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
            Choose Your Matrimonial Plan
        </h1>
        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
            Dignified, trustworthy premium plans designed to help you connect with serious matrimonial candidates.
        </p>
    </div>

    {{-- Country Selector Card --}}
    <x-ui.card padding="spacious" class="max-w-4xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-0.5">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Select Your Country of Residence</span>
                </h3>
                <p class="text-xs text-slate-500">
                    Pricing and currency automatically adapt based on your country scope.
                </p>
            </div>

            <div class="w-full sm:w-72">
                <select wire:model.live="country" class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-white border border-slate-200/90 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none font-bold text-slate-800 transition shadow-2xs">
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
    </x-ui.card>

    {{-- Pricing Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        @foreach ($plans as $plan)
            <x-ui.card padding="spacious" overflow="visible" :hover="true" class="relative flex flex-col justify-between border-2 {{ $plan->billing_interval === 'monthly' ? 'border-rose-500 shadow-lg' : 'border-rose-100/90 shadow-xs' }}">
                
                @if ($plan->billing_interval === 'monthly')
                    <span class="absolute -top-3.5 right-6 z-20 bg-gradient-to-r from-rose-600 to-pink-600 text-white text-[11px] font-black px-3.5 py-1 rounded-full uppercase tracking-wider shadow-md">
                        Most Popular
                    </span>
                @endif

                <div class="space-y-6">
                    <div class="flex items-baseline justify-between gap-2 border-b border-slate-100 pb-4">
                        <h2 class="text-xl font-extrabold text-slate-900">
                            {{ $plan->name }}
                        </h2>
                        <span class="text-xs font-black text-rose-600 bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-lg">
                            {{ $plan->duration_days }} Days
                        </span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-3xl sm:text-4xl font-black text-rose-600 tracking-tight leading-none">
                            {{ $plan->formatted_price }}
                        </span>
                        <span class="text-slate-500 text-xs font-medium">
                            / {{ $plan->billing_interval }}
                        </span>
                    </div>

                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        {{ $plan->description }}
                    </p>

                    <ul class="space-y-2.5 text-xs text-slate-700 font-medium">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Full access to send proposal interests</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Direct connections upon acceptance</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Priority verified member badge</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>SSLCommerz secure encrypted checkout</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-8">
                    <x-ui.button wire:click="selectPlan('{{ $plan->slug }}')" type="button" :variant="$plan->billing_interval === 'monthly' ? 'primary' : 'outline'" size="lg" class="w-full">
                        Select {{ $plan->name }} Plan
                    </x-ui.button>
                </div>
            </x-ui.card>
        @endforeach
    </div>

</div>
