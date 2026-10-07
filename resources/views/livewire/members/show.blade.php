<div class="container">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- Navigation Back Button --}}
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('members.index') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">
                &larr; Back to Members Directory
            </a>
        </div>

        {{-- Flash Notification Alerts --}}
        @if (session()->has('message'))
            <div class="alert-success">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('info'))
            <div class="alert-success" style="background-color: #E0F2FE; color: #0369A1; border-color: #BAE6FD;">
                {{ session('info') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- Section A: Profile Header Card & Photo Gallery --}}
        <div x-data="{ activePhotoUrl: '{{ $profile->photo_url ?: '' }}', showLightbox: false }" class="card" style="border-radius: 1.5rem; margin-bottom: 2rem; background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F7 100%); overflow: hidden;">
            <div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
                
                {{-- Photo Gallery Area --}}
                <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: center; width: 180px; flex-shrink: 0;">
                    {{-- Main Photo Display --}}
                    <div @click="if (activePhotoUrl) showLightbox = true" style="width: 180px; height: 210px; border-radius: 1.25rem; overflow: hidden; background: var(--bg-warm); border: 2px solid var(--primary-light); display: flex; align-items: center; justify-content: center; position: relative; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        <template x-if="activePhotoUrl">
                            <img :src="activePhotoUrl" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                        </template>
                        <template x-if="!activePhotoUrl">
                            @if ($profile->photo_url)
                                <img src="{{ $profile->photo_url }}" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="text-align: center; color: var(--primary); font-family: var(--font-heading);">
                                    <div style="font-size: 3.5rem; font-weight: 700; opacity: 0.8; line-height: 1;">
                                        {{ strtoupper(substr($profile->first_name ?: ($profile->user->name ?? 'M'), 0, 1)) }}
                                    </div>
                                </div>
                            @endif
                        </template>

                        @if ($profilePhotos->count() > 0)
                            <div style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.6); color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Click to view</span>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnail Gallery Strip --}}
                    @if ($profilePhotos->count() > 1)
                        <div style="display: flex; gap: 0.4rem; overflow-x: auto; max-width: 180px; padding: 4px 0;">
                            @foreach ($profilePhotos as $pPhoto)
                                <button type="button" @click="activePhotoUrl = '{{ $pPhoto->url }}'" style="width: 40px; height: 40px; border-radius: 0.5rem; overflow: hidden; border: 2px solid #fff; padding: 0; cursor: pointer; flex-shrink: 0;" :class="{ 'ring-2 ring-rose-500': activePhotoUrl === '{{ $pPhoto->url }}' }">
                                    <img src="{{ $pPhoto->url }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Header Details & Action Bar --}}
                <div style="flex: 1; min-width: 260px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h1 style="font-size: 2.2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                                {{ $profile->full_name }}
                            </h1>
                            <p style="color: var(--text-muted); font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                <svg class="w-4 h-4 text-rose-500 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ implode(', ', array_filter([$profile->city, $profile->country])) ?: 'Location Not Specified' }}</span>
                            </p>
                        </div>

                        <div>
                            @if ($profile->user && $profile->user->email_verified_at)
                                <span style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; padding: 0.3rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; margin-bottom: 0.5rem;">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Email Verified Member</span>
                                </span>
                            @endif

                            {{-- Action Bar --}}
                            <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">
                                        Login to Send Interest
                                    </a>
                                @else
                                    @if (! auth()->user()->hasVerifiedEmail())
                                        <a href="{{ route('verification.notice') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; color: var(--primary);">
                                            Verify Your Email to Connect
                                        </a>
                                    @elseif (auth()->id() === $profile->user_id)
                                        {{-- Own profile action buttons --}}
                                        <a href="{{ route('member.profile') }}" class="btn btn-primary" style="padding: 0.5rem 1.1rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <svg class="w-3.5 h-3.5 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit Profile</span>
                                        </a>
                                        <a href="{{ route('member.profile.photos') }}" class="btn btn-outline" style="padding: 0.5rem 1.1rem; font-size: 0.85rem; color: var(--primary); border-color: var(--primary); display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <svg class="w-3.5 h-3.5 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>Manage Photos</span>
                                        </a>
                                    @else
                                        {{-- Interest Action --}}
                                        @if ($existingInterest)
                                            @if ($existingInterest->status === 'accepted')
                                                <span style="background: #DEF7EC; color: #03543F; padding: 0.45rem 1.1rem; border-radius: 9999px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                                    <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Connected</span>
                                                </span>
                                            @elseif ($existingInterest->status === 'pending')
                                                @if ($existingInterest->sender_id === auth()->id())
                                                    <span style="background: #FFE4E6; color: var(--primary); padding: 0.45rem 0.9rem; border-radius: 9999px; font-weight: 600; font-size: 0.85rem;">
                                                        Interest Sent (Pending)
                                                    </span>
                                                    <button wire:click="cancelInterest" type="button" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; color: var(--text-muted);">
                                                        Cancel Interest
                                                    </button>
                                                @else
                                                    <button wire:click="acceptInterest" type="button" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        <span>Accept Interest</span>
                                                    </button>
                                                    <button wire:click="rejectInterest" type="button" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem; color: var(--primary); display: inline-flex; align-items: center; gap: 0.4rem;">
                                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        <span>Reject</span>
                                                    </button>
                                                @endif
                                            @elseif ($existingInterest->status === 'rejected')
                                                <span style="background: #FDE8E8; color: #9B1C1C; padding: 0.45rem 1rem; border-radius: 9999px; font-weight: 600; font-size: 0.85rem;">
                                                    Interest Declined
                                                </span>
                                            @elseif ($existingInterest->status === 'cancelled')
                                                <button wire:click="sendInterest" type="button" class="btn btn-primary" style="padding: 0.55rem 1.35rem; font-size: 0.95rem;">
                                                    Send Interest
                                                </button>
                                            @endif
                                        @else
                                            <button wire:click="sendInterest" type="button" class="btn btn-primary" style="padding: 0.55rem 1.35rem; font-size: 0.95rem;">
                                                Send Interest
                                            </button>
                                        @endif

                                        {{-- Message Action Button (Visible on Mutual Match) --}}
                                        @if ($isMutualMatch)
                                            @if (auth()->user()->isPremium())
                                                <a href="{{ $messageUrl ?: route('member.messages.index') }}" class="btn btn-primary" style="padding: 0.65rem 1.6rem; font-size: 0.95rem; font-weight: 700; gap: 0.5rem; box-shadow: 0 4px 14px rgba(225, 29, 72, 0.35); display: inline-flex; align-items: center;">
                                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                    <span>Message Member</span>
                                                </a>
                                            @else
                                                <a href="{{ route('membership.index') }}" class="btn btn-primary" style="padding: 0.65rem 1.6rem; font-size: 0.95rem; font-weight: 700; gap: 0.5rem; box-shadow: 0 4px 14px rgba(225, 29, 72, 0.35); display: inline-flex; align-items: center;" title="Upgrade to Premium to send direct messages">
                                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                    <span>Message Member (Upgrade to VIP)</span>
                                                </a>
                                            @endif
                                        @endif

                                        {{-- Shortlist Toggle Button --}}
                                        <button wire:click="toggleShortlist" type="button" class="btn {{ $isShortlisted ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;" title="{{ $isShortlisted ? 'Remove from shortlist' : 'Add to shortlist' }}">
                                            <svg class="w-3.5 h-3.5 shrink-0 {{ $isShortlisted ? 'fill-white text-white' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                            <span>{{ $isShortlisted ? 'Shortlisted' : 'Shortlist' }}</span>
                                        </button>

                                        {{-- Block / Unblock Button --}}
                                        <button wire:click="toggleBlock" wire:confirm="Are you sure you want to {{ $isBlocked ? 'unblock' : 'block' }} this member?" type="button" class="btn btn-outline" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; color: #9B1C1C; border-color: #F87171; display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            <span>{{ $isBlocked ? 'Unblock Member' : 'Block' }}</span>
                                        </button>

                                        {{-- Report Button --}}
                                        <button wire:click="openReportModal" type="button" class="btn btn-outline" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; color: #D97706; border-color: #FBBF24; display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <svg class="w-3.5 h-3.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                            <span>Report</span>
                                        </button>
                                    @endif
                                @endguest
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 1.25rem;">
                        @if ($profile->age)
                            <span style="background: var(--primary); color: #FFFFFF; padding: 0.35rem 0.9rem; border-radius: 9999px; font-weight: 600; font-size: 0.9rem;">
                                {{ $profile->age }} Years Old
                            </span>
                        @endif
                        @if ($profile->marital_status)
                            <span style="background: var(--primary-light); color: var(--primary); padding: 0.35rem 0.9rem; border-radius: 9999px; font-weight: 600; font-size: 0.9rem;">
                                {{ $profile->marital_status }}
                            </span>
                        @endif
                        @if ($profile->religion)
                            <span style="background: #F3ECE9; color: var(--text-main); padding: 0.35rem 0.9rem; border-radius: 9999px; font-weight: 600; font-size: 0.9rem;">
                                {{ $profile->religion }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Photo Lightbox Modal --}}
            <div x-show="showLightbox" x-cloak @click.away="showLightbox = false" style="position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.85); display: flex; align-items: center; justify-content: center; padding: 1rem;">
                <div style="position: relative; max-width: 90vw; max-height: 90vh;">
                    <button type="button" @click="showLightbox = false" style="position: absolute; top: -40px; right: 0; color: #fff; font-size: 1.8rem; background: none; border: none; cursor: pointer;">
                        &times; Close
                    </button>
                    <img :src="activePhotoUrl || '{{ $profile->photo_url }}'" alt="{{ $profile->full_name }}" style="max-width: 90vw; max-height: 85vh; border-radius: 0.75rem; object-fit: contain; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                </div>
            </div>
        </div>

        {{-- Section B: Basic Information --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                Basic Information
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Gender</span>
                    <strong style="font-size: 1rem; text-transform: capitalize;">{{ $profile->gender ?: 'Not specified' }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Age / Birth Year</span>
                    <strong style="font-size: 1rem;">
                        {{ $profile->age ? $profile->age . ' yrs' : 'Not specified' }} 
                        @if($profile->date_of_birth) ({{ $profile->date_of_birth->format('Y') }}) @endif
                    </strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Religion</span>
                    <strong style="font-size: 1rem;">{{ $profile->religion ?: 'Not specified' }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Marital Status</span>
                    <strong style="font-size: 1rem;">{{ $profile->display_marital_status }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Children Count</span>
                    <strong style="font-size: 1rem;">{{ $profile->children_count }} {{ Str::plural('Child', $profile->children_count) }}</strong>
                </div>
            </div>
        </div>

        {{-- Section C: Personal & Matrimonial Details --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                Personal & Matrimonial Details
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Height</span>
                    <strong style="font-size: 1rem;">{{ $profile->formatted_height ?: 'Not specified' }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Education / Qualification</span>
                    <strong style="font-size: 1rem;">{{ $profile->education ?: 'Not specified' }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Occupation / Profession</span>
                    <strong style="font-size: 1rem;">{{ $profile->occupation ?: 'Not specified' }}</strong>
                </div>
            </div>
        </div>

        {{-- Section D: About Me --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                About Me (নিজের সম্পর্কে)
            </h3>

            @if ($profile->about_me)
                <p style="color: var(--text-main); font-size: 1rem; line-height: 1.7; white-space: pre-line;">
                    {{ $profile->about_me }}
                </p>
            @else
                <p style="color: var(--text-muted); font-style: italic; font-size: 0.95rem;">
                    The member has not written a personal bio statement yet.
                </p>
            @endif
        </div>

        {{-- Section E: Partner Preference & Interest --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem; border-left: 4px solid var(--primary);">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                <span>Partner Preference & Expectations</span>
                <span style="font-size: 0.85rem; font-weight: 500; color: var(--primary);">(কেমন পাত্র / পাত্রী খুঁজছেন)</span>
            </h3>

            @if ($profile->partner_expectation)
                <p style="color: var(--text-main); font-size: 1rem; line-height: 1.7; white-space: pre-line;">
                    {{ $profile->partner_expectation }}
                </p>
            @else
                <p style="color: var(--text-muted); font-style: italic; font-size: 0.95rem;">
                    Specific partner expectations have not been provided yet.
                </p>
            @endif
        </div>

        {{-- Section E: Location --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2.5rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                Location Overview
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">City</span>
                    <strong style="font-size: 1rem;">{{ $profile->city ?: 'Not specified' }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Country</span>
                    <strong style="font-size: 1rem;">{{ $profile->country ?: 'Not specified' }}</strong>
                </div>

                @if ($profile->location)
                    <div>
                        <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Area / Location Details</span>
                        <strong style="font-size: 1rem;">{{ $profile->location }}</strong>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Report Member Modal --}}
    @if ($showReportModal)
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
            <div class="card" style="width: 100%; max-width: 500px; border-radius: 1.25rem; background: #FFFFFF; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--bg-wine); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        <span>Report Profile</span>
                    </h3>
                    <button wire:click="closeReportModal" type="button" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
                </div>

                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
                    Please state the reason for reporting <strong>{{ $profile->full_name }}</strong>. Our moderation team will review this report within 24 hours.
                </p>

                <form wire:submit.prevent="submitReport">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.35rem;">Reason for Report</label>
                        <select wire:model="reportReason" class="form-control" style="width: 100%; padding: 0.6rem; border-radius: 0.5rem; border: 1px solid var(--border-warm);">
                            <option value="Inappropriate Content">Inappropriate Content / Photos</option>
                            <option value="Fake Profile">Fake Profile / Identity Theft</option>
                            <option value="Harassment">Harassment / Abusive Behaviour</option>
                            <option value="Spam / Commercial">Spam / Commercial Solicitation</option>
                            <option value="Underage / Invalid Data">Underage / Invalid Data</option>
                            <option value="Other">Other Reason</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.35rem;">Additional Details (Optional)</label>
                        <textarea wire:model="reportDescription" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border-radius: 0.5rem; border: 1px solid var(--border-warm);" placeholder="Describe the issue in detail..."></textarea>
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                        <button wire:click="closeReportModal" type="button" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem; background: #D97706; border-color: #D97706;">
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
