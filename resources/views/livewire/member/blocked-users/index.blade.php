<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-6 rounded-3xl border border-rose-100 shadow-2xs">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <a href="{{ route('member.messages.index') }}" class="hover:text-rose-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Messages</span>
                </a>
                <span>/</span>
                <span class="text-rose-600 font-bold">Blocked Members</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>🚫 Blocked Members</span>
                <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2.5 py-0.5 rounded-full border border-slate-200">
                    {{ $blockedRecords->total() }}
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Members you have blocked from messaging or contacting you. You can unblock them at any time.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('member.messages.index') }}" class="btn btn-outline text-xs font-bold px-4 py-2 rounded-xl transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Back to Messages</span>
            </a>
            <a href="{{ route('member.settings.index') }}" class="btn btn-ghost text-xs font-semibold px-3 py-2 rounded-xl text-slate-600 hover:text-slate-900 transition">
                <span>Settings</span>
            </a>
        </div>
    </div>

    {{-- Status Flash --}}
    @if (session()->has('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm p-4 rounded-2xl flex items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    {{-- Blocked List Content --}}
    @if ($blockedRecords->isEmpty())
        <div class="bg-white border border-rose-100 rounded-3xl p-8 sm:p-12 text-center space-y-3 shadow-2xs max-w-lg mx-auto">
            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h2 class="text-base sm:text-lg font-black text-slate-900">No Blocked Members</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xs mx-auto leading-relaxed">
                You haven't blocked any members. When you block someone from chat or profile, they will appear here so you can manage or unblock them.
            </p>
            <div class="pt-2">
                <a href="{{ route('member.messages.index') }}" class="btn btn-primary text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs inline-flex items-center gap-1.5">
                    <span>Go to Messages</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($blockedRecords as $record)
                @php
                    $blockedUser = $record->blocked;
                    $profile = $blockedUser?->memberProfile;
                    $conv = $conversations->get($record->blocked_id);
                @endphp
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col justify-between gap-3 hover:border-rose-200 transition">
                    
                    {{-- User Info --}}
                    <div class="flex items-start gap-3">
                        <div class="relative w-12 h-12 rounded-full overflow-hidden bg-slate-100 ring-2 ring-slate-200 shrink-0 flex items-center justify-center font-bold text-slate-500">
                            @if ($profile && $profile->profile_photo_path)
                                <img src="{{ Storage::url($profile->profile_photo_path) }}" alt="{{ $profile->full_name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-sm">{{ mb_substr($profile?->first_name ?: ($blockedUser?->name ?: 'U'), 0, 1) }}</span>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900 truncate">
                                    {{ $profile?->full_name ?: ($blockedUser?->name ?: 'Member') }}
                                </h3>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-1.5 py-0.5 rounded border border-slate-200">
                                    Blocked
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 mt-0.5 truncate">
                                @if ($profile)
                                    {{ $profile->age ? $profile->age . ' yrs' : '' }}
                                    {{ $profile->occupation ? '• ' . $profile->occupation : '' }}
                                    {{ $profile->present_city ? '• ' . $profile->present_city : '' }}
                                @else
                                    {{ $blockedUser?->email }}
                                @endif
                            </p>

                            <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Blocked {{ $record->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                        <button wire:click="unblock({{ $record->blocked_id }})" wire:loading.attr="disabled" type="button" class="flex-1 btn btn-primary text-xs font-bold py-2 px-3 rounded-xl shadow-2xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Unblock</span>
                        </button>

                        @if ($conv)
                            <a href="{{ route('member.messages.show', $conv->id) }}" class="btn btn-outline text-xs font-semibold py-2 px-3 rounded-xl text-slate-700 hover:text-rose-600 transition inline-flex items-center gap-1" title="View Conversation">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Chat</span>
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pt-4">
            {{ $blockedRecords->links() }}
        </div>
    @endif

</div>
