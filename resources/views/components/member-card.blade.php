@props(['profile'])

<div class="card" style="border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; height: 100%; transition: transform 0.2s ease, box-shadow 0.2s ease;">
    <div>
        {{-- Card Top / Image & Status Badge --}}
        <div style="position: relative; width: 100%; height: 220px; background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E6 100%); display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 1rem; margin-bottom: 1.25rem;">
            @if ($profile->photo_url)
                <img src="{{ $profile->photo_url }}" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div style="text-align: center; color: var(--primary); font-family: var(--font-heading);">
                    <div style="font-size: 3rem; font-weight: 700; opacity: 0.8; line-height: 1;">
                        {{ strtoupper(substr($profile->first_name ?: ($profile->user->name ?? 'M'), 0, 1)) }}
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-top: 0.25rem;">
                        No Photo Uploaded
                    </span>
                </div>
            @endif

            {{-- Verification Badge Overlay --}}
            @if ($profile->user && $profile->user->email_verified_at)
                <span style="position: absolute; top: 12px; right: 12px; background: rgba(255, 255, 255, 0.92); color: #03543F; font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 9999px; box-shadow: var(--shadow-sm); backdrop-filter: blur(4px); display: inline-flex; align-items: center; gap: 0.3rem;">
                    ✓ Verified
                </span>
            @endif
        </div>

        {{-- Card Main Info --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.4rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                    {{ $profile->full_name }}
                </h3>
                @if ($profile->age)
                    <span style="font-size: 0.95rem; font-weight: 600; color: var(--primary);">
                        {{ $profile->age }} yrs
                    </span>
                @endif
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.4rem; margin-bottom: 1rem; font-size: 0.85rem;">
                @php
                    $metaItems = array_filter([
                        $profile->marital_status,
                        $profile->religion,
                        $profile->gender ? ucfirst(strtolower($profile->gender)) : null,
                    ]);
                @endphp

                @foreach ($metaItems as $item)
                    <span style="background: {{ $loop->first ? 'var(--primary-light)' : '#F3ECE9' }}; color: {{ $loop->first ? 'var(--primary)' : 'var(--text-main)' }}; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; display: inline-block;">
                        {{ $item }}
                    </span>
                    @if (! $loop->last)
                        <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700; padding: 0 0.1rem;">•</span>
                    @endif
                @endforeach
            </div>

            @if ($profile->city || $profile->country)
                <div style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.35rem;">
                    📍 <span>{{ implode(', ', array_filter([$profile->city, $profile->country])) }}</span>
                </div>
            @endif

            @if ($profile->occupation || $profile->education)
                <div style="font-size: 0.85rem; color: var(--text-main); margin-bottom: 1.25rem;">
                    <strong>{{ $profile->occupation ?: $profile->education }}</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- Card Action Footer --}}
    <div style="border-top: 1px solid var(--border-warm); padding-top: 1rem; margin-top: 0.5rem;">
        <a href="{{ route('members.show', $profile->id) }}" class="btn btn-outline" style="width: 100%; text-align: center;">
            View Profile
        </a>
    </div>
</div>
