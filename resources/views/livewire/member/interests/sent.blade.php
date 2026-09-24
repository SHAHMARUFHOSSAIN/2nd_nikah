<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- Header & Subnav --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                    Sent Interests
                </h1>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Track proposals and interest requests you have sent to other members.
                </p>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('member.interests.received') }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Received
                </a>
                <a href="{{ route('member.interests.sent') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Sent ({{ \App\Models\Interest::where('sender_id', auth()->id())->where('status', 'pending')->count() }})
                </a>
                <a href="{{ route('member.connections') }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Connections
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert-success">
                {{ session('message') }}
            </div>
        @endif

        {{-- Interests List --}}
        @if ($interests->total() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach ($interests as $interest)
                    @php
                        $receiverProfile = $interest->receiver->memberProfile;
                    @endphp

                    <div class="card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1; min-width: 260px;">
                            {{-- Avatar --}}
                            <div style="width: 70px; height: 70px; border-radius: 1rem; overflow: hidden; background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E6 100%); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                @if ($receiverProfile?->photo_url)
                                    <img src="{{ $receiverProfile->photo_url }}" alt="{{ $receiverProfile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">
                                        {{ strtoupper(substr($receiverProfile?->first_name ?: ($interest->receiver->name ?? 'M'), 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
                                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                                        {{ $receiverProfile?->full_name ?: $interest->receiver->name }}
                                    </h3>
                                    @if ($interest->receiver->email_verified_at)
                                        <span style="color: #03543F; background: #DEF7EC; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                                            ✓ Verified
                                        </span>
                                    @endif
                                </div>

                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                                    @if ($receiverProfile?->age)
                                        <span>{{ $receiverProfile->age }} yrs</span>
                                        <span>•</span>
                                    @endif
                                    @if ($receiverProfile?->marital_status)
                                        <span>{{ $receiverProfile->marital_status }}</span>
                                        <span>•</span>
                                    @endif
                                    @if ($receiverProfile?->religion)
                                        <span>{{ $receiverProfile->religion }}</span>
                                        <span>•</span>
                                    @endif
                                    @if ($receiverProfile?->city || $receiverProfile?->country)
                                        <span>📍 {{ implode(', ', array_filter([$receiverProfile->city, $receiverProfile->country])) }}</span>
                                    @endif
                                </div>

                                <div style="font-size: 0.78rem; color: var(--text-light);">
                                    Sent {{ $interest->created_at->diffForHumans() }} ({{ $interest->created_at->format('M d, Y') }})
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            @if ($interest->status === 'pending')
                                <span style="background: #FFE4E6; color: var(--primary); font-size: 0.85rem; font-weight: 600; padding: 0.4rem 0.85rem; border-radius: 9999px;">
                                    Pending
                                </span>
                                <button wire:click="cancel({{ $interest->id }})" type="button" class="btn btn-outline" style="padding: 0.4rem 0.85rem; font-size: 0.85rem; color: var(--text-muted);">
                                    Cancel Interest
                                </button>
                            @elseif ($interest->status === 'accepted')
                                <span style="background: #DEF7EC; color: #03543F; font-size: 0.85rem; font-weight: 700; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                    ✓ Accepted
                                </span>
                            @elseif ($interest->status === 'rejected')
                                <span style="background: #FDE8E8; color: #9B1C1C; font-size: 0.85rem; font-weight: 600; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                    Declined
                                </span>
                            @elseif ($interest->status === 'cancelled')
                                <span style="background: #F3ECE9; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                    Cancelled
                                </span>
                            @endif

                            @if ($receiverProfile)
                                <a href="{{ route('members.show', $receiverProfile->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.85rem; font-size: 0.85rem;">
                                    View Profile
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                {{ $interests->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📤</div>
                <h3 class="empty-state-title">No sent interests yet</h3>
                <p class="empty-state-desc">
                    When you discover a member profile and express interest, your requests will appear here.
                </p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('search.index') }}" class="btn btn-primary">
                        Search Members
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
