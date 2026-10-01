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
                            <div style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.6); color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                🔍 Click to view
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
                                📍 <span>{{ implode(', ', array_filter([$profile->city, $profile->country])) ?: 'Location Not Specified' }}</span>
                            </p>
                        </div>

                        <div>
                            @if ($profile->user && $profile->user->email_verified_at)
                                <span style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; padding: 0.3rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; margin-bottom: 0.5rem;">
                                    ✓ Email Verified Member
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
                                        <a href="{{ route('member.profile') }}" class="btn btn-primary" style="padding: 0.5rem 1.1rem; font-size: 0.85rem;">
                                            ✏️ Edit Profile
                                        </a>
                                        <a href="{{ route('member.profile.photos') }}" class="btn btn-outline" style="padding: 0.5rem 1.1rem; font-size: 0.85rem; color: var(--primary); border-color: var(--primary);">
                                            📷 Manage Photos
                                        </a>
                                    @else
                                        {{-- Interest Action --}}
                                        @if ($existingInterest)
                                            @if ($existingInterest->status === 'accepted')
                                                <span style="background: #DEF7EC; color: #03543F; padding: 0.45rem 1.1rem; border-radius: 9999px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                                    ✓ Connected
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
                                                    <button wire:click="acceptInterest" type="button" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                                                        ✓ Accept Interest
                                                    </button>
                                                    <button wire:click="rejectInterest" type="button" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem; color: var(--primary);">
                                                        ✕ Reject
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
                                                <a href="{{ $messageUrl ?: route('member.messages.index') }}" class="btn btn-primary" style="padding: 0.5rem 1.1rem; font-size: 0.85rem; background: #059669; border-color: #059669;">
                                                    💬 Message Member
                                                </a>
                                            @else
                                                <a href="{{ route('membership.index') }}" class="btn btn-outline" style="padding: 0.5rem 1.1rem; font-size: 0.85rem; color: #059669; border-color: #059669;" title="Upgrade to Premium to send direct messages">
                                                    💬 Message (Upgrade)
                                                </a>
                                            @endif
                                        @endif

                                        {{-- Shortlist Toggle Button --}}
                                        <button wire:click="toggleShortlist" type="button" class="btn {{ $isShortlisted ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.45rem 0.9rem; font-size: 0.85rem;" title="{{ $isShortlisted ? 'Remove from shortlist' : 'Add to shortlist' }}">
                                            {{ $isShortlisted ? '★ Shortlisted' : '☆ Shortlist' }}
                                        </button>

                                        {{-- Block / Unblock Button --}}
                                        <button wire:click="toggleBlock" wire:confirm="Are you sure you want to {{ $isBlocked ? 'unblock' : 'block' }} this member?" type="button" class="btn btn-outline" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; color: #9B1C1C; border-color: #F87171;">
                                            {{ $isBlocked ? 'Unblock Member' : '🚫 Block' }}
                                        </button>

                                        {{-- Report Button --}}
                                        <button wire:click="openReportModal" type="button" class="btn btn-outline" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; color: #D97706; border-color: #FBBF24;">
                                            🚩 Report
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
                    <strong style="font-size: 1rem;">{{ $profile->marital_status ?: 'Not specified' }}</strong>
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
                About Me
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
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                        🚩 Report Profile
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
