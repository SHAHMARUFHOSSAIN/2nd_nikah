<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? \App\Models\Setting::get('site_name', '2nd Nikah') }} - {{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}</title>
    <meta name="description" content="A dignified, mature matrimonial platform for individuals seeking a second chance at marriage with faith, integrity, and privacy.">

    @if ($faviconUrl = \App\Models\Setting::getAssetUrl('favicon_path'))
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    @if (\App\Models\Setting::get('primary_color'))
        <style>
            :root {
                --primary: {{ \App\Models\Setting::get('primary_color') }};
                --border-focus: {{ \App\Models\Setting::get('primary_color') }};
            }
        </style>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="{ mobileMenuOpen: false }" @keydown.window.escape="mobileMenuOpen = false" class="bg-slate-50/70 text-slate-800 font-sans antialiased min-h-screen flex flex-col {{ request()->routeIs('member.messages.*') ? 'h-screen overflow-hidden' : '' }}">
    
    {{-- Top Sticky Header --}}
    <header class="site-header sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-rose-100/80 shadow-2xs">
        <div class="header-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            {{-- Brand Logo --}}
            <a href="{{ route('home') }}" class="brand-logo flex items-center gap-3 shrink-0 text-decoration-none group">
                @php
                    $headerLogoUrl = \App\Models\Setting::getAssetUrl('logo_path');
                @endphp
                @if ($headerLogoUrl)
                    <img src="{{ $headerLogoUrl }}" alt="{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}" class="h-9 sm:h-10 w-auto object-contain">
                @else
                    <div class="brand-icon w-10 h-10 rounded-xl bg-gradient-to-br from-rose-600 to-pink-500 text-white font-extrabold flex items-center justify-center shadow-xs group-hover:scale-105 transition duration-200">2N</div>
                @endif
                <div class="flex flex-col">
                    <span class="brand-title font-black text-lg sm:text-xl text-slate-900 leading-tight tracking-tight">{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}</span>
                    <span class="brand-tagline-sm hidden sm:inline-block text-[11px] text-slate-500 font-semibold tracking-wide">{{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}</span>
                </div>
            </a>

            {{-- Hamburger Toggle Button (Mobile ONLY md:hidden) --}}
            <button type="button" class="mobile-nav-toggle md:hidden ml-auto inline-flex items-center justify-center w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer shrink-0" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle navigation menu">
                <span x-show="!mobileMenuOpen" class="text-xl leading-none">☰</span>
                <span x-show="mobileMenuOpen" x-cloak class="text-xl leading-none">✕</span>
            </button>

            {{-- Desktop Navigation Links (Desktop ONLY hidden md:flex) --}}
            <nav class="desktop-nav hidden md:flex items-center gap-1.5">
                <ul class="nav-links flex items-center gap-1 list-none m-0 p-0">
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('search.index') }}" class="nav-link {{ request()->routeIs('search.index') ? 'active' : '' }}">Search</a></li>
                    <li><a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">Members</a></li>
                    <li><a href="{{ route('membership.index') }}" class="nav-link {{ request()->routeIs('membership.*') ? 'active' : '' }}">Membership</a></li>
                    
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                        <li>
                            <a href="{{ route('member.messages.index') }}" class="nav-link {{ request()->routeIs('member.messages.*') ? 'active' : '' }} inline-flex items-center gap-1.5">
                                <span>Messages</span>
                                @php
                                    $unreadNavCount = auth()->user()->unreadMessagesCount();
                                @endphp
                                @if ($unreadNavCount > 0)
                                    <span class="bg-rose-600 text-white text-[10px] font-black px-1.5 py-0.2 rounded-full leading-none shadow-2xs">
                                        {{ $unreadNavCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        @if(auth()->user()->is_admin)
                            <li><a href="/admin" class="nav-link text-rose-600 font-extrabold">Admin Panel</a></li>
                        @endif
                        <li class="ml-1">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <x-ui.button type="submit" variant="outline" size="sm">Log Out</x-ui.button>
                            </form>
                        </li>
                    @else
                        <li class="ml-2 flex items-center gap-2">
                            <x-ui.button :href="route('login')" variant="ghost" size="sm">Log In</x-ui.button>
                            <x-ui.button :href="route('register')" variant="primary" size="sm">Create Account</x-ui.button>
                        </li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    {{-- Mobile Secondary Menu Drawer Panel (Mobile ONLY md:hidden) --}}
    <div x-show="mobileMenuOpen" x-cloak class="md:hidden">
        {{-- Backdrop Overlay --}}
        <div class="mobile-drawer-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50" @click="mobileMenuOpen = false" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        {{-- Mobile Slide-over Drawer Panel --}}
        <div class="mobile-drawer-panel fixed top-0 right-0 bottom-0 w-[85%] max-w-xs bg-white shadow-2xl z-50 flex flex-col overflow-y-auto" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
            <div class="p-4 border-b border-rose-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="font-extrabold text-base text-slate-900">Menu & Navigation</div>
                <button type="button" @click="mobileMenuOpen = false" aria-label="Close navigation menu" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 flex items-center justify-center text-lg font-bold transition">✕</button>
            </div>

            <div class="p-4 pb-28 flex flex-col gap-4 flex-1">
                {{-- Main Navigation Links --}}
                <div class="space-y-1">
                    <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest px-2 mb-1">Primary</div>
                    <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('home') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                        <span>🏠</span> <span>Home</span>
                    </a>
                    <a href="{{ route('members.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('members.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                        <span>👥</span> <span>Find Members</span>
                    </a>
                    <a href="{{ route('search.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('search.index') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                        <span>🔍</span> <span>Search Filter</span>
                    </a>
                    <a href="{{ route('membership.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('membership.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                        <span>💎</span> <span>Membership Plans</span>
                    </a>
                </div>

                @auth
                    {{-- Member Activity & Account Links --}}
                    <div class="pt-3 border-t border-slate-100 space-y-1">
                        <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest px-2 mb-1">Account & Activity</div>
                        <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('dashboard') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>📊</span> <span>Dashboard</span>
                        </a>
                        <a href="{{ route('member.messages.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.messages.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>💬</span> <span>Messages</span>
                        </a>
                        <a href="{{ route('member.interests.received') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.interests.received') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>📥</span> <span>Received Interests</span>
                        </a>
                        <a href="{{ route('member.interests.sent') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.interests.sent') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>📤</span> <span>Sent Interests</span>
                        </a>
                        <a href="{{ route('member.connections') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.connections') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>🤝</span> <span>Connections & Matches</span>
                        </a>
                        <a href="{{ route('member.shortlists.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.shortlists.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>⭐</span> <span>Shortlisted Profiles</span>
                        </a>
                        <a href="{{ route('member.visitors.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.visitors.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>👀</span> <span>Profile Visitors</span>
                        </a>
                        <a href="{{ route('member.notifications.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.notifications.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <div class="flex items-center gap-2.5">
                                <span>🔔</span> <span>Notifications</span>
                            </div>
                            @php
                                $unreadNotifNavCount = auth()->user()->unreadNotifications()->count();
                            @endphp
                            @if ($unreadNotifNavCount > 0)
                                <span class="bg-rose-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-2xs">
                                    {{ $unreadNotifNavCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('member.settings.index') }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition {{ request()->routeIs('member.settings.*') ? 'active text-rose-600 bg-rose-50 font-bold' : '' }}">
                            <span>⚙️</span> <span>Account Settings</span>
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="/admin" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm text-rose-600 font-extrabold hover:bg-rose-50 transition">
                                <span>👑</span> <span>Admin Panel</span>
                            </a>
                        @endif
                    </div>
                @endauth

                {{-- CMS Links --}}
                <div class="pt-3 border-t border-slate-100 space-y-1">
                    <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest px-2 mb-1">Information</div>
                    @php
                        $cmsDrawerPages = \App\Models\CmsPage::where('is_published', true)->get();
                    @endphp
                    @foreach($cmsDrawerPages as $cmsPage)
                        <a href="/{{ $cmsPage->slug }}" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition">
                            <span>📄</span> <span>{{ $cmsPage->title }}</span>
                        </a>
                    @endforeach
                    @if($cmsDrawerPages->isEmpty())
                        <a href="/privacy-policy" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition">📄 Privacy Policy</a>
                        <a href="/terms-of-service" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition">📄 Terms of Service</a>
                        <a href="/contact-us" @click="mobileMenuOpen = false" class="mobile-drawer-link flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 hover:bg-rose-50 transition">📄 Contact Support</a>
                    @endif
                </div>

                {{-- Auth Buttons --}}
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-ui.button type="submit" variant="outline" class="w-full">Log Out</x-ui.button>
                        </form>
                    @else
                        <x-ui.button :href="route('login')" variant="outline" class="w-full">Log In</x-ui.button>
                        <x-ui.button :href="route('register')" variant="primary" class="w-full">Create Account</x-ui.button>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="main-content flex-1 w-full {{ request()->routeIs('member.messages.*') ? 'h-[calc(100dvh-64px)] overflow-hidden flex flex-col' : 'pb-20 md:pb-8' }}">
        {{ $slot }}
    </main>

    {{-- Mobile Bottom Navigation Bar (Mobile ONLY fixed bottom-0 md:hidden) --}}
    @unless (request()->routeIs('member.messages.show'))
    <nav class="mobile-bottom-nav fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-rose-100/90 shadow-lg md:hidden" aria-label="Mobile Bottom Navigation">
        <div class="flex items-center justify-around h-15 px-2 pb-[env(safe-area-inset-bottom,0px)]">
            {{-- 1. Home --}}
            <a href="{{ route('home') }}" class="mobile-bottom-nav-item flex-1 flex flex-col items-center justify-center gap-1 py-1 text-slate-500 text-[11px] font-medium transition-colors {{ request()->routeIs('home') ? 'active text-rose-600 font-bold' : '' }}">
                <span class="mobile-bottom-nav-icon inline-flex items-center justify-center px-2.5 py-0.5 rounded-full {{ request()->routeIs('home') ? 'bg-rose-50 text-rose-600' : '' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                <span>Home</span>
            </a>

            {{-- 2. Search --}}
            <a href="{{ route('search.index') }}" class="mobile-bottom-nav-item flex-1 flex flex-col items-center justify-center gap-1 py-1 text-slate-500 text-[11px] font-medium transition-colors {{ request()->routeIs('search.index') ? 'active text-rose-600 font-bold' : '' }}">
                <span class="mobile-bottom-nav-icon inline-flex items-center justify-center px-2.5 py-0.5 rounded-full {{ request()->routeIs('search.index') ? 'bg-rose-50 text-rose-600' : '' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <span>Search</span>
            </a>

            {{-- 3. Members --}}
            <a href="{{ route('members.index') }}" class="mobile-bottom-nav-item flex-1 flex flex-col items-center justify-center gap-1 py-1 text-slate-500 text-[11px] font-medium transition-colors {{ request()->routeIs('members.*') ? 'active text-rose-600 font-bold' : '' }}">
                <span class="mobile-bottom-nav-icon inline-flex items-center justify-center px-2.5 py-0.5 rounded-full {{ request()->routeIs('members.*') ? 'bg-rose-50 text-rose-600' : '' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                <span>Members</span>
            </a>

            {{-- 4. Messages --}}
            <a href="@auth {{ route('member.messages.index') }} @else {{ route('login') }} @endauth" class="mobile-bottom-nav-item flex-1 flex flex-col items-center justify-center gap-1 py-1 text-slate-500 text-[11px] font-medium transition-colors {{ request()->routeIs('member.messages.*') ? 'active text-rose-600 font-bold' : '' }}">
                <span class="mobile-bottom-nav-icon relative inline-flex items-center justify-center px-2.5 py-0.5 rounded-full {{ request()->routeIs('member.messages.*') ? 'bg-rose-50 text-rose-600' : '' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    @auth
                        @php
                            $unreadMobileCount = auth()->user()->unreadMessagesCount();
                        @endphp
                        @if ($unreadMobileCount > 0)
                            <span class="mobile-nav-badge absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none border-2 border-white min-w-[16px] text-center shadow-2xs">{{ $unreadMobileCount }}</span>
                        @endif
                    @endauth
                </span>
                <span>Messages</span>
            </a>

            {{-- 5. Profile --}}
            <a href="@auth {{ route('dashboard') }} @else {{ route('login') }} @endauth" class="mobile-bottom-nav-item flex-1 flex flex-col items-center justify-center gap-1 py-1 text-slate-500 text-[11px] font-medium transition-colors {{ (request()->routeIs('dashboard') || request()->routeIs('member.profile') || request()->routeIs('login') || request()->routeIs('register')) ? 'active text-rose-600 font-bold' : '' }}">
                <span class="mobile-bottom-nav-icon inline-flex items-center justify-center px-2.5 py-0.5 rounded-full {{ (request()->routeIs('dashboard') || request()->routeIs('member.profile') || request()->routeIs('login') || request()->routeIs('register')) ? 'bg-rose-50 text-rose-600' : '' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <span>@auth Profile @else Log In @endauth</span>
            </a>
        </div>
    </nav>
    @endunless

    {{-- Enterprise Footer --}}
    @unless (request()->routeIs('member.messages.*'))
    <footer class="site-footer bg-slate-950 text-slate-300 pt-16 pb-12 mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-10 border-b border-slate-800/80">
                
                {{-- Column 1: Brand & Registered Company Info --}}
                <div class="lg:col-span-2 space-y-4">
                    @php
                        $footerLogoUrl = \App\Models\Setting::getAssetUrl('footer_logo_path') 
                                      ?? \App\Models\Setting::getAssetUrl('logo_path');
                    @endphp
                    @if ($footerLogoUrl)
                        <img src="{{ $footerLogoUrl }}" alt="{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}" class="h-10 w-auto object-contain">
                    @else
                        <h3 class="text-2xl font-black text-white tracking-tight">{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}</h3>
                    @endif
                    <p class="text-sm font-semibold text-rose-400">{{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}</p>
                    <p class="text-xs text-slate-300 max-w-sm leading-relaxed">
                        {{ \App\Models\Setting::get('footer_description', 'A dignified, trustworthy matrimonial platform designed with privacy, integrity, and respect.') }}
                    </p>

                    {{-- Mandatory Merchant & Trade License Details --}}
                    <div class="pt-2 text-xs text-slate-300 space-y-1.5 bg-slate-900/80 p-3.5 rounded-xl border border-slate-800 max-w-sm">
                        <p><strong class="text-white font-bold">Organization:</strong> {{ \App\Models\Setting::get('company_name', '2ndnikah') }}</p>
                        <p><strong class="text-white font-bold">Trade License:</strong> {{ \App\Models\Setting::get('trade_license_number', 'TRAD/DNCC/025984/2024') }}</p>
                        <p><strong class="text-white font-bold">Registered Address:</strong> {{ \App\Models\Setting::get('registered_address', 'Dhaka-1100, Bangladesh') }}</p>
                        <p><strong class="text-white font-bold">Support Desk:</strong> 2ndnikahsupport@gmail.com | +880 1613591741</p>
                    </div>
                </div>

                {{-- Column 2: Navigation --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">Quick Links</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="{{ route('home') }}" class="hover:text-rose-400 transition">Home</a></li>
                        <li><a href="{{ route('members.index') }}" class="hover:text-rose-400 transition">Members Directory</a></li>
                        <li><a href="{{ route('membership.index') }}" class="hover:text-rose-400 transition">Membership Plans</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-rose-400 transition">Dashboard</a></li>
                            <li><a href="{{ route('member.settings.index') }}" class="hover:text-rose-400 transition">Account Settings</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-rose-400 transition">Log In</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-rose-400 transition">Register</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Column 3: Legal & Compliance (MANDATORY SSLCommerz requirements) --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">Legal & Compliance</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="/about-us" class="hover:text-rose-400 transition font-medium">About Us & Management</a></li>
                        <li><a href="/terms-and-conditions" class="hover:text-rose-400 transition font-medium">Terms and Conditions</a></li>
                        <li><a href="/privacy-policy" class="hover:text-rose-400 transition font-medium">Privacy Policy</a></li>
                        <li><a href="/refund-policy" class="hover:text-rose-400 transition font-medium text-amber-300 hover:text-amber-200">Return and Refund Policy</a></li>
                    </ul>
                </div>

                {{-- Column 4: App Download & Security --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">Mobile App & Trust</h4>
                    
                    @php
                        $playStoreUrl = \App\Models\Setting::get('app_download_google_play_url') ?: (\App\Models\Setting::get('play_store_url') ?: 'https://play.google.com/store/apps');
                        $appStoreUrl = \App\Models\Setting::get('app_download_apple_store_url') ?: (\App\Models\Setting::get('app_store_url') ?: 'https://apps.apple.com');
                        $playStoreEnabled = (bool) \App\Models\Setting::get('app_download_google_play_enabled', true);
                        $appStoreEnabled = (bool) \App\Models\Setting::get('app_download_apple_store_enabled', true);
                    @endphp

                    {{-- App Badges (Controlled by Admin Panel) --}}
                    <div class="flex flex-col gap-2.5">
                        @if ($playStoreEnabled)
                            <a href="{{ $playStoreUrl }}" target="_blank" class="inline-flex items-center gap-3 bg-slate-900 border border-slate-800 hover:border-emerald-500/70 hover:bg-slate-800/90 px-4 py-2.5 rounded-2xl text-white transition-all shadow-md group">
                                <svg class="w-6 h-6 shrink-0" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M47.2 22.3C44.7 24.8 43.3 28.7 43.3 33.9v444.2c0 5.2 1.4 9.1 3.9 11.6l1.3 1.2 248.6-248.6v-5.8L48.5 21.1l-1.3 1.2z" fill="#00D2FF"/>
                                    <path d="M379.2 324.4l-82.1-82.1v-5.8l82.1-82.1 1.8 1 97.4 55.3c27.8 15.8 27.8 41.7 0 57.5l-97.2 55.2-2 1z" fill="#FFCF00"/>
                                    <path d="M381.2 323.4L297.1 239.3 48.5 487.9c9.2 9.7 24.4 10.9 41.5 1.3l291.2-165.8" fill="#FF3A44"/>
                                    <path d="M381.2 188.6L90 22.8C72.9 13.1 57.7 14.4 48.5 24.1L297.1 272.7l84.1-84.1z" fill="#00E676"/>
                                </svg>
                                <div class="flex flex-col text-left">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-300 font-bold leading-tight">GET IT ON</span>
                                    <span class="text-xs font-black text-white group-hover:text-emerald-400 transition leading-tight">Google Play</span>
                                </div>
                            </a>
                        @endif

                        @if ($appStoreEnabled)
                            <a href="{{ $appStoreUrl }}" target="_blank" class="inline-flex items-center gap-3 bg-slate-900 border border-slate-800 hover:border-rose-500/70 hover:bg-slate-800/90 px-4 py-2.5 rounded-2xl text-white transition-all shadow-md group">
                                <svg class="w-6 h-6 shrink-0 fill-current text-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.85c.66-.8 1.11-1.92.99-3.04-.96.04-2.13.64-2.81 1.44-.61.71-1.14 1.85-1 2.95 1.08.08 2.17-.55 2.82-1.35z"/>
                                </svg>
                                <div class="flex flex-col text-left">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-300 font-bold leading-tight">DOWNLOAD ON THE</span>
                                    <span class="text-xs font-black text-white group-hover:text-rose-400 transition leading-tight">App Store</span>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            {{-- SSLCommerz Official Payment Methods & Security Assurance Panel (Enterprise Grade) --}}
            <div class="bg-gradient-to-b from-slate-900 to-slate-950 rounded-3xl border border-slate-800/90 p-6 sm:p-8 md:p-10 shadow-2xl text-center space-y-7">
                
                {{-- Header Badge & Title --}}
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 text-xs sm:text-sm font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>100% SECURE & ENCRYPTED PAYMENTS VIA SSLCOMMERZ</span>
                    </div>
                    <h3 class="text-lg sm:text-2xl font-black text-white tracking-tight">
                        SSLCommerz Verified Payment Methods
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-2xl mx-auto leading-relaxed font-medium">
                        We accept Visa, Mastercard, American Express, bKash, Nagad, Rocket, Upay, and major Islamic & Net Banking gateways. All transactions are protected with bank-grade 256-bit SSL encryption.
                    </p>
                </div>

                {{-- Prominent SSLCommerz Official Payment Banner (Perfect Size & High Visibility) --}}
                <div class="flex justify-center items-center">
                    <div class="bg-white rounded-3xl p-5 sm:p-7 md:p-8 shadow-2xl border border-white/95 max-w-5xl w-full transition-all duration-300 hover:shadow-emerald-950/20">
                        <div class="overflow-x-auto scrollbar-none">
                            <img src="{{ asset('images/sslcommerz-banner.png') }}" 
                                 alt="SSLCommerz Verified Payment Methods - Visa, Mastercard, AMEX, bKash, Nagad, Rocket, Upay, Bank Transfer" 
                                 class="w-full min-w-[550px] md:min-w-0 h-auto max-h-48 sm:max-h-56 md:max-h-64 object-contain mx-auto block">
                        </div>
                    </div>
                </div>

                {{-- Security Trust Highlights Bar --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 pt-1 max-w-5xl mx-auto text-left">
                    <div class="flex items-center gap-2.5 bg-slate-800/80 border border-slate-700/80 p-3.5 rounded-xl">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <div>
                            <span class="text-xs font-bold text-white block">256-Bit SSL</span>
                            <span class="text-[10px] text-slate-300 font-medium">Encrypted Transactions</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-800/80 border border-slate-700/80 p-3.5 rounded-xl">
                        <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <div>
                            <span class="text-xs font-bold text-white block">PCI-DSS Level 1</span>
                            <span class="text-[10px] text-slate-300 font-medium">Certified Compliance</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-800/80 border border-slate-700/80 p-3.5 rounded-xl">
                        <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <div>
                            <span class="text-xs font-bold text-white block">Instant Activation</span>
                            <span class="text-[10px] text-slate-300 font-medium">Immediate Digital License</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-800/80 border border-slate-700/80 p-3.5 rounded-xl">
                        <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <div>
                            <span class="text-xs font-bold text-white block">All Payment Modes</span>
                            <span class="text-[10px] text-slate-300 font-medium">Cards, bKash & Nagad</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer Bottom Bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-300 font-medium pt-2">
                <div>
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', '2nd Nikah') }}. {{ \App\Models\Setting::get('footer_copyright', 'All rights reserved.') }}
                </div>
                <div class="flex items-center gap-4 flex-wrap justify-center">
                    <a href="/terms-and-conditions" class="hover:text-rose-400 transition">Terms & Conditions</a>
                    <span>•</span>
                    <a href="/privacy-policy" class="hover:text-rose-400 transition">Privacy Policy</a>
                    <span>•</span>
                    <a href="/refund-policy" class="hover:text-rose-400 transition">Return & Refund Policy</a>
                    <span>•</span>
                    <a href="/about-us" class="hover:text-rose-400 transition">About Us</a>
                </div>
            </div>
        </div>
    </footer>
    @endunless

    @livewireScripts
</body>
</html>
