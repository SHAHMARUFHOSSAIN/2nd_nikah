<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">
    
    {{-- Page Header --}}
    <x-ui.card padding="spacious" class="bg-gradient-to-br from-rose-50/80 via-pink-50/30 to-white border-rose-200/80">
        <div class="space-y-1">
            <span class="text-[11px] font-black tracking-widest text-rose-600 uppercase block">
                MATRIMONIAL DIRECTORY
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Discover Verified Members</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium max-w-2xl">
                Browse verified, active matrimonial profiles seeking a dignified partnership on {{ \App\Models\Setting::get('site_name', '2nd Nikah') }}
            </p>
        </div>
    </x-ui.card>

    {{-- Feedback Messages --}}
    @if (session()->has('message') || session()->has('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl flex items-center gap-2 shadow-2xs">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') ?? session('status') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl flex items-center gap-2 shadow-2xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs sm:text-sm font-bold px-4 py-3 rounded-2xl flex items-center gap-2 shadow-2xs">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    {{-- Members Grid or Empty State --}}
    @if ($members->isEmpty())
        <x-ui.empty-state icon="👥" title="No verified profiles are currently available" description="There are currently no verified public member profiles matching discoverability criteria. Check back soon!" :action-href="auth()->check() ? null : route('register')" :action-text="auth()->check() ? null : 'Create Account'" />
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($members as $profile)
                <x-ui.member-card :profile="$profile" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center pt-4">
            {{ $members->links() }}
        </div>
    @endif
</div>
