<div class="container pt-4 pb-24 md:py-10" x-data="{ showFilterModal: false }" @keydown.window.escape="showFilterModal = false">
    {{-- Page Header --}}
    <div class="text-center mb-5 md:mb-8">
        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 border border-rose-200/80 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 shadow-2xs">
            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span>MATRIMONIAL SEARCH</span>
        </span>
        <h1 class="text-2xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-1.5">
            Discover Matrimonial Members
        </h1>
        <p class="text-slate-500 text-xs md:text-base max-w-xl mx-auto leading-relaxed">
            Find verified, visible members aligned with your values and criteria for a blessed 2nd Nikah.
        </p>
    </div>

    {{-- Mobile Filter Action & Quick Selector Bar (Mobile ONLY md:hidden) --}}
    <div class="md:hidden bg-white border border-rose-100/80 rounded-2xl p-2.5 mb-4 shadow-xs">
        <div class="flex items-center gap-2">
            <button type="button" @click="showFilterModal = true" class="btn btn-primary text-xs py-2 px-3.5 flex items-center gap-1.5 font-bold shadow-xs shrink-0 rounded-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span>Filter</span>
                @if ($this->activeFilterCount > 0)
                    <span class="bg-white text-rose-600 text-[10px] font-extrabold px-1.5 py-0.2 rounded-full leading-none">{{ $this->activeFilterCount }}</span>
                @endif
            </button>

            <div class="grid grid-cols-3 gap-1 bg-slate-100/90 p-1 rounded-xl text-xs font-semibold text-center flex-1">
                <button wire:click="$set('gender', '')" type="button" class="py-1.5 rounded-lg transition-all {{ $gender === '' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">All</button>
                <button wire:click="$set('gender', 'male')" type="button" class="py-1.5 rounded-lg transition-all {{ $gender === 'male' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">Male</button>
                <button wire:click="$set('gender', 'female')" type="button" class="py-1.5 rounded-lg transition-all {{ $gender === 'female' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">Female</button>
            </div>
        </div>
    </div>

    {{-- Active Filters Chip Bar (Both Mobile & Desktop) --}}
    @if ($this->activeFilterCount > 0)
        <div class="flex flex-wrap items-center gap-2 mb-4 bg-rose-50/70 border border-rose-100 p-3 rounded-xl">
            <span class="text-xs font-bold text-slate-700 shrink-0">Active Filters:</span>
            
            @if ($gender !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Gender: {{ ucfirst($gender) }}
                    <button wire:click="removeFilter('gender')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($marital_status !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Status: {{ $marital_status }}
                    <button wire:click="removeFilter('marital_status')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($religion !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Religion: {{ $religion }}
                    <button wire:click="removeFilter('religion')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($min_age !== '' || $max_age !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Age: {{ $min_age ?: '18' }}–{{ $max_age ?: '70+' }} yrs
                    <button wire:click="removeFilter('min_age'); $wire.removeFilter('max_age')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($min_height !== '' || $max_height !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Height: {{ $min_height ?: '100' }}–{{ $max_height ?: '230' }} cm
                    <button wire:click="removeFilter('min_height'); $wire.removeFilter('max_height')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($city !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    City: {{ $city }}
                    <button wire:click="removeFilter('city')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($country !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Country: {{ $country }}
                    <button wire:click="removeFilter('country')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($education !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Edu: {{ $education }}
                    <button wire:click="removeFilter('education')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            @if ($children !== '')
                <span class="inline-flex items-center gap-1 bg-white text-rose-600 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-full shadow-2xs">
                    Children: {{ $children === 'no' ? 'No Children' : 'Has Children' }}
                    <button wire:click="removeFilter('children')" type="button" class="text-rose-400 hover:text-rose-700 font-bold ml-1">✕</button>
                </span>
            @endif

            <button wire:click="clearFilters" type="button" class="text-xs font-bold text-slate-500 hover:text-rose-600 underline ml-auto">
                Reset All
            </button>
        </div>
    @endif

    {{-- Layout Grid: Desktop Sidebar + Main Results --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">

        {{-- DESKTOP SIDEBAR FILTER PANEL (Desktop ONLY md:block) --}}
        <div class="hidden md:block card bg-white p-5 rounded-2xl border border-rose-100 shadow-xs sticky top-20">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-rose-100">
                <h2 class="font-bold text-base text-slate-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter Members</span>
                </h2>
                <button wire:click="clearFilters" type="button" class="btn btn-outline text-xs py-1 px-2.5 rounded-lg border-rose-200 text-slate-600 hover:text-rose-600">
                    Clear Filters
                </button>
            </div>

            <div class="space-y-4">
                {{-- Gender --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Gender</label>
                    <select wire:model.live="gender" class="form-control text-xs p-2 rounded-lg">
                        <option value="">Any Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                {{-- Age Range --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Age Range (Years)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" min="18" max="90" wire:model.live.debounce.300ms="min_age" placeholder="Min (18)" class="form-control text-xs p-2 rounded-lg">
                        <input type="number" min="18" max="90" wire:model.live.debounce.300ms="max_age" placeholder="Max (70)" class="form-control text-xs p-2 rounded-lg">
                    </div>
                </div>

                {{-- Religion --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Religion</label>
                    <select wire:model.live="religion" class="form-control text-xs p-2 rounded-lg">
                        <option value="">Any Religion</option>
                        <option value="Islam">Islam</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Christianity">Christianity</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                {{-- Marital Status --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Marital Status</label>
                    <select wire:model.live="marital_status" class="form-control text-xs p-2 rounded-lg">
                        <option value="">Any Marital Status (সকল)</option>
                        <option value="Unmarried">Unmarried (অবিবাহিত)</option>
                        <option value="Married (Seeking 2nd Marriage)">Married - Seeking 2nd Marriage (বিবাহিত - ২য় বিবাহ)</option>
                        <option value="Divorced">Divorced (তালাকপ্রাপ্ত / ডিভোর্সড)</option>
                        <option value="Widowed">Widowed (বিধবা / বিপত্নীক)</option>
                        <option value="Single Parent">Single Parent (সিঙ্গেল প্যারেন্ট)</option>
                    </select>
                </div>

                {{-- City --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">City</label>
                    <input type="text" wire:model.live.debounce.300ms="city" placeholder="e.g. Dhaka, Chittagong" class="form-control text-xs p-2 rounded-lg">
                </div>

                {{-- Country --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Country</label>
                    <input type="text" wire:model.live.debounce.300ms="country" placeholder="e.g. Bangladesh" class="form-control text-xs p-2 rounded-lg">
                </div>

                {{-- Education --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Education</label>
                    <input type="text" wire:model.live.debounce.300ms="education" placeholder="e.g. Masters, Bachelor" class="form-control text-xs p-2 rounded-lg">
                </div>

                {{-- Children --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Children</label>
                    <select wire:model.live="children" class="form-control text-xs p-2 rounded-lg">
                        <option value="">Any</option>
                        <option value="no">No Children</option>
                        <option value="yes">Has Children</option>
                    </select>
                </div>

                {{-- Height Range (cm) --}}
                <div class="form-group mb-0">
                    <label class="form-label text-xs font-bold text-slate-700">Height Range (cm)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" min="100" max="230" wire:model.live.debounce.300ms="min_height" placeholder="Min (cm)" class="form-control text-xs p-2 rounded-lg">
                        <input type="number" min="100" max="230" wire:model.live.debounce.300ms="max_height" placeholder="Max (cm)" class="form-control text-xs p-2 rounded-lg">
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN RESULTS AREA --}}
        <div class="md:col-span-3 space-y-4">
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

            {{-- Results Header Bar: Count & Sorting --}}
            <div class="bg-white border border-rose-100 p-3 rounded-2xl flex flex-row items-center justify-between gap-2 shadow-2xs">
                <div class="text-xs md:text-sm font-bold text-slate-900 whitespace-nowrap">
                    Showing <span class="text-rose-600 font-extrabold text-sm md:text-base">{{ $profiles->total() }}</span> {{ Str::plural('member', $profiles->total()) }}
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <label for="sort" class="text-[11px] md:text-xs font-bold text-slate-500 whitespace-nowrap hidden sm:inline">Sort by:</label>
                    <select id="sort" wire:model.live="sort" class="form-control text-xs py-1.5 px-2.5 rounded-xl border-rose-200 text-slate-700 font-medium">
                        <option value="newest">Newest Members</option>
                        <option value="oldest">Oldest Members</option>
                        <option value="youngest">Age: Youngest</option>
                        <option value="oldest_age">Age: Oldest</option>
                        <option value="height_asc">Height: Shortest</option>
                        <option value="height_desc">Height: Tallest</option>
                    </select>
                </div>
            </div>

            {{-- MEMBER CARDS GRID --}}
            @if ($profiles->total() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach ($profiles as $profile)
                        <x-ui.member-card :profile="$profile" />
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6 flex justify-center">
                    {{ $profiles->links() }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="empty-state bg-white border border-rose-100 p-8 rounded-2xl text-center my-4">
                    <div class="empty-state-icon text-5xl mb-3">🔍</div>
                    <h3 class="empty-state-title font-bold text-lg text-slate-900 mb-1">No members found</h3>
                    <p class="empty-state-desc text-slate-500 text-sm max-w-md mx-auto mb-4">
                        Try adjusting your search filters to discover more members.
                    </p>
                    <button wire:click="clearFilters" type="button" class="btn btn-primary text-xs py-2 px-4 font-bold">
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- MOBILE FILTER MODAL SHEET (Mobile ONLY md:hidden) --}}
    <div x-show="showFilterModal" x-cloak class="md:hidden">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[60]" @click="showFilterModal = false"></div>

        {{-- Slide-up Bottom Sheet Panel --}}
        <div class="fixed inset-x-0 bottom-0 z-[60] bg-white rounded-t-2xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden"
             x-show="showFilterModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-4 border-b border-rose-100 bg-white shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <h2 class="font-extrabold text-base text-slate-900">Filter Members</h2>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="clearFilters" type="button" class="text-xs font-bold text-rose-600 hover:underline">
                        Clear Filters
                    </button>
                    <button type="button" @click="showFilterModal = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold p-1 leading-none" aria-label="Close modal">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <div class="p-4 space-y-4 overflow-y-auto flex-1 min-h-0">
                {{-- 1. Gender Chips --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Gender</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button wire:click="$set('gender', '')" type="button" class="py-2 px-1 text-xs font-bold rounded-xl border transition-all text-center {{ $gender === '' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200' }}">Any</button>
                        <button wire:click="$set('gender', 'male')" type="button" class="py-2 px-1 text-xs font-bold rounded-xl border transition-all text-center {{ $gender === 'male' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200' }}">Male</button>
                        <button wire:click="$set('gender', 'female')" type="button" class="py-2 px-1 text-xs font-bold rounded-xl border transition-all text-center {{ $gender === 'female' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200' }}">Female</button>
                    </div>
                </div>

                {{-- 2. Marital Status Chips --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Marital Status</label>
                    <div class="flex items-center gap-2 overflow-x-auto pb-1.5 pt-0.5 px-0.5">
                        @foreach (['' => 'Any', 'Never Married' => 'Never Married', 'Divorced' => 'Divorced', 'Widowed' => 'Widowed', 'Single Parent' => 'Single Parent'] as $val => $label)
                            <button wire:click="$set('marital_status', '{{ $val }}')" type="button" class="py-1.5 px-3.5 text-xs font-bold rounded-full border transition-all shrink-0 whitespace-nowrap {{ $marital_status === $val ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- 3. Age Range --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Age Range (Years)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" min="18" max="90" wire:model.live.debounce.300ms="min_age" placeholder="Min Age (18)" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                        <input type="number" min="18" max="90" wire:model.live.debounce.300ms="max_age" placeholder="Max Age (70)" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                    </div>
                </div>

                {{-- 4. Religion --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Religion</label>
                    <select wire:model.live="religion" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                        <option value="">Any Religion</option>
                        <option value="Islam">Islam</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Christianity">Christianity</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                {{-- 5. Height Range (cm) --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Height Range (cm)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" min="100" max="230" wire:model.live.debounce.300ms="min_height" placeholder="Min cm (e.g. 150)" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                        <input type="number" min="100" max="230" wire:model.live.debounce.300ms="max_height" placeholder="Max cm (e.g. 185)" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                    </div>
                </div>

                {{-- 6. City & Country --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                        <input type="text" wire:model.live.debounce.300ms="city" placeholder="e.g. Dhaka" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country</label>
                        <input type="text" wire:model.live.debounce.300ms="country" placeholder="e.g. Bangladesh" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                    </div>
                </div>

                {{-- 7. Education & Children --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Education</label>
                        <input type="text" wire:model.live.debounce.300ms="education" placeholder="e.g. Masters" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Children</label>
                        <select wire:model.live="children" class="form-control text-xs p-2.5 rounded-xl border-slate-200">
                            <option value="">Any</option>
                            <option value="no">No Children</option>
                            <option value="yes">Has Children</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Modal Footer (Fixed at bottom above mobile bottom nav) --}}
            <div class="p-4 pb-20 border-t border-rose-100 bg-white flex gap-3 shrink-0 shadow-lg">
                <button wire:click="clearFilters" type="button" class="btn btn-outline flex-1 text-xs py-3 font-bold border-rose-200 text-slate-700">
                    Reset All
                </button>
                <button type="button" @click="showFilterModal = false" class="btn btn-primary flex-1 text-xs py-3 font-bold shadow-md">
                    Apply & View ({{ $profiles->total() }})
                </button>
            </div>
        </div>
    </div>
</div>
