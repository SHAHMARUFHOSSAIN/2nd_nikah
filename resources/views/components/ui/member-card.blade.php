@props([
    'profile',
    'showActions' => true,
])

@php
    $user = $profile->user;
    $photoUrl = $profile->photo_url;
    $isVerified = $user && $user->hasVerifiedEmail();

    $auth = auth()->user();
    $authId = auth()->id();
    $targetUserId = $profile->user_id;

    $isGuest = ! auth()->check();
    $isSelf = $authId && $authId === $targetUserId;
    $isAdmin = ($auth?->is_admin) || ($user?->is_admin);

    $isShortlisted = false;
    $isBlocked = false;
    $isMatched = false;
    $hasSentInterest = false;
    $hasReceivedInterest = false;
    $canMessage = false;
    $isPremium = false;
    $messageRoute = route('member.messages.index');

    if ($auth && ! $isSelf && ! $isAdmin) {
        $isShortlisted = $auth->hasShortlisted($targetUserId);
        $isBlocked = $auth->hasBlockedOrIsBlockedBy($targetUserId);

        if (! $isBlocked) {
            $userOne = min($authId, $targetUserId);
            $userTwo = max($authId, $targetUserId);

            $match = \App\Models\UserMatch::where('user_one_id', $userOne)
                ->where('user_two_id', $userTwo)
                ->first();

            $isMatched = (bool) $match;
            $isPremium = $auth->isPremium();

            if ($isMatched) {
                $canMessage = true;
                $conversation = \App\Models\Conversation::where('user_one_id', $userOne)
                    ->where('user_two_id', $userTwo)
                    ->first();

                if ($conversation) {
                    $messageRoute = route('member.messages.show', $conversation->id);
                }
            } else {
                $activeInterest = \App\Models\Interest::where(function ($q) use ($authId, $targetUserId) {
                    $q->where('sender_id', $authId)->where('receiver_id', $targetUserId);
                })->orWhere(function ($q) use ($authId, $targetUserId) {
                    $q->where('sender_id', $targetUserId)->where('receiver_id', $authId);
                })->latest()->first();

                if ($activeInterest && $activeInterest->status === 'pending') {
                    if ($activeInterest->sender_id === $authId) {
                        $hasSentInterest = true;
                    } elseif ($activeInterest->receiver_id === $authId) {
                        $hasReceivedInterest = true;
                    }
                }
            }
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl sm:rounded-3xl border border-rose-100/90 shadow-2xs hover:shadow-md hover:border-rose-200/90 transition-all duration-200 overflow-hidden flex flex-col group h-full']) }}>
    
    {{-- Card Image Header --}}
    <div class="relative aspect-[4/3.5] sm:aspect-[4/3] bg-slate-100 overflow-hidden shrink-0">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $profile->full_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-rose-100 to-pink-50 text-rose-500 font-black text-3xl">
                {{ mb_substr($profile->first_name ?: ($user?->name ?: 'M'), 0, 1) }}
            </div>
        @endif

        {{-- Verification Badge Overlay --}}
        @if ($isVerified)
            <div class="absolute top-2.5 left-2.5 z-10">
                <x-ui.verification-badge show-text />
            </div>
        @endif

        {{-- Shortlist Floating Button (Top-Right of Photo) --}}
        @if (! $isSelf && ! $isAdmin && ! $isBlocked)
            <div class="absolute top-2.5 right-2.5 z-10">
                @if ($isGuest)
                    <a href="{{ route('login') }}" class="w-8 h-8 rounded-full bg-white/90 hover:bg-white backdrop-blur-xs text-slate-400 hover:text-amber-500 flex items-center justify-center shadow-xs transition border border-white/60" title="Log in to shortlist member" aria-label="Shortlist {{ $profile->full_name }}">
                        <svg class="w-4 h-4 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </a>
                @else
                    <button type="button" wire:click="toggleShortlist({{ $targetUserId }})" class="w-8 h-8 rounded-full {{ $isShortlisted ? 'bg-amber-50 text-amber-500 border-amber-300' : 'bg-white/90 hover:bg-white text-slate-400 hover:text-amber-500 border-white/60' }} backdrop-blur-xs flex items-center justify-center shadow-xs transition border cursor-pointer hover:scale-105" title="{{ $isShortlisted ? 'Remove from shortlist' : 'Add to shortlist' }}" aria-label="{{ $isShortlisted ? 'Remove from shortlist' : 'Shortlist' }}">
                        <svg class="w-4 h-4 {{ $isShortlisted ? 'fill-amber-400 text-amber-500' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </button>
                @endif
            </div>
        @endif

        {{-- Age & Location Overlay Pill --}}
        <div class="absolute bottom-2.5 left-2.5 z-10 bg-slate-900/80 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-xs" style="color: #FFFFFF !important;">
            @if ($profile->age)
                <span>{{ $profile->age }} yrs</span>
            @endif
            @if ($profile->city)
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3 text-rose-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    <span>{{ $profile->city }}</span>
                </span>
            @endif
        </div>
    </div>

    {{-- Details Body --}}
    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
        <div class="space-y-1">
            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 group-hover:text-rose-600 transition truncate leading-tight">
                {{ $profile->full_name ?: ($user?->name ?: 'Member Profile') }}
            </h3>
            
            <div class="flex flex-wrap gap-1.5 text-xs text-slate-500 font-medium">
                @if ($profile->marital_status)
                    <span class="bg-slate-100 px-2 py-0.5 rounded-md text-[11px]">{{ $profile->marital_status }}</span>
                @endif
                @if ($profile->religion)
                    <span class="bg-slate-100 px-2 py-0.5 rounded-md text-[11px]">{{ $profile->religion }}</span>
                @endif
                @if ($profile->occupation)
                    <span class="bg-slate-100 px-2 py-0.5 rounded-md text-[11px] truncate max-w-[140px]">{{ $profile->occupation }}</span>
                @endif
            </div>
        </div>

        {{-- Actions Footer --}}
        @if ($showActions)
            <div class="pt-3 border-t border-slate-100 flex items-center gap-2 mt-auto shrink-0 w-full">
                {{-- View Profile Button --}}
                <a href="{{ route('members.show', $profile->id) }}" class="btn btn-outline text-xs font-bold py-2 px-2.5 flex-1 justify-center gap-1 min-w-0" title="View Member Profile" aria-label="View {{ $profile->full_name }}'s profile">
                    <span class="truncate">View Profile</span>
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>

                @if (! $isSelf && ! $isAdmin && ! $isBlocked)
                    @if ($canMessage)
                        @if ($isPremium)
                            {{-- Active Message Action - Prominent Primary Highlight --}}
                            <a href="{{ $messageRoute }}" class="btn btn-primary text-xs font-bold py-2 px-2.5 flex-1 justify-center gap-1.5 min-w-0 shadow-sm" title="Send Message" aria-label="Message {{ $profile->full_name }}">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span class="truncate">Message</span>
                            </a>
                        @else
                            {{-- Premium Protected Message Action - Prominent Highlight with VIP badge --}}
                            <a href="{{ route('membership.index') }}" class="btn btn-primary text-xs font-bold py-2 px-2 flex-1 justify-center gap-1 min-w-0 shadow-sm" title="Upgrade to Premium to Message" aria-label="Message {{ $profile->full_name }} (VIP Required)">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span class="truncate">Message</span>
                                <span class="text-[9px] bg-amber-400 text-slate-900 font-extrabold px-1 py-0.2 rounded-full uppercase leading-none shrink-0">VIP</span>
                            </a>
                        @endif
                    @elseif ($hasSentInterest)
                        {{-- Interest Sent State --}}
                        <span class="btn bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold py-2 px-2.5 flex-1 justify-center gap-1 cursor-default min-w-0" title="Interest Request Sent" aria-label="Interest sent to {{ $profile->full_name }}">
                            <svg class="w-3.5 h-3.5 text-emerald-600 fill-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span class="truncate">Sent</span>
                        </span>
                    @elseif ($hasReceivedInterest)
                        {{-- Interest Received State --}}
                        <a href="{{ route('members.show', $profile->id) }}" class="btn btn-primary text-xs font-bold py-2 px-2.5 flex-1 justify-center gap-1 min-w-0" title="View Interest Request" aria-label="Respond to {{ $profile->full_name }}'s interest">
                            <svg class="w-3.5 h-3.5 text-white fill-white shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span class="truncate">Respond</span>
                        </a>
                    @elseif ($isGuest)
                        {{-- Guest Interest Action --}}
                        <a href="{{ route('login') }}" class="btn btn-primary text-xs font-bold py-2 px-2.5 flex-1 justify-center gap-1 min-w-0" title="Log in to send interest" aria-label="Send Interest to {{ $profile->full_name }}">
                            <svg class="w-3.5 h-3.5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="truncate">Interest</span>
                        </a>
                    @else
                        {{-- Authenticated Interest Action --}}
                        <button type="button" wire:click="sendInterest({{ $targetUserId }})" wire:loading.attr="disabled" class="btn btn-primary text-xs font-bold py-2 px-2.5 flex-1 justify-center gap-1 min-w-0" title="Send Interest Proposal" aria-label="Send Interest to {{ $profile->full_name }}">
                            <svg class="w-3.5 h-3.5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="truncate">Interest</span>
                        </button>
                    @endif
                @endif
            </div>
        @endif
    </div>

</div>
