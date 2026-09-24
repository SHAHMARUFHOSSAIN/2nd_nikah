<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- Header & Subnav --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                    My Connections
                </h1>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Mutual matrimonial connections established through accepted interest proposals.
                </p>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('member.interests.received') }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Received
                </a>
                <a href="{{ route('member.interests.sent') }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Sent
                </a>
                <a href="{{ route('member.connections') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Connections ({{ \App\Models\UserMatch::forUser(auth()->id())->count() }})
                </a>
            </div>
        </div>

        {{-- Matches List --}}
        @if ($matches->total() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach ($matches as $match)
                    @php
                        $partner = $match->getPartnerUser(auth()->id());
                        $partnerProfile = $partner?->memberProfile;
                    @endphp

                    <div class="card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1; min-width: 260px;">
                            {{-- Avatar --}}
                            <div style="width: 75px; height: 75px; border-radius: 1rem; overflow: hidden; background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E6 100%); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                @if ($partnerProfile?->photo_url)
                                    <img src="{{ $partnerProfile->photo_url }}" alt="{{ $partnerProfile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 1.85rem; font-weight: 700; color: var(--primary);">
                                        {{ strtoupper(substr($partnerProfile?->first_name ?: ($partner->name ?? 'M'), 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
                                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                                        {{ $partnerProfile?->full_name ?: $partner->name }}
                                    </h3>
                                    <span style="color: #03543F; background: #DEF7EC; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.6rem; border-radius: 9999px;">
                                        ✓ Connected
                                    </span>
                                </div>

                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                                    @if ($partnerProfile?->age)
                                        <span>{{ $partnerProfile->age }} yrs</span>
                                        <span>•</span>
                                    @endif
                                    @if ($partnerProfile?->marital_status)
                                        <span>{{ $partnerProfile->marital_status }}</span>
                                        <span>•</span>
                                    @endif
                                    @if ($partnerProfile?->religion)
                                        <span>{{ $partnerProfile->religion }}</span>
                                        <span>•</span>
                                    @endif
                                    @if ($partnerProfile?->city || $partnerProfile?->country)
                                        <span>📍 {{ implode(', ', array_filter([$partnerProfile->city, $partnerProfile->country])) }}</span>
                                    @endif
                                </div>

                                <div style="font-size: 0.78rem; color: var(--text-light);">
                                    Matched on {{ $match->matched_at->format('M d, Y') }} ({{ $match->matched_at->diffForHumans() }})
                                </div>
                            </div>
                        </div>

                        {{-- Status & View Profile --}}
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                            @if ($partnerProfile)
                                <a href="{{ route('members.show', $partnerProfile->id) }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                                    View Profile
                                </a>
                            @endif

                            <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">
                                Messaging will be available after the messaging system is enabled.
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                {{ $matches->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">💍</div>
                <h3 class="empty-state-title">No connections yet</h3>
                <p class="empty-state-desc">
                    When you accept a received interest or another member accepts your sent proposal, your mutual connection will be established here.
                </p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('search.index') }}" class="btn btn-primary">
                        Find Members
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
