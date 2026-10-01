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
                                    <span class="text-amber-300">✨</span>
                                    <span>Premium Messaging</span>
                                </h3>
                                <p class="text-[11px] text-pink-100 leading-snug">
                                    Upgrade to send messages, share images and exchange contact details securely.
                                </p>
                            </div>
                            <a href="{{ route('membership.index') }}" style="white-space: nowrap !important; flex-shrink: 0 !important;" class="bg-white hover:bg-rose-50 text-rose-700 font-extrabold text-xs px-3.5 py-1.5 rounded-xl transition shadow-2xs shrink-0 whitespace-nowrap inline-flex items-center justify-center self-start sm:self-center mt-1 sm:mt-0">
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
                           class="w-full text-xs sm:text-sm pr-3 py-2.5 bg-white border border-slate-200/90 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition shadow-2xs text-slate-800 placeholder-slate-400">
                </div>

                {{-- Recent Conversations Section --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-2.5 sm:p-3.5 space-y-3">
                    
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <span>Recent Conversations</span>
                            <span class="bg-rose-100 text-rose-700 text-[11px] font-black px-2 py-0.5 rounded-full">
                                {{ $conversations->count() }}
                            </span>
                        </h2>
                    </div>

                    {{-- Conversation List --}}
                    @if ($conversations->isEmpty())
                        <div class="py-6 text-center text-slate-400 space-y-2">
                            <div class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-xl text-rose-500 mx-auto shadow-inner">
                                💬
                            </div>
                            <h3 class="text-xs font-bold text-slate-800">No conversations yet</h3>
                            <p class="text-[11px] text-slate-500 max-w-xs mx-auto leading-relaxed">
                                Once you connect with a mutual match, your private conversations will appear here.
                            </p>
                            <a href="{{ route('members.index') }}" class="inline-flex items-center gap-1 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3.5 py-1.5 rounded-xl transition shadow-2xs mt-1">
                                <span>Browse Members</span>
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto pr-0.5">
                            @foreach ($conversations as $conv)
                                @php
                                    $partner = $conv->getPartnerUser(auth()->id());
                                    $partnerProfile = $partner?->memberProfile;
                                    $unreadCount = $conv->unreadCountFor(auth()->id());
                                    $lastMsg = $conv->lastMessage;
                                @endphp
                                <a href="{{ route('member.messages.show', $conv->id) }}" class="group flex items-center justify-between py-2 px-1.5 rounded-xl transition-all duration-150 gap-2 min-w-0 {{ $unreadCount > 0 ? 'bg-rose-50/60 font-semibold' : 'hover:bg-slate-50' }}">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        
                                        {{-- 40px Avatar --}}
                                        <div class="relative w-10 h-10 rounded-full ring-2 ring-rose-100 overflow-hidden bg-slate-100 shrink-0 flex items-center justify-center font-bold text-rose-600">
                                            @if ($partnerProfile && $partnerProfile->profile_photo_path)
                                                <img src="{{ Storage::url($partnerProfile->profile_photo_path) }}" alt="{{ $partnerProfile->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs">{{ mb_substr($partnerProfile?->first_name ?: $partner?->name, 0, 1) }}</span>
                                            @endif
                                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 ring-2 ring-white rounded-full"></span>
                                        </div>

                                        {{-- Partner Details --}}
                                        <div class="min-w-0 flex-1 pr-1">
                                            <div class="flex items-center gap-1 min-w-0">
                                                <h3 class="text-xs font-bold text-slate-900 truncate group-hover:text-rose-600 transition">
                                                    {{ $partnerProfile?->full_name ?: $partner?->name }}
                                                </h3>
                                                @if ($partner && $partner->hasVerifiedEmail())
                                                    <span class="text-[10px] text-blue-600 bg-blue-50 px-1 py-0.2 rounded font-semibold shrink-0" title="Verified Profile">✓</span>
                                                @endif
                                                @if ($unreadCount > 0)
                                                    <span class="bg-rose-600 text-white text-[10px] font-black px-1.5 py-0.2 rounded-full shadow-2xs shrink-0 ml-auto">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                                @if ($lastMsg)
                                                    @if ($lastMsg->sender_id === auth()->id())
                                                        <span class="text-slate-700 font-medium">You: </span>
                                                    @endif

                                                    @if ($lastMsg->isDeleted())
                                                        <span class="italic text-slate-400">This message was deleted</span>
                                                    @elseif ($lastMsg->isImage())
                                                        <span>📷 Image message</span>
                                                    @elseif ($lastMsg->isWhatsAppRequest())
                                                        <span>📱 WhatsApp Request</span>
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
                                                        <span class="text-[10px] text-blue-600 bg-blue-50 px-1 py-0.2 rounded font-semibold shrink-0" title="Verified Profile">✓</span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-slate-400 block truncate">Matched {{ $match->matched_at ? $match->matched_at->format('M d') : '' }}</span>
                                            </div>
                                        </div>
                                        <button wire:click="startConversation({{ $partner->id }})" style="white-space: nowrap !important; flex-shrink: 0 !important;" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition shadow-2xs shrink-0 whitespace-nowrap inline-flex items-center justify-center">
                                            Message
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
                
                <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 text-4xl shadow-inner mx-auto">
                    💌
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
                        <span class="text-lg block">🔒</span>
                        <h4 class="text-xs font-bold text-slate-900">Mutual Privacy</h4>
                        <p class="text-[10px] text-slate-500">Only connected matches can message.</p>
                    </div>
                    <div class="p-3 bg-slate-50/80 rounded-xl space-y-1">
                        <span class="text-lg block">📱</span>
                        <h4 class="text-xs font-bold text-slate-900">WhatsApp Sharing</h4>
                        <p class="text-[10px] text-slate-500">Contact details shared upon mutual consent.</p>
                    </div>
                    <div class="p-3 bg-slate-50/80 rounded-xl space-y-1">
                        <span class="text-lg block">🖼️</span>
                        <h4 class="text-xs font-bold text-slate-900">Private Storage</h4>
                        <p class="text-[10px] text-slate-500">Chat attachments stream securely.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
