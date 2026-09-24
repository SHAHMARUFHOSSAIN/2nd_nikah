<div>
    {{-- Hero Section --}}
    <section style="background: linear-gradient(135deg, #3B0712 0%, #4A0E17 100%); color: #FFFFFF; padding: 5rem 1.5rem; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <span style="display: inline-block; background: rgba(244, 114, 182, 0.15); border: 1px solid rgba(244, 114, 182, 0.3); color: #FBCFE8; padding: 0.4rem 1.2rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem;">
                MATRIMONIAL PLATFORM
            </span>
            <h1 style="font-size: 3rem; color: #FFFFFF; font-weight: 700; margin-bottom: 1.25rem; line-height: 1.15;">
                {{ \App\Models\Setting::get('site_tagline', 'Every Heart Deserves a 2nd Chance') }}
            </h1>
            <p style="font-size: 1.15rem; color: #FCE7F3; margin-bottom: 2.5rem; font-weight: 400; line-height: 1.7;">
                Welcome to <strong>{{ \App\Models\Setting::get('site_name', '2nd Nikah') }}</strong> — a dignified, trusted, and respectful environment tailored for individuals looking to embark on their second chapter of life with sincerity and faith.
            </p>
            
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('members.index') }}" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem;">Browse Members</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline" style="color: #FFFFFF; border-color: rgba(255,255,255,0.3); padding: 0.85rem 2rem; font-size: 1rem;">Create Account</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline" style="color: #FFFFFF; border-color: rgba(255,255,255,0.3); padding: 0.85rem 2rem; font-size: 1rem;">Go to Dashboard</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Value Pillars Section --}}
    <section class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>Built On Trust & Dignity</h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0.5rem auto 0;">Designed specifically for mature, genuine individuals seeking a blessed partnership.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <div class="card" style="text-align: center; border-radius: 1.5rem;">
                <div style="width: 50px; height: 50px; background: var(--primary-light); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.5rem; font-weight: bold;">
                    1
                </div>
                <h3>Strict Confidentiality</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">Your privacy is paramount. Complete control over your information and interactions.</p>
            </div>

            <div class="card" style="text-align: center; border-radius: 1.5rem;">
                <div style="width: 50px; height: 50px; background: var(--primary-light); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.5rem; font-weight: bold;">
                    2
                </div>
                <h3>Verified Profiles</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">All registrations undergo verification to maintain a safe, high-integrity community.</p>
            </div>

            <div class="card" style="text-align: center; border-radius: 1.5rem;">
                <div style="width: 50px; height: 50px; background: var(--primary-light); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.5rem; font-weight: bold;">
                    3
                </div>
                <h3>Sincere Intentions</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">A focused matrimonial experience free from artificial distractions or fake data.</p>
            </div>
        </div>
    </section>

    {{-- Members Listing Section (Real Data / Empty State) --}}
    <section class="container" style="padding-top: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2>Recent Discoverable Members</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Verified members on our platform</p>
            </div>

            @if(!$recentMembers->isEmpty())
                <a href="{{ route('members.index') }}" class="btn btn-outline" style="font-size: 0.9rem;">
                    View All Members &rarr;
                </a>
            @endif
        </div>

        @if($recentMembers->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                <div class="empty-state-title">No verified profiles are currently available.</div>
                <div class="empty-state-desc">There are currently no verified public members listed. Be the first to join our growing community!</div>
                @guest
                    <div style="margin-top: 1.5rem;">
                        <a href="{{ route('register') }}" class="btn btn-primary">Create Your Account</a>
                    </div>
                @endguest
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach($recentMembers as $profile)
                    <x-member-card :profile="$profile" />
                @endforeach
            </div>
        @endif
    </section>
</div>
