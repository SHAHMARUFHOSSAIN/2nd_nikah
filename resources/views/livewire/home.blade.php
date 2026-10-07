<div>
    {{-- Hero Section (Ultra Premium Matrimonial Experience) --}}
    @if (\App\Models\Setting::get('hero_enabled', true))
        @php
            $heroImageUrl = \App\Models\Setting::getAssetUrl('hero_image') ?: \App\Models\Setting::getAssetUrl('hero_image_path');
        @endphp
        <section class="relative bg-gradient-to-br from-rose-50/80 via-pink-50/40 to-slate-50/80 pt-14 pb-16 md:pt-20 md:pb-24 border-b border-rose-100/80 overflow-hidden">
            {{-- Ambient Glow Blobs --}}
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[480px] bg-gradient-to-tr from-rose-300/30 via-pink-200/25 to-amber-100/20 blur-3xl rounded-full pointer-events-none -z-10"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#e11d480d_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

            <div class="container px-4 mx-auto max-w-7xl relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 md:gap-14 items-center">
                    
                    {{-- Left Column: Copy, CTAs, and Trust Proof --}}
                    <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                        
                        {{-- Eyebrow Badge --}}
                        <div>
                            <span class="inline-flex items-center gap-2 bg-white/95 backdrop-blur-md text-rose-700 border border-rose-200/90 text-xs sm:text-sm font-extrabold px-4 py-1.5 rounded-full shadow-sm">
                                <span class="flex h-2 w-2 rounded-full bg-rose-600 animate-ping"></span>
                                <span>✨ {{ \App\Models\Setting::get('hero_eyebrow', 'BANGLADESH\'S #1 TRUSTED 2ND MARRIAGE PLATFORM') }}</span>
                            </span>
                        </div>
                        
                        {{-- Heading --}}
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.15] tracking-tight">
                            {!! \App\Models\Setting::get('hero_heading', 'Every Heart Deserves a <span class="bg-gradient-to-r from-rose-600 via-pink-600 to-rose-700 bg-clip-text text-transparent">Blessed 2nd Chance</span>') !!}
                        </h1>
                        
                        {{-- Subtitle --}}
                        <p class="text-slate-600 text-sm sm:text-base md:text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0 font-medium">
                            {{ \App\Models\Setting::get('hero_subtitle', 'Welcome to 2nd Nikah — a dignified, trusted, and respectful environment tailored for individuals looking to embark on their second chapter of life with sincerity, dignity, and faith.') }}
                        </p>
                        
                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-1">
                            <a href="{{ \App\Models\Setting::get('hero_cta_primary_url', '/members') }}" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-500 to-pink-600 text-white font-extrabold text-sm sm:text-base shadow-xl shadow-rose-500/25 hover:shadow-2xl hover:shadow-rose-500/35 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 group">
                                <span>{{ \App\Models\Setting::get('hero_cta_primary_text', 'Browse Verified Members') }}</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            @guest
                                <a href="{{ \App\Models\Setting::get('hero_cta_secondary_url', '/register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/95 hover:bg-white text-slate-800 font-extrabold text-sm sm:text-base border border-slate-200/90 shadow-md hover:border-rose-300 hover:text-rose-600 hover:-translate-y-0.5 transition-all duration-200">
                                    <span>{{ \App\Models\Setting::get('hero_cta_secondary_text', 'Create Free Account') }}</span>
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/95 hover:bg-white text-slate-800 font-extrabold text-sm sm:text-base border border-slate-200/90 shadow-md hover:border-rose-300 hover:text-rose-600 hover:-translate-y-0.5 transition-all duration-200">
                                    <span>Go to Dashboard</span>
                                </a>
                            @endguest
                        </div>

                        {{-- Micro Trust Indicators --}}
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6 text-xs text-slate-600 font-bold pt-4 border-t border-rose-200/60">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-black">✓</span>
                                <span>100% NID Verified</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">🔒</span>
                                <span>Photo Privacy Control</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs">💍</span>
                                <span>Islamic Values & Dignity</span>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column: Showcase Card (Uploaded Image OR Interactive Quick Match Finder) --}}
                    <div class="lg:col-span-5 relative">
                        @if ($heroImageUrl)
                            {{-- Uploaded Hero Image Showcase with Floating Trust Badges --}}
                            <div class="relative mx-auto max-w-md lg:max-w-none">
                                <div class="absolute -inset-3 bg-gradient-to-tr from-rose-500/25 via-pink-400/20 to-amber-300/20 rounded-3xl blur-xl opacity-75"></div>
                                <div class="relative bg-white/90 backdrop-blur-md p-3 rounded-3xl border border-white shadow-2xl overflow-hidden group">
                                    <img src="{{ $heroImageUrl }}" alt="{{ \App\Models\Setting::get('site_name', '2nd Nikah') }} Hero" class="w-full h-auto max-h-[440px] object-cover rounded-2xl group-hover:scale-[1.01] transition-transform duration-300">
                                    <div class="absolute top-6 right-6 bg-slate-900/80 backdrop-blur-md border border-white/20 text-white text-[11px] font-black px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span>Verified Matches</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Interactive Matrimonial Search & Quick Discovery Card --}}
                            <div class="relative mx-auto max-w-md lg:max-w-none">
                                <div class="absolute -inset-3 bg-gradient-to-tr from-rose-500/25 via-pink-400/20 to-purple-500/20 rounded-3xl blur-xl opacity-75"></div>
                                <div class="relative bg-white/95 backdrop-blur-md p-6 sm:p-7 rounded-3xl border border-white shadow-2xl space-y-5">
                                    
                                    {{-- Card Header --}}
                                    <div class="flex items-center justify-between pb-3 border-b border-rose-100/80">
                                        <div>
                                            <span class="text-xs font-black uppercase tracking-wider text-rose-600 block">QUICK MATCH DISCOVERY</span>
                                            <h3 class="text-base sm:text-lg font-black text-slate-900">Find Your Life Partner</h3>
                                        </div>
                                        <span class="text-2xl">💍</span>
                                    </div>

                                    {{-- Quick Match Form Leading to /members --}}
                                    <form action="{{ route('members.index') }}" method="GET" class="space-y-3.5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">I am looking for</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-50 cursor-pointer font-bold text-xs text-rose-700 transition">
                                                    <input type="radio" name="gender" value="female" class="text-rose-600 focus:ring-rose-500 accent-rose-600" checked>
                                                    <span>A Bride (পাত্রী)</span>
                                                </label>
                                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100 cursor-pointer font-bold text-xs text-slate-700 transition">
                                                    <input type="radio" name="gender" value="male" class="text-rose-600 focus:ring-rose-500 accent-rose-600">
                                                    <span>A Groom (পাত্র)</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2.5">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Marital Status</label>
                                                <select name="marital_status" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50/70 p-2.5 focus:border-rose-400 focus:ring-rose-400">
                                                    <option value="">Any Status (সকল)</option>
                                                    <option value="Unmarried">Unmarried (অবিবাহিত)</option>
                                                    <option value="Married (Seeking 2nd Marriage)">Married - Seeking 2nd Marriage (বিবাহিত - ২য় বিবাহ)</option>
                                                    <option value="Divorced">Divorced (তালাকপ্রাপ্ত / ডিভোর্সড)</option>
                                                    <option value="Widowed">Widowed (বিধবা / বিপত্নীক)</option>
                                                    <option value="Single Parent">Single Parent (সন্তানসহ)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Division</label>
                                                <select name="division" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50/70 p-2.5 focus:border-rose-400 focus:ring-rose-400">
                                                    <option value="">All Divisions</option>
                                                    <option value="Dhaka">Dhaka</option>
                                                    <option value="Chittagong">Chittagong</option>
                                                    <option value="Sylhet">Sylhet</option>
                                                    <option value="Rajshahi">Rajshahi</option>
                                                    <option value="Khulna">Khulna</option>
                                                    <option value="Barisal">Barisal</option>
                                                    <option value="Rangpur">Rangpur</option>
                                                    <option value="Mymensingh">Mymensingh</option>
                                                    <option value="Abroad">Abroad (প্রবাসী)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs sm:text-sm shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 group">
                                            <span>Search Verified Profiles</span>
                                            <svg class="w-4 h-4 text-rose-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </form>

                                    {{-- Mini Social Proof --}}
                                    <div class="pt-2 flex items-center justify-between text-[11px] text-slate-500 font-semibold border-t border-slate-100">
                                        <span class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span>Active Members Online</span>
                                        </span>
                                        <span class="text-rose-600 font-bold">100% Privacy Shield</span>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </section>
    @endif

    {{-- Hero App Download Bar (Manageable via Admin Panel: Active/Inactive & URLs) --}}
    @php
        $heroAppEnabled = (bool) \App\Models\Setting::get('hero_app_download_enabled', true);
        $playEnabled = (bool) \App\Models\Setting::get('app_download_google_play_enabled', true);
        $appleEnabled = (bool) \App\Models\Setting::get('app_download_apple_store_enabled', true);
        $playUrl = \App\Models\Setting::get('app_download_google_play_url') ?: (\App\Models\Setting::get('play_store_url') ?: 'https://play.google.com/store/apps');
        $appleUrl = \App\Models\Setting::get('app_download_apple_store_url') ?: (\App\Models\Setting::get('app_store_url') ?: 'https://apps.apple.com');
    @endphp

    @if ($heroAppEnabled && ($playEnabled || $appleEnabled))
        <div class="container px-4 mx-auto max-w-7xl relative z-20 -mt-6 sm:-mt-8 md:-mt-10 mb-8 sm:mb-12">
            <div class="bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 text-white rounded-3xl p-5 sm:p-7 md:p-8 shadow-2xl border border-slate-800/90 relative overflow-hidden group">
                {{-- Ambient Light Accent --}}
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row items-center justify-between gap-6 relative z-10">
                    
                    {{-- Left Title & Info --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 max-w-2xl">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 p-0.5 shadow-lg shadow-rose-500/25 shrink-0 flex items-center justify-center">
                            <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                                <svg class="w-7 h-7 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-rose-400 text-xs font-black uppercase tracking-wider mb-1">
                                <span>📱 OFFICIAL 2ND NIKAH APPS</span>
                            </div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-black text-white tracking-tight" style="color: #FFFFFF !important;">
                                {{ \App\Models\Setting::get('hero_app_download_title', 'Download The 2nd Nikah Mobile App') }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-200 mt-1 leading-relaxed font-medium">
                                {{ \App\Models\Setting::get('hero_app_download_subtitle', 'Stay connected anywhere with instant real-time notifications, private biometric chat & secure match alerts.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Right: The 2 App Download Options (Google Play & App Store) --}}
                    <div class="flex flex-wrap sm:flex-nowrap items-center justify-center gap-3.5 shrink-0 w-full sm:w-auto">
                        
                        {{-- Option 1: Google Play Store Button (Controlled by Admin Panel) --}}
                        @if ($playEnabled)
                            <a href="{{ $playUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3.5 bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 rounded-2xl border border-slate-700/80 hover:border-emerald-400/80 transition-all duration-200 shadow-lg hover:shadow-emerald-500/10 hover:-translate-y-0.5 group w-full sm:w-auto justify-center">
                                <svg class="w-7 h-7 shrink-0" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M47.2 22.3C44.7 24.8 43.3 28.7 43.3 33.9v444.2c0 5.2 1.4 9.1 3.9 11.6l1.3 1.2 248.6-248.6v-5.8L48.5 21.1l-1.3 1.2z" fill="#00D2FF"/>
                                    <path d="M379.2 324.4l-82.1-82.1v-5.8l82.1-82.1 1.8 1 97.4 55.3c27.8 15.8 27.8 41.7 0 57.5l-97.2 55.2-2 1z" fill="#FFCF00"/>
                                    <path d="M381.2 323.4L297.1 239.3 48.5 487.9c9.2 9.7 24.4 10.9 41.5 1.3l291.2-165.8" fill="#FF3A44"/>
                                    <path d="M381.2 188.6L90 22.8C72.9 13.1 57.7 14.4 48.5 24.1L297.1 272.7l84.1-84.1z" fill="#00E676"/>
                                </svg>
                                <div class="flex flex-col text-left">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-300 font-bold leading-tight">GET IT ON</span>
                                    <span class="text-sm font-black text-white group-hover:text-emerald-400 transition leading-tight">Google Play</span>
                                </div>
                            </a>
                        @endif

                        {{-- Option 2: Apple App Store Button (Controlled by Admin Panel) --}}
                        @if ($appleEnabled)
                            <a href="{{ $appleUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3.5 bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 rounded-2xl border border-slate-700/80 hover:border-rose-400/80 transition-all duration-200 shadow-lg hover:shadow-rose-500/10 hover:-translate-y-0.5 group w-full sm:w-auto justify-center">
                                <svg class="w-7 h-7 shrink-0 fill-current text-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.85c.66-.8 1.11-1.92.99-3.04-.96.04-2.13.64-2.81 1.44-.61.71-1.14 1.85-1 2.95 1.08.08 2.17-.55 2.82-1.35z"/>
                                </svg>
                                <div class="flex flex-col text-left">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-300 font-bold leading-tight">DOWNLOAD ON THE</span>
                                    <span class="text-sm font-black text-white group-hover:text-rose-400 transition leading-tight">App Store</span>
                                </div>
                            </a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Value Pillars Section --}}
    @if (\App\Models\Setting::get('section_why_us_enabled', true))
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 relative">
            {{-- Background Glow Accents --}}
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-72 bg-gradient-to-r from-rose-100/40 via-pink-100/30 to-purple-100/40 blur-3xl rounded-full pointer-events-none -z-10"></div>

            <div class="text-center mb-12 max-w-2xl mx-auto space-y-3">
                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200/80 text-xs font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow-2xs">
                    <span>🛡️ TRUST & INTEGRITY GUARANTEE</span>
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ \App\Models\Setting::get('section_why_us_title', 'Built On Trust, Faith & Privacy') }}
                </h2>
                <p class="text-slate-600 text-xs sm:text-base leading-relaxed">
                    {{ \App\Models\Setting::get('section_why_us_subtitle', 'Designed specifically for mature, genuine individuals seeking a blessed partnership.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                
                {{-- Card 1: Strict Confidentiality --}}
                <div class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-rose-100/90 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-pink-500 rounded-t-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div>
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-rose-50 via-rose-100/70 to-pink-50 border border-rose-200/80 text-rose-600 flex items-center justify-center mb-6 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <rect x="9" y="10" width="6" height="5" rx="1"/>
                                <path d="M10 10V8a2 2 0 1 1 4 0v2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 mb-2.5 group-hover:text-rose-600 transition-colors">
                            Strict Confidentiality
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                            Your privacy is paramount. Enjoy complete control over your personal information, profile discoverability, contact details, and interactions.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-extrabold text-rose-700 bg-rose-50/60 -mx-6 -mb-6 p-4 rounded-b-3xl">
                        <span>🔒 100% Encrypted & Private</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </div>
                </div>

                {{-- Card 2: Verified Profiles --}}
                <div class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-rose-100/90 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-pink-500 rounded-t-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div>
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-rose-50 via-rose-100/70 to-pink-50 border border-rose-200/80 text-rose-600 flex items-center justify-center mb-6 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 mb-2.5 group-hover:text-rose-600 transition-colors">
                            Verified Profiles
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                            Every registration undergoes thorough verification checks to eliminate fake profiles and maintain a safe, high-integrity matrimonial community.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-extrabold text-rose-700 bg-rose-50/60 -mx-6 -mb-6 p-4 rounded-b-3xl">
                        <span>✓ ID & Phone Screened</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </div>
                </div>

                {{-- Card 3: Sincere Intentions --}}
                <div class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-rose-100/90 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-pink-500 rounded-t-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div>
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-rose-50 via-rose-100/70 to-pink-50 border border-rose-200/80 text-rose-600 flex items-center justify-center mb-6 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-900 mb-2.5 group-hover:text-rose-600 transition-colors">
                            Sincere Intentions
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                            A focused, dignified matrimonial experience tailored exclusively for genuine individuals seeking a blessed, lifelong second chance.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-extrabold text-rose-700 bg-rose-50/60 -mx-6 -mb-6 p-4 rounded-b-3xl">
                        <span>💎 Dignified & Serious</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </div>
                </div>

            </div>
        </section>
    @endif

    {{-- Recent Discoverable Members --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 sm:pb-16">
        <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Recent Discoverable Members</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Verified members on our platform looking for a second chance</p>
            </div>

            @if(!$recentMembers->isEmpty())
                <x-ui.button :href="route('members.index')" variant="outline" size="sm">
                    View All Members &rarr;
                </x-ui.button>
            @endif
        </div>

        @if($recentMembers->isEmpty())
            <x-ui.empty-state icon="👥" title="No verified profiles available" description="There are currently no public members listed. Be the first to join our growing community!" :action-href="route('register')" action-text="Create Account" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentMembers as $profile)
                    <x-ui.member-card :profile="$profile" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- App Download Section --}}
    @if (\App\Models\Setting::get('app_download_section_enabled', true))
        @php
            $playUrl = \App\Models\Setting::get('app_download_google_play_url') ?: \App\Models\Setting::get('play_store_url');
            $appleUrl = \App\Models\Setting::get('app_download_apple_store_url') ?: \App\Models\Setting::get('app_store_url');
            $appPreviewUrl = \App\Models\Setting::getAssetUrl('app_preview_image');
        @endphp
        <section class="relative overflow-hidden w-full border-t border-rose-100 bg-gradient-to-br from-rose-50/70 via-pink-50/30 to-white py-14 sm:py-20">
            <div class="max-w-4xl mx-auto px-4 flex flex-col items-center text-center">
                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 border border-rose-200/80 text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-3 shadow-2xs">
                    <span>📱 MOBILE APPLICATION</span>
                </span>

                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-3 tracking-tight">
                    {{ \App\Models\Setting::get('app_download_title', 'Take 2nd Nikah Wherever You Go') }}
                </h2>

                <p class="text-slate-600 text-xs sm:text-base mb-8 leading-relaxed max-w-2xl">
                    {{ \App\Models\Setting::get('app_download_description', 'Download our official mobile app for iOS and Android to manage your profile, receive instant real-time notifications, and connect securely anywhere.') }}
                </p>

                {{-- Real Smartphone Card Mockup Frame --}}
                <div class="relative w-full mb-10 max-w-[300px] sm:max-w-[340px] transition-transform duration-300 hover:scale-[1.01]">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-rose-500/25 via-pink-400/20 to-purple-600/30 rounded-[58px] blur-2xl opacity-90"></div>
                    <div class="relative bg-slate-900 rounded-[48px] p-3 sm:p-3.5 shadow-2xl border-[6px] border-slate-950 ring-1 ring-white/10">
                        <div class="relative bg-white rounded-[38px] overflow-hidden aspect-[9/18.5] border border-slate-200/90 shadow-inner flex flex-col justify-between text-left text-slate-800 font-sans select-none">
                            @if ($appPreviewUrl)
                                <img src="{{ $appPreviewUrl }}" alt="2nd Nikah Mobile App Preview" class="w-full h-full object-cover rounded-[36px]">
                            @else
                                {{-- Real App UI Mockup --}}
                                <div class="w-full h-full bg-slate-50 flex flex-col justify-between relative overflow-hidden">
                                    {{-- Status Bar Notch --}}
                                    <div class="w-24 h-4 bg-slate-900 rounded-b-xl mx-auto absolute top-0 left-1/2 -translate-x-1/2 z-30 flex items-center justify-center gap-1.5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-800 border border-slate-700"></div>
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                                    </div>

                                    {{-- App Top Bar --}}
                                    <div class="pt-5 px-3.5 pb-2 bg-white border-b border-rose-100 flex flex-col items-center shrink-0 relative z-20">
                                        <div class="w-full flex items-center justify-between mb-1">
                                            <button type="button" class="w-7 h-7 rounded-full bg-rose-50 text-rose-600 text-xs font-bold flex items-center justify-center">‹</button>
                                            <div class="text-center">
                                                <div class="text-xs font-black text-rose-600 tracking-tight flex items-center justify-center gap-1">
                                                    <span>♥</span>
                                                    <span>AI Match</span>
                                                    <span>♥</span>
                                                </div>
                                                <div class="text-[9px] text-slate-400 font-medium">We find better, you decide</div>
                                            </div>
                                            <button type="button" class="w-7 h-7 rounded-full bg-rose-50 text-rose-600 text-xs font-bold flex items-center justify-center">?</button>
                                        </div>
                                        <div class="bg-rose-50/90 border border-rose-200/60 rounded-full px-3 py-0.5 text-[9.5px] font-extrabold text-rose-600 flex items-center gap-1 shadow-2xs">
                                            <span>✨</span>
                                            <span>AI is finding your perfect match</span>
                                            <span>♥</span>
                                        </div>
                                    </div>

                                    {{-- Main Member Card Content --}}
                                    <div class="p-3 flex-1 flex flex-col justify-between overflow-hidden relative">
                                        {{-- Member Card --}}
                                        <div class="bg-white rounded-2xl border border-rose-100 shadow-md overflow-hidden relative group">
                                            {{-- Floating Hearts Animation Background --}}
                                            <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden opacity-70">
                                                <span class="absolute top-2 right-4 text-xs animate-bounce" style="animation-duration: 2.5s;">💖</span>
                                                <span class="absolute top-10 right-2 text-[10px] animate-pulse">💕</span>
                                                <span class="absolute top-16 left-3 text-xs animate-bounce" style="animation-duration: 3s;">💗</span>
                                            </div>

                                            <div class="relative h-44 bg-slate-200 overflow-hidden">
                                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=400" alt="Sadia Islam" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                                
                                                <div class="absolute top-2 left-2 bg-rose-600 text-white text-[8.5px] font-black px-2 py-0.5 rounded-full flex items-center gap-1 shadow-2xs">
                                                    <span>★</span> New Match
                                                </div>
                                                <div class="absolute top-2 right-2 bg-black/40 backdrop-blur-xs text-white text-[8.5px] font-bold px-1.5 py-0.5 rounded-full">
                                                    1/1
                                                </div>

                                                <div class="absolute bottom-2 left-2 right-2 text-white">
                                                    <div class="flex items-center gap-1 font-black text-xs">
                                                        <span>Sadia Islam</span>
                                                        <span class="text-blue-400 bg-white/20 text-[9px] rounded-full w-3.5 h-3.5 inline-flex items-center justify-center">✓</span>
                                                    </div>
                                                    <div class="text-[9.5px] text-slate-200 font-medium">25, Dhaka, Bangladesh</div>
                                                </div>
                                            </div>

                                            <div class="p-2 space-y-1.5 bg-white">
                                                <div class="flex flex-wrap gap-1">
                                                    <span class="bg-slate-100 text-slate-700 text-[8.5px] font-semibold px-2 py-0.5 rounded-md border border-slate-200/80">🎓 MBA Graduate</span>
                                                    <span class="bg-slate-100 text-slate-700 text-[8.5px] font-semibold px-2 py-0.5 rounded-md border border-slate-200/80">💼 Marketing Executive</span>
                                                    <span class="bg-slate-100 text-slate-700 text-[8.5px] font-semibold px-2 py-0.5 rounded-md border border-slate-200/80">🕌 Practicing Muslim</span>
                                                    <span class="bg-slate-100 text-slate-700 text-[8.5px] font-semibold px-2 py-0.5 rounded-md border border-slate-200/80">💍 Single</span>
                                                </div>
                                                <div class="pt-0.5 text-right">
                                                    <span class="text-[9px] font-bold text-rose-600 underline">View Details ⓘ</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Privacy Notice --}}
                                        <div class="bg-rose-50/80 border border-rose-100 rounded-xl p-1.5 text-center my-1 flex items-center justify-center gap-1">
                                            <span class="text-rose-500 text-[9px]">🛡️</span>
                                            <span class="text-[8.5px] font-bold text-slate-700">Safe & Secure Matching</span>
                                            <span class="text-[8px] text-slate-400">• We respect your privacy 🔒</span>
                                        </div>

                                        {{-- Quick Actions Row --}}
                                        <div class="grid grid-cols-4 gap-1 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-8 h-8 rounded-full bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold flex items-center justify-center shadow-2xs">💬</div>
                                                <span class="text-[7.5px] font-bold text-slate-700 mt-0.5">Chat Accept</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <div class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold flex items-center justify-center shadow-2xs">📱</div>
                                                <span class="text-[7.5px] font-bold text-slate-700 mt-0.5">WhatsApp</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-rose-600 to-pink-500 text-white text-sm font-bold flex items-center justify-center shadow-md -mt-0.5">💖</div>
                                                <span class="text-[7.5px] font-extrabold text-rose-600 mt-0.5">Like</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold flex items-center justify-center shadow-2xs">❌</div>
                                                <span class="text-[7.5px] font-bold text-slate-700 mt-0.5">Reject</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Phone Bottom Nav Bar --}}
                                    <div class="bg-white border-t border-slate-100 px-2 py-1.5 flex items-center justify-around shrink-0 relative z-20">
                                        <div class="flex flex-col items-center text-slate-400">
                                            <span class="text-[10px]">🏠</span>
                                            <span class="text-[7px]">Home</span>
                                        </div>
                                        <div class="flex flex-col items-center text-slate-400">
                                            <span class="text-[10px]">💖</span>
                                            <span class="text-[7px]">Matches</span>
                                        </div>
                                        <div class="flex flex-col items-center text-rose-600 font-bold">
                                            <span class="w-6 h-6 rounded-full bg-rose-600 text-white text-[10px] flex items-center justify-center shadow-xs">✨</span>
                                            <span class="text-[7px]">AI Match</span>
                                        </div>
                                        <div class="flex flex-col items-center text-slate-400 relative">
                                            <span class="text-[10px]">💬</span>
                                            <span class="absolute -top-1 right-0 bg-rose-600 text-white text-[6px] font-bold w-2.5 h-2.5 rounded-full flex items-center justify-center">3</span>
                                            <span class="text-[7px]">Messages</span>
                                        </div>
                                        <div class="flex flex-col items-center text-slate-400">
                                            <span class="text-[10px]">👤</span>
                                            <span class="text-[7px]">Profile</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Download Store Buttons --}}
                <div class="flex flex-wrap items-center justify-center gap-4 max-w-md w-full">
                    @if (\App\Models\Setting::get('app_download_google_play_enabled', true) && !empty($playUrl))
                        <a href="{{ $playUrl }}" target="_blank" rel="noopener noreferrer" class="shrink-0 text-decoration-none group">
                            <div class="inline-flex items-center gap-3.5 px-6 py-3 rounded-2xl bg-slate-950 text-white hover:bg-slate-900 transition-all duration-200 shadow-lg hover:shadow-xl border border-slate-800/90 shrink-0">
                                <svg class="w-6 h-6 shrink-0" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M47.2 22.3C44.7 24.8 43.3 28.7 43.3 33.9v444.2c0 5.2 1.4 9.1 3.9 11.6l1.3 1.2 248.6-248.6v-5.8L48.5 21.1l-1.3 1.2z" fill="#00D2FF"/>
                                    <path d="M379.2 324.4l-82.1-82.1v-5.8l82.1-82.1 1.8 1 97.4 55.3c27.8 15.8 27.8 41.7 0 57.5l-97.2 55.2-2 1z" fill="#FFCF00"/>
                                    <path d="M381.2 323.4L297.1 239.3 48.5 487.9c9.2 9.7 24.4 10.9 41.5 1.3l291.2-165.8" fill="#FF3A44"/>
                                    <path d="M381.2 188.6L90 22.8C72.9 13.1 57.7 14.4 48.5 24.1L297.1 272.7l84.1-84.1z" fill="#00E676"/>
                                </svg>
                                <div class="text-left leading-tight">
                                    <div class="text-[9px] uppercase font-bold text-slate-300 tracking-wider">GET IT ON</div>
                                    <div class="text-xs sm:text-sm font-black text-white group-hover:text-rose-400 transition">Google Play</div>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (\App\Models\Setting::get('app_download_apple_store_enabled', true) && !empty($appleUrl))
                        <a href="{{ $appleUrl }}" target="_blank" rel="noopener noreferrer" class="shrink-0 text-decoration-none group">
                            <div class="inline-flex items-center gap-3.5 px-6 py-3 rounded-2xl bg-slate-950 text-white hover:bg-slate-900 transition-all duration-200 shadow-lg hover:shadow-xl border border-slate-800/90 shrink-0">
                                <svg class="w-6 h-6 shrink-0 fill-current text-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.85c.66-.8 1.11-1.92.99-3.04-.96.04-2.13.64-2.81 1.44-.61.71-1.14 1.85-1 2.95 1.08.08 2.17-.55 2.82-1.35z"/>
                                </svg>
                                <div class="text-left leading-tight">
                                    <div class="text-[9px] uppercase font-bold text-slate-300 tracking-wider">DOWNLOAD ON THE</div>
                                    <div class="text-xs sm:text-sm font-black text-white group-hover:text-rose-400 transition">App Store</div>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
