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

                @php
                    $isBdScope = ($plan->country_scope === 'BD');
                @endphp

                {{-- MANDATORY Important Declaration, Islamic Warning, No-Refund Policy & User Consent Box --}}
                <div x-data="{ lang: '{{ $isBdScope ? 'bn' : 'en' }}' }" style="background: #FFFFFF; border: 1.5px solid #FBCFE8; border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 1.75rem; box-shadow: 0 4px 20px rgba(225, 29, 72, 0.04);">
                    
                    {{-- Header with Language Selector (if International, show toggle) --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1.5px solid #FCE7F3; padding-bottom: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span style="font-size: 1.35rem;">📜</span>
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: #111827; margin: 0; line-height: 1.3;" x-show="lang === 'bn'">
                                    গুরুত্বপূর্ণ ঘোষণা ও ব্যবহারকারীর সম্মতি
                                </h3>
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: #111827; margin: 0; line-height: 1.3;" x-show="lang === 'en'" style="display: none;">
                                    Important Notice & User Consent
                                </h3>
                                <span style="font-size: 0.75rem; color: #E11D48; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                    {{ $isBdScope ? 'পেমেন্ট পূর্ববর্তী বাধ্যতামূলক নীতিমালা' : 'Mandatory Pre-Payment Acknowledgment' }}
                                </span>
                            </div>
                        </div>

                        @if (! $isBdScope)
                            {{-- International Language Toggle --}}
                            <div style="display: inline-flex; background: #F1F5F9; padding: 0.25rem; border-radius: 0.75rem; border: 1px solid #E2E8F0;">
                                <button type="button" @click="lang = 'en'" :style="lang === 'en' ? 'background: #E11D48; color: #FFF; font-weight: 700;' : 'background: transparent; color: #475569; font-weight: 600;'" style="border: none; border-radius: 0.5rem; padding: 0.3rem 0.75rem; font-size: 0.8rem; cursor: pointer; transition: all 0.2s;">
                                    English
                                </button>
                                <button type="button" @click="lang = 'bn'" :style="lang === 'bn' ? 'background: #E11D48; color: #FFF; font-weight: 700;' : 'background: transparent; color: #475569; font-weight: 600;'" style="border: none; border-radius: 0.5rem; padding: 0.3rem 0.75rem; font-size: 0.8rem; cursor: pointer; transition: all 0.2s;">
                                    বাংলা
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Scrollable Declaration Content Box --}}
                    <div style="max-height: 320px; overflow-y: auto; padding: 1.25rem; background: #FFF7ED; border: 1px solid #FED7AA; border-radius: 0.875rem; font-size: 0.875rem; line-height: 1.7; color: #334155; margin-bottom: 1.25rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        
                        {{-- BENGALI CONTENT --}}
                        <div x-show="lang === 'bn'" style="{{ ! $isBdScope ? 'display: none;' : '' }}">
                            <div style="margin-bottom: 1.25rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #9A3412; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>📌</span> প্ল্যাটফর্মের ভূমিকা ও দায়ভারের সীমাবদ্ধতা:
                                </h4>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    <strong>2ndNikah.com</strong> একটি অনলাইন ম্যাট্রিমনি প্ল্যাটফর্ম, যার উদ্দেশ্য হলো বিবাহের উদ্দেশ্যে প্রাপ্তবয়স্ক ব্যক্তিদের মধ্যে পরিচিত হওয়ার একটি মাধ্যম প্রদান করা। 2ndNikah.com কোনো বিবাহের নিশ্চয়তা প্রদান করে না এবং ব্যবহারকারীদের ব্যক্তিগত সিদ্ধান্তের জন্য দায়ী নয়।
                                </p>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    প্রত্যেক ব্যবহারকারী নিজের সিদ্ধান্ত, যোগাযোগ ও কার্যকলাপের জন্য নিজেই দায়ী থাকবেন। কোনো ব্যক্তির সঙ্গে যোগাযোগ, সম্পর্ক বা বিবাহের সিদ্ধান্ত নেওয়ার আগে তার পরিচয়, বৈবাহিক অবস্থা, পারিবারিক ও অন্যান্য গুরুত্বপূর্ণ তথ্য যথাযথভাবে যাচাই করুন। প্রয়োজনে নিজের অভিভাবক, পরিবারের সদস্য বা বিশ্বস্ত ব্যক্তিদের সঙ্গে পরামর্শ করে সিদ্ধান্ত নিন।
                                </p>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    ব্যবহারকারীদের দেওয়া তথ্য সম্পূর্ণ সঠিক বা নির্ভুল—এমন কোনো নিশ্চয়তা 2ndNikah.com প্রদান করে না। তাই অন্য কোনো ব্যবহারকারীর তথ্যের ওপর নির্ভর করার আগে নিজ দায়িত্বে যাচাই করুন।
                                </p>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    ব্যবহারকারীদের মধ্যকার ব্যক্তিগত যোগাযোগ, সাক্ষাৎ, বিবাহ, আর্থিক লেনদেন, উপহার, অর্থ প্রদান, প্রতারণা, ব্যক্তিগত বিরোধ, ক্ষতি বা অন্য কোনো কার্যকলাপের জন্য প্রযোজ্য আইন যতটুকু অনুমতি দেয়, তার সীমার মধ্যে 2ndNikah.com কর্তৃপক্ষ কোনো দায়ভার গ্রহণ করে না।
                                </p>
                                <p style="margin-bottom: 0; text-align: justify;">
                                    কোনো ব্যবহারকারী অশালীন, অনৈতিক, প্রতারণামূলক, হয়রানিমূলক বা বেআইনি কোনো কাজে লিপ্ত হলে তার সম্পূর্ণ দায়ভার সংশ্লিষ্ট ব্যবহারকারীর নিজের। 2ndNikah.com এ ধরনের কার্যকলাপের বিরুদ্ধে প্রয়োজনীয় ব্যবস্থা নেওয়ার অধিকার সংরক্ষণ করে এবং প্রযোজ্য আইন অনুযায়ী সংশ্লিষ্ট কর্তৃপক্ষের সঙ্গে সহযোগিতা করতে পারে।
                                </p>
                            </div>

                            {{-- Islamic Warning --}}
                            <div style="margin-bottom: 1.25rem; background: #FEF2F2; border-left: 4px solid #DC2626; padding: 0.85rem 1rem; border-radius: 0.5rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #991B1B; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>🕌</span> ইসলামী সতর্কতা:
                                </h4>
                                <p style="margin-bottom: 0.6rem; color: #7F1D1D; text-align: justify;">
                                    এই প্ল্যাটফর্মটি হালালভাবে বিবাহের উদ্দেশ্যে পরিচিত হওয়ার একটি মাধ্যম মাত্র। প্রত্যেক মুসলিম ব্যবহারকারীকে আল্লাহর প্রতি জবাবদিহিতার কথা স্মরণ রেখে শালীনতা, সততা ও ইসলামী আদব-আখলাক বজায় রেখে প্ল্যাটফর্মটি ব্যবহার করার জন্য অনুরোধ করা হচ্ছে।
                                </p>
                                <p style="margin-bottom: 0.6rem; color: #7F1D1D; text-align: justify;">
                                    কোনো ব্যবহারকারী যদি এই প্ল্যাটফর্মের মাধ্যমে পরিচিত হয়ে অশালীনতা, প্রতারণা, অবৈধ সম্পর্ক, অন্যায় বা অন্য কোনো অনৈতিক কাজে নিজেকে জড়িয়ে ফেলেন, তবে সেই কাজের দায়ভার সম্পূর্ণভাবে সংশ্লিষ্ট ব্যক্তির নিজের।
                                </p>
                                <p style="margin-bottom: 0; color: #991B1B; font-weight: 700; text-align: justify;">
                                    এ ধরনের ব্যক্তিগত কাজ, সিদ্ধান্ত বা গুনাহের জন্য 2ndNikah.com কর্তৃপক্ষ দুনিয়া ও আখিরাতে কোনো দায়ভার গ্রহণ করবে না।
                                </p>
                            </div>

                            {{-- Strict No Refund Policy --}}
                            <div style="margin-bottom: 1.25rem; background: #FFFBEB; border-left: 4px solid #D97706; padding: 0.85rem 1rem; border-radius: 0.5rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #92400E; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>🚫</span> নো-রিফান্ড নীতিমালা (Strict No-Refund Policy):
                                </h4>
                                <p style="margin-bottom: 0; color: #78350F; text-align: justify;">
                                    <strong>2ndNikah.com-এর মেম্বারশিপ প্ল্যান একটি তাৎক্ষণিক ডিজিটাল সেবা।</strong> পেমেন্ট সম্পন্ন হওয়ার সাথে সাথেই মেম্বারশিপ প্যাকেজের সুবিধাসমূহ স্বয়ংক্রিয়ভাবে সক্রিয় হয়ে যায়। বিধায় <strong>আমাদের কোনো প্রকার রিফান্ড বা অর্থ ফেরত নীতিমালা নেই (সেবা সম্পূর্ণ নন-রিফান্ডেবল)</strong>। সাবস্ক্রিপশন সম্পন্ন করার পূর্বে অনুগ্রহ করে আপনার কাঙ্ক্ষিত প্যাকেজ ও সিদ্ধান্ত ১০০% নিশ্চিত করুন।
                                </p>
                            </div>

                            {{-- Consent Explanation --}}
                            <div style="background: #F0FDF4; border-left: 4px solid #16A34A; padding: 0.85rem 1rem; border-radius: 0.5rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #166534; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>✍️</span> ব্যবহারকারীর সম্মতি:
                                </h4>
                                <p style="margin-bottom: 0; color: #14532D; text-align: justify;">
                                    “Accept & Continue” বাটনে ক্লিক করার মাধ্যমে আমি ঘোষণা করছি যে আমি উপরোক্ত বিষয়গুলো পড়েছি, বুঝেছি এবং সম্মত হয়েছি। আমি বুঝতে পারছি যে 2ndNikah.com শুধুমাত্র বিবাহের উদ্দেশ্যে পরিচিত হওয়ার একটি মাধ্যম এবং আমার নিজের সিদ্ধান্ত ও কার্যকলাপের দায়ভার আমার নিজের।
                                </p>
                            </div>
                        </div>

                        {{-- ENGLISH CONTENT (For International and Toggle) --}}
                        <div x-show="lang === 'en'" style="{{ $isBdScope ? 'display: none;' : '' }}">
                            <div style="margin-bottom: 1.25rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #9A3412; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>📌</span> Platform Role & Limitation of Liability:
                                </h4>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    <strong>2ndNikah.com</strong> is an online matrimonial platform intended solely to facilitate introduction between consenting adults for the solemn purpose of marriage. 2ndNikah.com does not guarantee marriage and is not liable for users' personal choices, communications, or decisions.
                                </p>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    Each user is solely responsible for their own decisions, communications, and activities. Before communicating, developing a relationship, or deciding to marry any person, you must independently and thoroughly verify their identity, marital status, family background, and other critical details. Consult your guardians, family members, or trusted advisors before deciding.
                                </p>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    2ndNikah.com does not warrant or guarantee that user-submitted information is complete, accurate, or error-free. Verify all details at your own discretion and responsibility before relying on any member's profile.
                                </p>
                                <p style="margin-bottom: 0.75rem; text-align: justify;">
                                    To the fullest extent permitted by applicable law, 2ndNikah.com and its management assume no liability for personal meetings, marriages, financial transactions, gifts, money transfers, fraud, personal disputes, damages, or any user conduct.
                                </p>
                                <p style="margin-bottom: 0; text-align: justify;">
                                    If any user engages in indecent, unethical, fraudulent, harassing, abusive, or unlawful activities, the entire liability rests solely with that user. 2ndNikah.com reserves the right to take necessary legal and disciplinary action against such conduct and cooperate fully with law enforcement authorities under applicable law.
                                </p>
                            </div>

                            {{-- Islamic Warning --}}
                            <div style="margin-bottom: 1.25rem; background: #FEF2F2; border-left: 4px solid #DC2626; padding: 0.85rem 1rem; border-radius: 0.5rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #991B1B; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>🕌</span> Islamic Admonition & Warning:
                                </h4>
                                <p style="margin-bottom: 0.6rem; color: #7F1D1D; text-align: justify;">
                                    This platform is strictly an introductory medium for halal matrimonial purposes. Every Muslim user is earnestly urged to remain mindful of their accountability before Allah Almighty, maintaining decency, honesty, integrity, and Islamic adab (ethics and decorum) at all times while using the platform.
                                </p>
                                <p style="margin-bottom: 0.6rem; color: #7F1D1D; text-align: justify;">
                                    If any user engages in vulgarity, deception, illicit relationships, injustice, or unethical conduct after connecting through this platform, the sole responsibility rests upon that individual.
                                </p>
                                <p style="margin-bottom: 0; color: #991B1B; font-weight: 700; text-align: justify;">
                                    2ndNikah.com bears no liability or accountability whatsoever in this world or in the Hereafter for such personal deeds, decisions, or sins.
                                </p>
                            </div>

                            {{-- Strict No Refund Policy --}}
                            <div style="margin-bottom: 1.25rem; background: #FFFBEB; border-left: 4px solid #D97706; padding: 0.85rem 1rem; border-radius: 0.5rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #92400E; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>🚫</span> Strict No-Refund Policy:
                                </h4>
                                <p style="margin-bottom: 0; color: #78350F; text-align: justify;">
                                    <strong>2ndNikah.com membership plans are instant, non-tangible digital services.</strong> Upon payment confirmation, premium features and access privileges are automatically and immediately provisioned to your account. Therefore, <strong>we maintain a strict NO-REFUND POLICY (all membership purchases are completely non-refundable)</strong>. Please confirm your choice and details thoroughly before placing your order.
                                </p>
                            </div>

                            {{-- Consent Explanation --}}
                            <div style="background: #F0FDF4; border-left: 4px solid #16A34A; padding: 0.85rem 1rem; border-radius: 0.5rem;">
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #166534; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>✍️</span> User Consent Declaration:
                                </h4>
                                <p style="margin-bottom: 0; color: #14532D; text-align: justify;">
                                    By checking the box and clicking “Accept & Continue”, I solemnly declare that I have read, understood, and agreed to all the above notices, Islamic warnings, strict no-refund policy, and platform rules. I acknowledge that 2ndNikah.com is solely an introductory medium for marriage, and I am solely responsible for my own choices, decisions, and actions.
                                </p>
                            </div>
                        </div>

                    </div>

                    {{-- MANDATORY Consent Checkbox & Legal Links --}}
                    <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; padding: 1rem 1.25rem; border-radius: 0.875rem; margin-bottom: 0.5rem;">
                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; font-size: 0.9rem; color: #1E293B; line-height: 1.55; user-select: none;">
                            <input type="checkbox" wire:model.live="agreeTerms" style="margin-top: 0.25rem; width: 1.25rem; height: 1.25rem; accent-color: #E11D48; cursor: pointer; flex-shrink: 0;">
                            <div>
                                <span style="font-weight: 700; color: #0F172A;" x-show="lang === 'bn'">
                                    আমি উপরোক্ত শর্তাবলি, ইসলামী সতর্কতা ও নো-রিফান্ড নীতিমালা পড়েছি এবং পূর্ণ সম্মতি জ্ঞাপন করছি।
                                </span>
                                <span style="font-weight: 700; color: #0F172A;" x-show="lang === 'en'" style="display: none;">
                                    I have read, understood, and agree to the Important Notice, Islamic Warning, and Strict No-Refund Policy.
                                </span>
                                <div style="font-size: 0.8rem; color: #64748B; margin-top: 0.35rem;">
                                    {{ $isBdScope ? 'এছাড়াও আমাদের ওয়েবসাইট কমপ্লায়েন্স:' : 'Also governed by our Website Compliances:' }}
                                    <a href="/terms-and-conditions" target="_blank" style="color: #E11D48; font-weight: 700; text-decoration: underline;">{{ $isBdScope ? 'শর্তাবলি (Terms)' : 'Terms & Conditions' }}</a>, 
                                    <a href="/privacy-policy" target="_blank" style="color: #E11D48; font-weight: 700; text-decoration: underline;">{{ $isBdScope ? 'গোপনীয়তা নীতি (Privacy)' : 'Privacy Policy' }}</a>, এবং 
                                    <a href="/refund-policy" target="_blank" style="color: #E11D48; font-weight: 700; text-decoration: underline;">{{ $isBdScope ? 'রিফান্ড নীতিমালা (Refund Policy)' : 'Refund Policy' }}</a>।
                                </div>
                            </div>
                        </label>
                        @if (! $agreeTerms)
                            <div style="font-size: 0.8rem; color: #B45309; font-weight: 700; margin-top: 0.6rem; padding-left: 2rem; display: flex; align-items: center; gap: 0.4rem;">
                                <span>⚠️</span>
                                <span x-show="lang === 'bn'">পেমেন্ট করতে অনুগ্রহ করে উপরোক্ত বক্সে টিক চিহ্ন দিয়ে সম্মতি প্রদান করুন।</span>
                                <span x-show="lang === 'en'" style="display: none;">You must check the box to confirm agreement before proceeding to payment.</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action / Payment Submission Button (Responsive and No Cropping) --}}
                <div>
                    <button wire:click="initiatePayment" wire:loading.attr="disabled" type="button" class="btn btn-primary" style="width: 100%; padding: 1rem 1.5rem; font-size: 1.05rem; font-weight: 800; border-radius: 1rem; box-shadow: 0 4px 16px rgba(225, 29, 72, 0.35); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; white-space: normal; word-break: normal; text-align: center; opacity: {{ $agreeTerms ? '1' : '0.6' }}; cursor: {{ $agreeTerms ? 'pointer' : 'not-allowed' }};">
                        <span wire:loading.remove style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            @if ($isBdScope)
                                <span>Accept & Continue — পেমেন্ট করুন ({{ $plan->formatted_price }})</span>
                            @else
                                <span>Accept & Continue — Proceed to Payment ({{ $plan->formatted_price }})</span>
                            @endif
                        </span>
                        <span wire:loading style="display: none; align-items: center; gap: 0.5rem;">
                            <span>Connecting to SSLCommerz...</span>
                        </span>
                    </button>
                    @if (! $agreeTerms)
                        <p style="text-align: center; font-size: 0.8rem; color: #94A3B8; margin-top: 0.6rem; font-weight: 600;">
                            🔒 {{ $isBdScope ? 'শর্তাবলিতে সম্মতি দেওয়ার পর পেমেন্ট গেটওয়ে সক্রিয় হবে' : 'Secure payment gateway unlocks upon accepting the terms above' }}
                        </p>
                    @endif
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
