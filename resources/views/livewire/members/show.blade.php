<div class="container">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- Navigation Back Button --}}
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('members.index') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">
                &larr; Back to Members Directory
            </a>
        </div>

        {{-- Section A: Profile Header Card --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem; background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F7 100%); overflow: hidden;">
            <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
                
                {{-- Profile Photo --}}
                <div style="width: 160px; height: 160px; border-radius: 1.25rem; overflow: hidden; background: var(--bg-warm); border: 2px dashed var(--border-warm); display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative;">
                    @if ($profile->photo_url)
                        <img src="{{ $profile->photo_url }}" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="text-align: center; color: var(--primary); font-family: var(--font-heading);">
                            <div style="font-size: 3.5rem; font-weight: 700; opacity: 0.8; line-height: 1;">
                                {{ strtoupper(substr($profile->first_name ?: ($profile->user->name ?? 'M'), 0, 1)) }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Header Details --}}
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
                                <span style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                                    ✓ Email Verified Member
                                </span>
                            @endif
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
</div>
