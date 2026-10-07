<div class="h-full w-full flex flex-col bg-slate-100/60 overflow-hidden">
    <div class="max-w-7xl w-full mx-auto sm:px-4 lg:px-8 h-full flex flex-col overflow-hidden p-0 sm:py-3 pb-16 md:pb-0">

        {{-- Flash Error Alert --}}
        @if (session()->has('error'))
            <div class="mb-3 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-xl shadow-2xs text-red-700 text-xs sm:text-sm flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Unified Enterprise Application Shell Card --}}
        <div class="bg-white border-0 sm:border border-rose-200/80 rounded-none sm:rounded-3xl shadow-none sm:shadow-lg overflow-hidden flex flex-col lg:flex-row h-full min-h-0 flex-1">

            {{-- Left Pane: Sidebar (Full width on mobile, 390px on desktop) --}}
            <div class="w-full lg:w-[390px] border-r border-rose-100 flex flex-col bg-slate-50/70 p-2.5 sm:p-4 space-y-3.5 shrink-0 flex-1 min-h-0 overflow-y-auto">
                
                {{-- Page Intro Header --}}
                <div class="bg-white border border-rose-100 rounded-2xl p-3 sm:p-4 shadow-2xs space-y-1">
                    <span class="text-[10px] font-black tracking-widest text-rose-600 uppercase block">
                        PRIVATE & SECURE
                    </span>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-tight">
                        Messages & Connections
                    </h1>
                    <p class="text-xs text-slate-500 leading-normal">
                        Private conversations with your verified mutual matches.
                    </p>
                </div>

                {{-- Standalone Premium Upgrade Notice Card (Non-Premium Users) --}}
                @if (! $isPremium)
                    <div class="bg-gradient-to-br from-rose-600 via-rose-700 to-purple-700 text-white border border-rose-400/30 rounded-2xl p-3 sm:p-4 shadow-sm space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="space-y-0.5 min-w-0 flex-1">
                                <h3 class="text-xs sm:text-sm font-black flex items-center gap-1.5 text-white">
                                    <svg class="w-4 h-4 text-amber-300 inline shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z"/></svg>
                                    <span>Premium Messaging</span>
                                </h3>
                                <p class="text-[11px] text-pink-100 leading-snug">
                                    Upgrade to send messages, share images and exchange contact details securely.
                                </p>
                            </div>
                            <a href="{{ route('membership.index') }}" style="white-space: nowrap !important; flex-shrink: 0 !important; color: #BE123C !important; background-color: #FFFFFF !important;" class="btn btn-sm font-black text-xs px-4 py-2 rounded-xl transition shadow-xs hover:bg-rose-50 shrink-0 whitespace-nowrap inline-flex items-center justify-center self-start sm:self-center mt-1 sm:mt-0">
                                Upgrade Now
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Search Conversations Input --}}
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" wire:model.live="search" placeholder="Search conversations..."
                           style="padding-left: 2.25rem !important;"
                           class="w-full text-base sm:text-sm pr-3 py-2.5 bg-white border border-slate-200/90 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition shadow-2xs text-slate-800 placeholder-slate-400">
                </div>

                {{-- Recent Conversations Section --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-2.5 sm:p-3.5 space-y-3" wire:poll.5s>
                    
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <span>Recent Conversations</span>
                            <span class="bg-rose-100 text-rose-700 text-[11px] font-black px-2 py-0.5 rounded-full">
                                {{ $conversations->count() }}
                            </span>
                        </h2>

                        <a href="{{ route('member.blocked.index') }}" class="text-[11px] font-bold text-slate-600 hover:text-rose-600 bg-slate-100 hover:bg-rose-50 border border-slate-200/80 px-2 py-1 rounded-xl transition inline-flex items-center gap-1 shrink-0" title="Manage Blocked Members">
                            <span>🚫 Block List</span>
                            @if ($totalBlockedUsers > 0)
                                <span class="bg-slate-200 text-slate-700 text-[10px] font-black px-1.5 py-0.2 rounded-full">
                                    {{ $totalBlockedUsers }}
                                </span>
                            @endif
                        </a>
                    </div>

                    {{-- Conversation Filter Tabs (All / Active / Blocked) --}}
                    @if ($blockedCount > 0)
                        <div class="flex items-center gap-1 bg-slate-100/90 p-1 rounded-xl text-xs font-bold text-slate-600">
                            <button type="button" wire:click="setFilter('all')" class="flex-1 py-1 px-2 rounded-lg transition text-center text-[11px] {{ $filter === 'all' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'hover:text-slate-900' }}">
                                All ({{ $rawTotalCount }})
                            </button>
                            <button type="button" wire:click="setFilter('active')" class="flex-1 py-1 px-2 rounded-lg transition text-center text-[11px] {{ $filter === 'active' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'hover:text-slate-900' }}">
                                Active ({{ $activeCount }})
                            </button>
                            <button type="button" wire:click="setFilter('blocked')" class="flex-1 py-1 px-2 rounded-lg transition text-center text-[11px] {{ $filter === 'blocked' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'hover:text-slate-900' }}">
                                Blocked ({{ $blockedCount }})
                            </button>
                        </div>
                    @endif

                    {{-- Conversation List --}}
                    @if ($conversations->isEmpty())
                        <div class="py-6 text-center text-slate-400 space-y-2">
                            <div class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 mx-auto shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800">
                                @if ($filter === 'blocked')
                                    No blocked conversations found
                                @else
                                    No conversations yet
                                @endif
                            </h3>
                            <p class="text-[11px] text-slate-500 max-w-xs mx-auto leading-relaxed">
                                @if ($filter === 'blocked')
                                    You have no blocked conversations matching this search.
                                @else
                                    Once you connect with a mutual match, your private conversations will appear here.
                                @endif
                            </p>
                            @if ($filter !== 'blocked')
                                <a href="{{ route('members.index') }}" class="btn btn-primary text-xs font-bold px-3.5 py-1.5 rounded-xl transition shadow-xs mt-1 inline-flex items-center gap-1">
                                    <span>Browse Members</span>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto pr-0.5">
                            @foreach ($conversations as $conv)
                                @php
                                    $partner = $conv->getPartnerUser(auth()->id());
                                    $partnerProfile = $partner?->memberProfile;
                                    $unreadCount = $conv->unreadCountFor(auth()->id());
                                    $lastMsg = $conv->lastMessage;
                                    $isBlockedByMe = $partner && auth()->user()->hasBlocked($partner->id);
                                    $isBlockedByPartner = $partner && auth()->user()->isBlockedBy($partner->id);
                                    $isBlockedEither = $isBlockedByMe || $isBlockedByPartner;
                                @endphp
                                <a href="{{ route('member.messages.show', $conv->id) }}" class="group flex items-center justify-between py-2 px-1.5 rounded-xl transition-all duration-150 gap-2 min-w-0 {{ $unreadCount > 0 ? 'bg-rose-50/60 font-semibold' : 'hover:bg-slate-50' }} {{ $isBlockedEither ? 'opacity-85 bg-slate-50/50' : '' }}">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        
                                        {{-- 40px Avatar --}}
                                        <div class="relative w-10 h-10 rounded-full ring-2 {{ $isBlockedEither ? 'ring-slate-200' : 'ring-rose-100' }} overflow-hidden bg-slate-100 shrink-0 flex items-center justify-center font-bold text-rose-600">
                                            @if ($partnerProfile && $partnerProfile->profile_photo_path)
                                                <img src="{{ Storage::url($partnerProfile->profile_photo_path) }}" alt="{{ $partnerProfile->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs">{{ mb_substr($partnerProfile?->first_name ?: ($partner?->name ?: 'U'), 0, 1) }}</span>
                                            @endif
                                            @if (! $isBlockedEither && $partner && $partner->isOnline())
                                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 ring-2 ring-white rounded-full" title="Online now"></span>
                                            @else
                                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-slate-300 ring-2 ring-white rounded-full" title="Offline"></span>
                                            @endif
                                        </div>

                                        {{-- Partner Details --}}
                                        <div class="min-w-0 flex-1 pr-1">
                                            <div class="flex items-center gap-1 min-w-0">
                                                <h3 class="text-xs font-bold text-slate-900 truncate group-hover:text-rose-600 transition">
                                                    {{ $partnerProfile?->full_name ?: ($partner?->name ?: 'Member') }}
                                                </h3>
                                                @if ($isBlockedByMe)
                                                    <span class="text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.2 rounded font-bold shrink-0">
                                                        Blocked
                                                    </span>
                                                @elseif ($isBlockedByPartner)
                                                    <span class="text-[9px] bg-slate-100 text-slate-500 px-1.5 py-0.2 rounded font-semibold shrink-0">
                                                        Restricted
                                                    </span>
                                                @elseif ($partner && $partner->hasVerifiedEmail())
                                                    <span class="text-[10px] text-blue-600 bg-blue-50 px-1 py-0.5 rounded font-semibold shrink-0 inline-flex items-center" title="Verified Profile">
                                                        <svg class="w-2.5 h-2.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </span>
                                                @endif
                                                @if ($unreadCount > 0)
                                                    <span class="bg-rose-600 text-white text-[10px] font-black px-1.5 py-0.2 rounded-full shadow-2xs shrink-0 ml-auto">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                                @if ($isBlockedByMe)
                                                    <span class="italic text-slate-400">🚫 You blocked this member</span>
                                                @elseif ($isBlockedByPartner)
                                                    <span class="italic text-slate-400">Communication restricted</span>
                                                @elseif ($lastMsg)
                                                    @if ($lastMsg->sender_id === auth()->id())
                                                        <span class="text-slate-700 font-medium">You: </span>
                                                    @endif

                                                    @if ($lastMsg->isDeleted())
                                                        <span class="italic text-slate-400">This message was deleted</span>
                                                    @elseif ($lastMsg->isImage())
                                                        <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Image message</span>
                                                    @elseif ($lastMsg->isWhatsAppRequest())
                                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-medium"><svg class="w-3 h-3 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.301-.15-1.785-.881-2.062-.982-.276-.101-.477-.15-.678.15-.201.301-.779.982-.955 1.183-.176.201-.351.226-.652.075-.301-.15-1.272-.469-2.423-1.496-.895-.798-1.5-1.785-1.676-2.086-.176-.301-.019-.464.131-.613.136-.135.301-.351.452-.527.151-.176.201-.301.301-.502.101-.201.05-.377-.025-.527-.075-.15-.678-1.635-.93-2.238-.244-.587-.492-.507-.678-.517-.176-.01-.377-.01-.577-.01s-.527.075-.803.377c-.276.301-1.054 1.03-1.054 2.512 0 1.481 1.079 2.912 1.229 3.113.15.201 2.124 3.243 5.146 4.549.719.31 1.28.495 1.718.634.722.23 1.379.197 1.899.12.579-.086 1.785-.729 2.036-1.431.251-.703.251-1.305.176-1.431-.075-.126-.276-.201-.577-.352z"/></svg> WhatsApp Request</span>
                                                    @else
                                                        {{ Str::limit($lastMsg->body, 25) }}
                                                    @endif
                                                @else
                                                    <span class="italic">No messages yet</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Time & Chevron --}}
                                    <div class="flex items-center gap-1 shrink-0">
                                        <span class="text-[10px] text-slate-400 whitespace-nowrap">
                                            {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : $conv->created_at->diffForHumans(null, true) }}
                                        </span>
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-600 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Mutual Matches Section --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-2.5 sm:p-3.5 space-y-2.5">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900">
                            Mutual Matches
                        </h2>
                        <p class="text-[11px] text-slate-500">
                            Start a private conversation with someone you've connected with.
                        </p>
                    </div>

                    @if ($matches->isEmpty())
                        <p class="text-xs text-slate-400 py-2 text-center">
                            No mutual accepted matches found. Express interest on profiles to connect!
                        </p>
                    @else
                        <div class="space-y-2 max-h-[300px] overflow-y-auto pr-0.5">
                            @foreach ($matches as $match)
                                @php
                                    $partner = $match->getPartnerUser(auth()->id());
                                    $partnerProfile = $partner?->memberProfile;
                                @endphp
                                @if ($partner)
                                    <div class="p-2 border border-slate-100 rounded-xl hover:border-rose-200 transition flex items-center justify-between gap-2 bg-slate-50/50 min-w-0">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            <div class="w-9 h-9 rounded-full bg-rose-100 overflow-hidden shrink-0 flex items-center justify-center font-bold text-rose-600 text-xs">
                                                @if ($partnerProfile && $partnerProfile->profile_photo_path)
                                                    <img src="{{ Storage::url($partnerProfile->profile_photo_path) }}" alt="{{ $partnerProfile->full_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <span>{{ mb_substr($partnerProfile?->first_name ?: $partner->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 pr-1">
                                                <div class="flex items-center gap-1 min-w-0">
                                                    <h4 class="text-xs font-bold text-slate-900 truncate">
                                                        {{ $partnerProfile?->full_name ?: $partner->name }}
                                                    </h4>
                                                    @if ($partner->hasVerifiedEmail())
                                                        <span class="text-[10px] text-blue-600 bg-blue-50 px-1 py-0.5 rounded font-semibold shrink-0 inline-flex items-center" title="Verified Profile">
                                                            <svg class="w-2.5 h-2.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-slate-400 block truncate">Matched {{ $match->matched_at ? $match->matched_at->format('M d') : '' }}</span>
                                            </div>
                                        </div>
                                        <button wire:click="startConversation({{ $partner->id }})" style="white-space: nowrap !important; flex-shrink: 0 !important;" class="btn btn-primary text-xs font-bold px-3 py-1.5 rounded-xl transition shadow-xs shrink-0 whitespace-nowrap inline-flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            <span>Message</span>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Pane: Enterprise Desktop Chat Workspace Showcase (Hidden on Mobile, Flex Fill on Desktop) --}}
            <div class="hidden lg:flex flex-1 bg-white flex-col items-center justify-center p-8 text-center space-y-6">
                
                <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 shadow-inner mx-auto">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>

                <div class="max-w-md space-y-2">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">
                        Private Matrimonial Inbox
                    </h2>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Select a conversation from the left sidebar or click "Message" on a mutual match to open your confidential chat room.
                    </p>
                </div>

                {{-- Feature Pills --}}
                <div class="grid grid-cols-3 gap-3 w-full max-w-lg pt-4 border-t border-slate-100 text-left">
                    <div class="p-3 bg-slate-50/80 rounded-xl space-y-1">
                        <svg class="w-5 h-5 text-rose-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <h4 class="text-xs font-bold text-slate-900">Mutual Privacy</h4>
                        <p class="text-[10px] text-slate-500">Only connected matches can message.</p>
                    </div>
                    <div class="p-3 bg-slate-50/80 rounded-xl space-y-1">
                        <svg class="w-5 h-5 text-emerald-600 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.301-.15-1.785-.881-2.062-.982-.276-.101-.477-.15-.678.15-.201.301-.779.982-.955 1.183-.176.201-.351.226-.652.075-.301-.15-1.272-.469-2.423-1.496-.895-.798-1.5-1.785-1.676-2.086-.176-.301-.019-.464.131-.613.136-.135.301-.351.452-.527.151-.176.201-.301.301-.502.101-.201.05-.377-.025-.527-.075-.15-.678-1.635-.93-2.238-.244-.587-.492-.507-.678-.517-.176-.01-.377-.01-.577-.01s-.527.075-.803.377c-.276.301-1.054 1.03-1.054 2.512 0 1.481 1.079 2.912 1.229 3.113.15.201 2.124 3.243 5.146 4.549.719.31 1.28.495 1.718.634.722.23 1.379.197 1.899.12.579-.086 1.785-.729 2.036-1.431.251-.703.251-1.305.176-1.431-.075-.126-.276-.201-.577-.352z"/></svg>
                        <h4 class="text-xs font-bold text-slate-900">WhatsApp Sharing</h4>
                        <p class="text-[10px] text-slate-500">Contact details shared upon mutual consent.</p>
                    </div>
                    <div class="p-3 bg-slate-50/80 rounded-xl space-y-1">
                        <svg class="w-5 h-5 text-indigo-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <h4 class="text-xs font-bold text-slate-900">Private Storage</h4>
                        <p class="text-[10px] text-slate-500">Chat attachments stream securely.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
