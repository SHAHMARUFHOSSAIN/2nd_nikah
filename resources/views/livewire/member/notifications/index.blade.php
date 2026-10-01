<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">
    
    {{-- Header Banner --}}
    <x-ui.card padding="spacious" class="bg-gradient-to-br from-rose-50/80 via-pink-50/30 to-white border-rose-200/80">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[11px] font-black tracking-widest text-rose-600 uppercase block">
                    ACCOUNT UPDATES
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Notification Center</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium max-w-2xl">
                    Real-time updates regarding your matrimonial interests, messages, membership alerts, and announcements.
                </p>
            </div>

            @if ($unreadCount > 0)
                <x-ui.button wire:click="markAllAsRead" type="button" variant="soft" size="sm">
                    ✓ Mark All as Read ({{ $unreadCount }})
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>

    {{-- Notifications List Card --}}
    <x-ui.card padding="spacious" class="space-y-4">
        @if ($notifications->isEmpty())
            <x-ui.empty-state icon="🔔" title="No Notifications Yet" description="You currently have no notification updates. We will notify you here when members interact with your profile or when important updates occur." />
        @else
            <div class="space-y-3">
                @foreach ($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = $notification->unread();
                        $type = $data['type'] ?? 'announcement';
                        $title = $data['title'] ?? null;
                        $message = $data['message'] ?? ($data['body'] ?? 'Notification update');
                        $actionLabel = $data['action_label'] ?? null;
                        $actionUrl = $data['action_url'] ?? null;
                    @endphp

                    <div class="p-4 rounded-2xl border transition-all duration-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $isUnread ? 'bg-rose-50/70 border-rose-200/90 shadow-2xs' : 'bg-white border-slate-200/80 hover:bg-slate-50/60' }}">
                        <div class="flex items-start gap-3.5 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-xl bg-white border border-rose-100 flex items-center justify-center text-lg shrink-0 shadow-2xs">
                                @if ($type === 'interest_received') 💖
                                @elseif ($type === 'interest_accepted') 🎉
                                @elseif ($type === 'interest_rejected') ℹ️
                                @elseif ($type === 'membership') 💎
                                @elseif ($type === 'promotion') 🎁
                                @elseif ($type === 'system') ⚙️
                                @elseif ($type === 'security') 🛡️
                                @elseif ($type === 'update') ✨
                                @else 📢 @endif
                            </div>

                            <div class="space-y-1 min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if ($title)
                                        <h3 class="text-sm font-extrabold text-slate-900 leading-tight">
                                            {{ $title }}
                                        </h3>
                                    @endif

                                    @if ($isUnread)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-full bg-rose-600 text-white shadow-2xs">
                                            New
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                                    {{ $message }}
                                </p>

                                <span class="text-[11px] font-bold text-slate-400 block pt-0.5">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            @if ($actionUrl)
                                <x-ui.button :href="$actionUrl" variant="primary" size="sm">
                                    {{ $actionLabel ?: 'View Details' }} &rarr;
                                </x-ui.button>
                            @endif

                            @if ($isUnread)
                                <x-ui.button wire:click="markAsRead('{{ $notification->id }}')" type="button" variant="outline" size="sm">
                                    Mark Read
                                </x-ui.button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 flex justify-center">
                {{ $notifications->links() }}
            </div>
        @endif
    </x-ui.card>

</div>
