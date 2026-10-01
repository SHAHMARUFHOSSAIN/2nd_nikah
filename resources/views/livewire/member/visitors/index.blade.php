<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        {{-- Header --}}
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                Recent Profile Visitors
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">
                Members who have recently viewed your matrimonial profile.
            </p>
        </div>

        @if ($visitors->isEmpty())
            <div class="card" style="border-radius: 1.5rem; text-align: center; padding: 3rem 1.5rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👁️</div>
                <h3 style="font-size: 1.25rem; color: var(--bg-wine); font-weight: 600;">No Recent Visitors</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 400px; margin: 0.5rem auto 1.5rem;">
                    When authenticated members view your profile, they will be listed here.
                </p>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach ($visitors as $visit)
                    @php
                        $visitorUser = $visit->visitor;
                        $profile = $visitorUser?->memberProfile;
                    @endphp
                    @if ($profile && $profile->is_profile_visible && $visitorUser->is_active)
                        <div class="card" style="border-radius: 1.25rem; padding: 1.5rem; text-align: center; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                {{-- Avatar --}}
                                <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem; overflow: hidden;">
                                    @if ($profile->profile_photo_path)
                                        <img src="{{ Storage::url($profile->profile_photo_path) }}" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ mb_substr($profile->first_name ?: $visitorUser->name, 0, 1) }}
                                    @endif
                                </div>

                                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                                    {{ $profile->full_name ?: $visitorUser->name }}
                                </h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                                    {{ $profile->age }} yrs &bull; {{ $profile->city ?: 'Bangladesh' }}
                                </p>
                                <div style="font-size: 0.78rem; color: var(--primary); font-weight: 600;">
                                    Visited {{ $visit->last_visited_at ? $visit->last_visited_at->diffForHumans() : $visit->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div style="margin-top: 1.25rem;">
                                <a href="{{ route('members.show', $profile->id) }}" class="btn btn-primary" style="width: 100%; font-size: 0.85rem; padding: 0.5rem;">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div style="margin-top: 2rem;">
                {{ $visitors->links() }}
            </div>
        @endif

    </div>
</div>
