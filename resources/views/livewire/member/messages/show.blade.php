<div class="h-full w-full flex flex-col bg-slate-100/60 overflow-hidden" x-data="{ showLightbox: false, activeImageUrl: '', copiedToast: false, composerMenuOpen: false }">
    <div class="max-w-7xl w-full mx-auto sm:px-4 lg:px-8 h-full flex flex-col overflow-hidden p-0 sm:py-3">

        {{-- Toast notification for copied text --}}
        <div x-show="copiedToast" x-transition x-cloak class="fixed top-5 right-5 z-50 bg-slate-900 text-white px-4 py-2.5 rounded-xl shadow-2xl text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Copied to clipboard!</span>
        </div>

        {{-- Flash Error Notice --}}
        @if ($errorMessage)
            <div class="mb-2 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-xl shadow-2xs flex items-center justify-between text-red-700 text-xs sm:text-sm shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ $errorMessage }}</span>
                </div>
            </div>
        @endif

        @if ($successMessage)
            <div class="mb-2 bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-xl shadow-2xs flex items-center justify-between text-emerald-800 text-xs sm:text-sm shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ $successMessage }}</span>
                </div>
            </div>
        @endif

        {{-- Unified Enterprise Application Shell Card --}}
        <div class="bg-white border-0 sm:border border-rose-200/80 rounded-none sm:rounded-3xl shadow-none sm:shadow-lg overflow-hidden flex flex-col lg:flex-row h-full min-h-0 flex-1">

            {{-- Left Pane: Sidebar (Desktop Only, 390px width) --}}
            <div class="hidden lg:flex w-[390px] border-r border-rose-100 flex-col bg-slate-50/70 p-4 space-y-4 shrink-0">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <span>Conversations</span>
                            <span class="bg-rose-100 text-rose-700 text-[11px] font-black px-2 py-0.5 rounded-full">
                                {{ isset($conversations) ? $conversations->count() : 1 }}
                            </span>
                        </h2>
                        <a href="{{ route('member.messages.index') }}" class="text-xs text-rose-600 hover:underline font-bold">
                            Back to Inbox
                        </a>
                    </div>

                    {{-- Sidebar Conversations List --}}
                    @if (isset($conversations) && $conversations->isNotEmpty())
                        <div class="divide-y divide-slate-100 max-h-[600px] overflow-y-auto pr-1">
                            @foreach ($conversations as $conv)
                                @php
                                    $pUser = $conv->getPartnerUser(auth()->id());
                                    $pProfile = $pUser?->memberProfile;
                                    $uCount = $conv->unreadCountFor(auth()->id());
                                    $lMsg = $conv->lastMessage;
                                    $isActiveConv = $conv->id === $conversation->id;
                                @endphp
                                <a href="{{ route('member.messages.show', $conv->id) }}" class="group flex items-center justify-between p-2.5 rounded-xl transition-all duration-150 gap-2.5 {{ $isActiveConv ? 'bg-rose-50 border border-rose-200 shadow-2xs' : 'hover:bg-slate-50' }}">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        <div class="w-10 h-10 rounded-full ring-2 ring-rose-100 overflow-hidden bg-slate-100 shrink-0 flex items-center justify-center font-bold text-rose-600 text-xs">
                                            @if ($pProfile && $pProfile->profile_photo_path)
                                                <img src="{{ Storage::url($pProfile->profile_photo_path) }}" alt="{{ $pProfile->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <span>{{ mb_substr($pProfile?->first_name ?: $pUser?->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-1">
                                                <h4 class="text-xs font-bold text-slate-900 truncate {{ $isActiveConv ? 'text-rose-700' : '' }}">
                                                    {{ $pProfile?->full_name ?: $pUser?->name }}
                                                </h4>
                                                @if ($uCount > 0)
                                                    <span class="bg-rose-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full">
                                                        {{ $uCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                                @if ($lMsg)
                                                    {{ Str::limit($lMsg->body, 35) }}
                                                @else
                                                    <span class="italic">No messages</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Pane: Active Chat Room --}}
            <div class="flex-1 flex flex-col bg-white min-w-0 h-full overflow-hidden relative">

                {{-- Compact Mobile & Desktop Chat Room Header (Fixed Top Layer) --}}
                <div class="px-3 sm:px-5 py-2.5 sm:py-3 bg-white border-b border-rose-100 flex items-center justify-between gap-2.5 shrink-0 shadow-2xs relative z-20">
                    
                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        {{-- Back Button --}}
                        <a href="{{ route('member.messages.index') }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-rose-50/80 text-rose-600 hover:bg-rose-600 hover:text-white transition flex items-center justify-center shrink-0" title="Back to Inbox">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </a>

                        {{-- Partner Photo --}}
                        <div class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-full ring-2 ring-rose-100 overflow-hidden bg-slate-100 shrink-0 flex items-center justify-center font-bold text-rose-600">
                            @if ($partnerProfile && $partnerProfile->profile_photo_path)
                                <img src="{{ Storage::url($partnerProfile->profile_photo_path) }}" alt="{{ $partnerProfile->full_name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs sm:text-sm">{{ mb_substr($partnerProfile?->first_name ?: $partner?->name, 0, 1) }}</span>
                            @endif
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 ring-2 ring-white rounded-full"></span>
                        </div>

                        {{-- Member Details Header --}}
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <h2 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                    {{ $partnerProfile?->full_name ?: $partner?->name }}
                                </h2>
                                @if ($partner && $partner->hasVerifiedEmail())
                                    <span class="text-[10px] text-blue-600 bg-blue-50 px-1 py-0.2 rounded font-semibold shrink-0" title="Verified Profile">✓</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 text-[11px] text-slate-500 truncate mt-0.2">
                                @if ($partnerProfile)
                                    <span>{{ $partnerProfile->age ? $partnerProfile->age . ' yrs' : '' }}</span>
                                    @if ($partnerProfile->city)
                                        <span>• {{ $partnerProfile->city }}</span>
                                    @endif
                                @endif
                                <span class="text-emerald-700 font-semibold">• Mutual Match</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions (Profile & More Options) --}}
                    <div class="flex items-center gap-1.5 shrink-0" x-data="{ open: false }">
                        @if ($partnerProfile)
                            <a href="{{ route('members.show', $partnerProfile->id) }}" style="white-space: nowrap !important; flex-shrink: 0 !important;" class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-slate-700 hover:text-rose-700 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 px-2.5 py-1 rounded-lg transition shrink-0 whitespace-nowrap">
                                <span>Profile</span>
                            </a>
                        @endif

                        <button @click="open = !open" @click.away="open = false" class="p-1.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-full transition" title="More options">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-3 top-12 w-48 bg-white border border-slate-200/90 rounded-xl shadow-xl py-1 z-50 divide-y divide-slate-100">
                            @if ($partnerProfile)
                                <a href="{{ route('members.show', $partnerProfile->id) }}" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                                    👤 View Profile
                                </a>
                            @endif
                            <button wire:click="confirmReport" @click="open = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-50 flex items-center gap-2">
                                ⚠️ Report Member
                            </button>
                            <button wire:click="confirmBlock" @click="open = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2">
                                🚫 Block Member
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Messages Thread Body (Exclusive Scroll Area) --}}
                <div class="flex-1 min-h-0 p-3.5 sm:p-5 overflow-y-auto bg-slate-50/40 space-y-3.5 relative z-10" id="chat-messages-container">
                    
                    @if ($messages->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-center p-6 text-slate-400 space-y-2">
                            <div class="w-14 h-14 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 text-3xl shadow-inner mx-auto">
                                💌
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Start Your Private Conversation</h3>
                            <p class="text-xs text-slate-500 max-w-xs leading-relaxed">
                                Exchange respectful messages, discuss matrimonial values, or request contact details securely.
                            </p>
                        </div>
                    @else
                        @foreach ($messages as $msg)
                            @php
                                $isMine = $msg->sender_id === auth()->id();
                                $isFirstUnread = $firstUnreadId && $firstUnreadId === $msg->id;
                            @endphp

                            {{-- Unread Divider Line --}}
                            @if ($isFirstUnread)
                                <div class="flex items-center my-3">
                                    <div class="flex-grow border-t border-rose-200"></div>
                                    <span class="flex-shrink mx-3 text-[10px] font-extrabold text-rose-600 bg-rose-100 border border-rose-200 px-3 py-0.5 rounded-full shadow-2xs">
                                        New Unread Messages
                                    </span>
                                    <div class="flex-grow border-t border-rose-200"></div>
                                </div>
                            @endif

                            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} group relative">
                                
                                {{-- Message Bubble (Max 80% Width on Mobile) --}}
                                <div class="max-w-[84%] sm:max-w-[78%] rounded-2xl px-4 py-2.5 text-xs sm:text-sm leading-relaxed shadow-2xs transition-all relative {{ $isMine ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-tr-xs' : 'bg-white text-slate-800 border border-slate-200/90 rounded-tl-xs' }}">

                                    {{-- Quoted Reply Context --}}
                                    @if ($msg->replyTo)
                                        <div class="mb-1.5 p-2 rounded-lg text-xs {{ $isMine ? 'bg-rose-800/60 text-rose-100 border-l-2 border-white/80' : 'bg-slate-100 text-slate-600 border-l-2 border-rose-500' }}">
                                            <div class="font-bold text-[10px]">
                                                Replying to {{ $msg->replyTo->sender_id === auth()->id() ? 'You' : ($partnerProfile?->first_name ?: 'Partner') }}
                                            </div>
                                            <div class="truncate">
                                                @if ($msg->replyTo->isDeleted())
                                                    <span class="italic">This message was deleted.</span>
                                                @elseif ($msg->replyTo->isImage())
                                                    <span>📷 [Image message]</span>
                                                @else
                                                    {{ $msg->replyTo->body }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Content Display --}}
                                    @if ($msg->isDeleted())
                                        <div class="flex items-center gap-1.5 italic text-slate-400 opacity-90 py-0.5 text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>This message was deleted.</span>
                                        </div>
                                    @elseif ($msg->isImage())
                                        <div class="space-y-1.5">
                                            <div class="overflow-hidden rounded-xl cursor-pointer hover:opacity-95 transition bg-black/5 max-w-xs"
                                                 @click="showLightbox = true; activeImageUrl = '{{ route('member.messages.attachment', $msg->id) }}'">
                                                <img src="{{ route('member.messages.attachment', $msg->id) }}" alt="Chat attachment" class="w-full h-auto max-h-56 object-cover rounded-xl" loading="lazy">
                                            </div>
                                            @if (! empty($msg->body) && $msg->body !== 'Sent an image')
                                                <p class="whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                                            @endif
                                        </div>
                                    @elseif ($msg->isWhatsAppRequest())
                                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-slate-800 space-y-2 my-1">
                                            <div class="flex items-center gap-1.5 font-bold text-emerald-900 text-xs">
                                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.301-.15-1.785-.881-2.062-.982-.276-.101-.477-.15-.678.15-.201.301-.779.982-.955 1.183-.176.201-.351.226-.652.075-.301-.15-1.272-.469-2.423-1.496-.895-.798-1.5-1.785-1.676-2.086-.176-.301-.019-.464.131-.613.136-.135.301-.351.452-.527.151-.176.201-.301.301-.502.101-.201.05-.377-.025-.527-.075-.15-.678-1.635-.93-2.238-.244-.587-.492-.507-.678-.517-.176-.01-.377-.01-.577-.01s-.527.075-.803.377c-.276.301-1.054 1.03-1.054 2.512 0 1.481 1.079 2.912 1.229 3.113.15.201 2.124 3.243 5.146 4.549.719.31 1.28.495 1.718.634.722.23 1.379.197 1.899.12.579-.086 1.785-.729 2.036-1.431.251-.703.251-1.305.176-1.431-.075-.126-.276-.201-.577-.352z"/></svg>
                                                <span>WhatsApp Contact Request</span>
                                            </div>

                                            @if ($whatsappRequest)
                                                @if ($whatsappRequest->isPending())
                                                    @if ($whatsappRequest->receiver_id === auth()->id())
                                                        <p class="text-[11px] text-slate-600 leading-snug">
                                                            Wants to exchange WhatsApp contact details with you.
                                                        </p>
                                                        <div class="flex items-center gap-2 pt-1">
                                                            <button wire:click="acceptWhatsApp({{ $whatsappRequest->id }})" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-2xs transition">
                                                                Accept
                                                            </button>
                                                            <button wire:click="rejectWhatsApp({{ $whatsappRequest->id }})" class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-lg transition">
                                                                Decline
                                                            </button>
                                                        </div>
                                                    @else
                                                        <p class="text-[11px] text-amber-800 leading-snug">
                                                            ⏳ Contact request sent. Waiting for response...
                                                        </p>
                                                        <button wire:click="cancelWhatsApp({{ $whatsappRequest->id }})" class="text-xs text-red-600 hover:text-red-800 underline font-semibold pt-0.5">
                                                            Cancel Request
                                                        </button>
                                                    @endif
                                                @elseif ($whatsappRequest->isAccepted())
                                                    <div class="bg-white p-2 rounded-lg border border-emerald-300 space-y-1">
                                                        <div class="text-xs font-bold text-emerald-900">
                                                            WhatsApp Contact Shared
                                                        </div>
                                                        <div class="text-xs text-slate-800 font-mono bg-slate-50 p-1.5 rounded border border-slate-200 flex items-center justify-between">
                                                            <strong class="text-xs text-slate-900">
                                                                {{ $whatsappRequest->requester_id === auth()->id() ? ($partnerProfile?->phone ?: 'Phone unavailable') : (auth()->user()->memberProfile?->phone ?: 'Phone unavailable') }}
                                                            </strong>
                                                            @if ($partnerProfile?->phone)
                                                                <button @click="navigator.clipboard.writeText('{{ $partnerProfile->phone }}'); copiedToast = true; setTimeout(() => copiedToast = false, 2000)" class="text-[11px] text-emerald-700 hover:text-emerald-900 bg-emerald-100 hover:bg-emerald-200 font-bold px-2 py-0.5 rounded transition">
                                                                    Copy
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @elseif ($whatsappRequest->isRejected())
                                                    <p class="text-xs text-slate-500 italic">WhatsApp request was declined.</p>
                                                @elseif ($whatsappRequest->isCancelled())
                                                    <p class="text-xs text-slate-500 italic">WhatsApp request was cancelled.</p>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <p class="whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                                    @endif

                                    {{-- Message Metadata --}}
                                    <div class="mt-1 flex items-center justify-between gap-2 text-[10px] opacity-80 select-none">
                                        <span>{{ $msg->created_at->format('g:i A') }}</span>
                                        
                                        @if ($isMine)
                                            <div class="flex items-center gap-0.5 font-bold">
                                                @if ($msg->read_at)
                                                    <span class="text-emerald-200" title="Read at {{ $msg->read_at->format('M d, g:i A') }}">
                                                        ✓✓ Read
                                                    </span>
                                                @else
                                                    <span class="text-rose-200" title="Sent">
                                                        ✓ Sent
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                @if (! $msg->isDeleted())
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-150 flex items-center gap-1 mt-1 text-[10px] text-slate-500">
                                        <button wire:click="setReplyTo({{ $msg->id }})" class="hover:text-rose-600 px-1.5 py-0.5 rounded hover:bg-white shadow-2xs transition">
                                            Reply
                                        </button>
                                        <button @click="navigator.clipboard.writeText('{{ addslashes($msg->body) }}'); copiedToast = true; setTimeout(() => copiedToast = false, 2000)" class="hover:text-rose-600 px-1.5 py-0.5 rounded hover:bg-white shadow-2xs transition">
                                            Copy
                                        </button>
                                        @if ($isMine)
                                            <button wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Are you sure you want to delete this message?" class="hover:text-red-600 px-1.5 py-0.5 rounded hover:bg-white shadow-2xs transition">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Composer Input Section (Fixed Bottom Layer) --}}
                <div class="p-2.5 sm:p-3 bg-white border-t border-slate-100 space-y-2 shrink-0 relative z-20">

                    {{-- Quoted Reply Context --}}
                    @if ($replyToMessageId)
                        @php
                            $replyMsg = $messages->firstWhere('id', $replyToMessageId);
                        @endphp
                        @if ($replyMsg)
                            <div class="flex items-center justify-between bg-rose-50 border-l-3 border-rose-500 px-3 py-1.5 rounded-r-lg text-xs">
                                <div class="truncate">
                                    <span class="font-bold text-rose-800 text-[11px]">Replying to {{ $replyMsg->sender_id === auth()->id() ? 'Yourself' : ($partnerProfile?->first_name ?: 'Partner') }}:</span>
                                    <span class="text-slate-600 italic ml-1 truncate text-xs">{{ Str::limit($replyMsg->body, 45) }}</span>
                                </div>
                                <button wire:click="clearReplyTo" class="text-slate-400 hover:text-slate-600 p-0.5 text-sm font-bold">
                                    &times;
                                </button>
                            </div>
                        @endif
                    @endif

                    {{-- Attachment Image Preview --}}
                    @if ($attachment)
                        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 p-2 rounded-lg text-xs">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-9 h-9 rounded bg-slate-200 overflow-hidden shrink-0">
                                    <img src="{{ $attachment->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-800 block truncate max-w-[160px] text-xs">{{ $attachment->getClientOriginalName() }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ round($attachment->getSize() / 1024) }} KB</span>
                                </div>
                            </div>
                            <button wire:click="removeAttachment" class="text-red-500 hover:text-red-700 p-1 font-bold">
                                &times;
                            </button>
                        </div>
                    @endif

                    @if ($isPremium)
                        <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                            
                            {{-- Mobile & Desktop Action Trigger [ + ] Menu --}}
                            <div class="relative shrink-0">
                                <button type="button" @click="composerMenuOpen = !composerMenuOpen" @click.away="composerMenuOpen = false" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Attach media or request details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>

                                <div x-show="composerMenuOpen" x-transition x-cloak class="absolute bottom-12 left-0 w-52 bg-white border border-slate-200/90 rounded-2xl shadow-xl p-1.5 z-40 space-y-1">
                                    <label class="cursor-pointer flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-700 rounded-xl transition">
                                        <input type="file" wire:model="attachment" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" @change="composerMenuOpen = false">
                                        <span>📷 Attach Image (Max 5MB)</span>
                                    </label>
                                    <button type="button" wire:click="requestWhatsApp" @click="composerMenuOpen = false" class="w-full text-left flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 rounded-xl transition">
                                        <span>📱 Request WhatsApp Details</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Text Area Input --}}
                            <div class="flex-1 min-w-0">
                                <textarea wire:model="messageBody" rows="1"
                                          @keydown.enter.prevent="if (!$event.shiftKey) $wire.sendMessage()"
                                          placeholder="Type a message..."
                                          class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200/90 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none resize-none max-h-24 transition text-slate-800 placeholder-slate-400"></textarea>
                            </div>

                            {{-- Send Button --}}
                            <button type="submit" wire:loading.attr="disabled" style="white-space: nowrap !important; flex-shrink: 0 !important;" class="bg-rose-600 hover:bg-rose-700 text-white p-2.5 sm:px-4 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center gap-1.5 shrink-0 whitespace-nowrap inline-flex items-center justify-center disabled:opacity-50" title="Send message">
                                <span wire:loading.remove class="hidden sm:inline">Send</span>
                                <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span wire:loading class="text-[11px]">Sending...</span>
                            </button>
                        </form>
                    @else
                        {{-- Standalone Premium Gate Card for Chat Room --}}
                        <div class="bg-gradient-to-br from-rose-600 via-rose-600 to-purple-700 text-white rounded-2xl p-3.5 sm:p-4 text-center space-y-2 shadow-sm">
                            <div class="flex items-center justify-center gap-1.5 font-black text-xs text-white">
                                <span class="text-amber-300">✨</span>
                                <span>Premium Messaging</span>
                            </div>
                            <p class="text-[11px] text-pink-100 max-w-sm mx-auto leading-tight">
                                Upgrade to send messages, share images and exchange contact details.
                            </p>
                            <a href="{{ route('membership.index') }}" style="white-space: nowrap !important;" class="inline-flex items-center justify-center bg-white hover:bg-rose-50 text-rose-700 font-extrabold text-xs px-4 py-2 rounded-xl shadow-2xs transition">
                                Upgrade to Premium
                            </a>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Lightbox Modal --}}
    <div x-show="showLightbox" x-transition x-cloak class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" @click.self="showLightbox = false">
        <button @click="showLightbox = false" class="absolute top-4 right-4 text-white hover:text-rose-400 p-2 text-2xl font-bold">
            &times;
        </button>
        <img :src="activeImageUrl" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
    </div>

    {{-- Block Confirmation Modal --}}
    @if ($showBlockModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-5 space-y-4 shadow-2xl border border-slate-100">
                <div class="flex items-center gap-2.5 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="text-sm font-bold text-slate-900">Block {{ $partnerProfile?->full_name ?: 'Member' }}?</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Blocking this member will immediately stop further messaging and exclude them from your search results.
                </p>
                <div class="flex items-center justify-end gap-2.5 pt-1">
                    <button wire:click="cancelBlock" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="blockPartner" class="px-3.5 py-1.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-2xs transition">
                        Yes, Block Member
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Report Submission Modal --}}
    @if ($showReportModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-5 space-y-4 shadow-2xl border border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Report Member</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Reason for Report</label>
                        <select wire:model="reportReason" class="w-full text-xs border border-slate-300 rounded-xl p-2 focus:ring-2 focus:ring-rose-500">
                            <option value="Inappropriate behavior">Inappropriate behavior</option>
                            <option value="Fake profile / Impersonation">Fake profile / Impersonation</option>
                            <option value="Harassment / Spam">Harassment / Spam</option>
                            <option value="Solicitation / Commercial">Solicitation / Commercial</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Details (Optional)</label>
                        <textarea wire:model="reportDetails" rows="3" class="w-full text-xs border border-slate-300 rounded-xl p-2 focus:ring-2 focus:ring-rose-500" placeholder="Provide additional details..."></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2.5 pt-1">
                    <button wire:click="cancelReport" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="reportPartner" class="px-3.5 py-1.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-2xs transition">
                        Submit Report
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        const container = document.getElementById('chat-messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        Livewire.hook('morph.updated', ({ component }) => {
            const container = document.getElementById('chat-messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
</script>
