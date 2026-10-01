<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        {{-- Header --}}
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                My Shortlisted Profiles
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">
                Profiles you have saved for quick reference and future connection.
            </p>
        </div>

        @if (session()->has('status'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                {{ session('status') }}
            </div>
        @endif

        @if ($shortlists->isEmpty())
            <div class="card" style="border-radius: 1.5rem; text-align: center; padding: 3rem 1.5rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⭐</div>
                <h3 style="font-size: 1.25rem; color: var(--bg-wine); font-weight: 600;">Your Shortlist is Empty</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 400px; margin: 0.5rem auto 1.5rem;">
                    Browse members in our directory and click the star icon to save profiles to your shortlist.
                </p>
                <a href="{{ route('members.index') }}" class="btn btn-primary">
                    Browse Members Directory
                </a>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach ($shortlists as $item)
                    @php
                        $targetUser = $item->shortlistedUser;
                        $profile = $targetUser?->memberProfile;
                    @endphp
                    @if ($profile && $profile->is_profile_visible && $targetUser->is_active)
                        <div class="card" style="border-radius: 1.25rem; padding: 1.5rem; text-align: center; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                            
                            {{-- Remove Star Button --}}
                            <button wire:click="removeShortlist({{ $targetUser->id }})" title="Remove from shortlist" type="button" style="position: absolute; top: 0.75rem; right: 0.75rem; background: #FFE4E6; border: none; width: 32px; height: 32px; border-radius: 50%; color: var(--primary); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                ★
                            </button>

                            <div>
                                {{-- Avatar --}}
                                <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem; overflow: hidden;">
                                    @if ($profile->profile_photo_path)
                                        <img src="{{ Storage::url($profile->profile_photo_path) }}" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ mb_substr($profile->first_name ?: $targetUser->name, 0, 1) }}
                                    @endif
                                </div>

                                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                                    {{ $profile->full_name ?: $targetUser->name }}
                                </h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                                    {{ $profile->age }} yrs &bull; {{ $profile->city ?: 'Bangladesh' }}
                                </p>
                            </div>

                            <div style="margin-top: 1rem;">
                                <a href="{{ route('members.show', $profile->id) }}" class="btn btn-primary" style="width: 100%; font-size: 0.85rem; padding: 0.5rem;">
                                    View Full Profile
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div style="margin-top: 2rem;">
                {{ $shortlists->links() }}
            </div>
        @endif

    </div>
</div>
