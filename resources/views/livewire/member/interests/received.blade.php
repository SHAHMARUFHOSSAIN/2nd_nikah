<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- Header & Subnav --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                    Received Interests
                </h1>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Members who have expressed interest in connecting with you for matrimonial proposal.
                </p>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('member.interests.received') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Received ({{ \App\Models\Interest::where('receiver_id', auth()->id())->where('status', 'pending')->count() }})
                </a>
                <a href="{{ route('member.interests.sent') }}" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Sent
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
                        $senderProfile = $interest->sender->memberProfile;
                    @endphp

                    <div class="card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1; min-width: 260px;">
                            {{-- Avatar --}}
                            <div style="width: 70px; height: 70px; border-radius: 1rem; overflow: hidden; background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E6 100%); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                @if ($senderProfile?->photo_url)
                                    <img src="{{ $senderProfile->photo_url }}" alt="{{ $senderProfile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">
                                        {{ strtoupper(substr($senderProfile?->first_name ?: ($interest->sender->name ?? 'M'), 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
                                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                                        {{ $senderProfile?->full_name ?: $interest->sender->name }}
                                    </h3>
                                    @if ($interest->sender->email_verified_at)
                                        <span style="color: #03543F; background: #DEF7EC; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                                            ✓ Verified
                                        </span>
                                    @endif
                                </div>

                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                                    @if ($senderProfile?->age)
                                        <span>{{ $senderProfile->age }} yrs</span>
                                        <span>•</span>
                                    @endif
                                    @if ($senderProfile?->marital_status)
                                        <span>{{ $senderProfile->marital_status }}</span>
                                        <span>•</span>
                                    @endif
                                    @if ($senderProfile?->religion)
                                        <span>{{ $senderProfile->religion }}</span>
                                        <span>•</span>
                                    @endif
                                    @if ($senderProfile?->city || $senderProfile?->country)
                                        <span>📍 {{ implode(', ', array_filter([$senderProfile->city, $senderProfile->country])) }}</span>
                                    @endif
                                </div>

                                <div style="font-size: 0.78rem; color: var(--text-light);">
                                    Received {{ $interest->created_at->diffForHumans() }} ({{ $interest->created_at->format('M d, Y') }})
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            @if ($interest->status === 'pending')
                                <button wire:click="accept({{ $interest->id }})" type="button" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                                    ✓ Accept
                                </button>
                                <button wire:click="reject({{ $interest->id }})" type="button" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem; color: var(--primary); border-color: var(--primary-light);">
                                    ✕ Reject
                                </button>
                            @elseif ($interest->status === 'accepted')
                                <span style="background: #DEF7EC; color: #03543F; font-size: 0.85rem; font-weight: 700; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                    Connected
                                </span>
                            @elseif ($interest->status === 'rejected')
                                <span style="background: #FDE8E8; color: #9B1C1C; font-size: 0.85rem; font-weight: 600; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                    Rejected
                                </span>
                            @elseif ($interest->status === 'cancelled')
                                <span style="background: #F3ECE9; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                    Cancelled by Sender
                                </span>
                            @endif

                            @if ($senderProfile)
                                <a href="{{ route('members.show', $senderProfile->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.85rem; font-size: 0.85rem;">
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
                <div class="empty-state-icon">💌</div>
                <h3 class="empty-state-title">No received interests yet</h3>
                <p class="empty-state-desc">
                    When other members express interest in connecting with your profile, they will appear here.
                </p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('search.index') }}" class="btn btn-primary">
                        Browse Members
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
