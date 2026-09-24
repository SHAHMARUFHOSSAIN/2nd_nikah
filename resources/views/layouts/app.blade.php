<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? \App\Models\Setting::get('site_name', '2nd Nikah') }} - {{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}</title>
    <meta name="description" content="A dignified, mature matrimonial platform for individuals seeking a second chance at marriage.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="brand-logo">
                <div class="brand-icon">2N</div>
                <div>
                    <div class="brand-title">{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}</div>
                    <div class="brand-tagline-sm">{{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}</div>
                </div>
            </a>

            <nav>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                    
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a></li>
                        @if(auth()->user()->is_admin)
                            <li><a href="/admin" class="nav-link" style="color: var(--primary); font-weight: 600;">Admin Panel</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 0.4rem 1rem;">Log Out</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="nav-link">Log In</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-primary">Create Account</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        {{ $slot }}
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h3>{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}</h3>
                <p>{{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}</p>
                <p style="margin-top: 0.75rem; font-size: 0.85rem; color: #FBCFE8;">
                    A dignified, trustworthy matrimonial platform designed with privacy, integrity, and respect.
                </p>
            </div>
            
            <div class="footer-links">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Log In</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>

            <div class="footer-links">
                <h4>Legal & Support</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Support</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', '2nd Nikah') }}. All rights reserved.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
