<div class="container">
    {{-- Page Header --}}
    <div class="card" style="border-radius: 1.5rem; margin-bottom: 2.5rem; background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F7 100%);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">
                    MATRIMONIAL DIRECTORY
                </span>
                <h1 style="font-size: 2rem; font-weight: 700; margin-top: 0.25rem;">Discover Verified Members</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">
                    Browse verified, active matrimonial profiles seeking a dignified partnership on {{ \App\Models\Setting::get('site_name', '2nd Nikah') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Members Grid or Empty State --}}
    @if ($members->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <div class="empty-state-title">No verified profiles are currently available.</div>
            <div class="empty-state-desc">There are currently no verified public member profiles matching discoverability criteria. Check back soon!</div>
            @guest
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                </div>
            @endguest
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            @foreach ($members as $profile)
                <x-member-card :profile="$profile" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            {{ $members->links() }}
        </div>
    @endif
</div>
