<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Top Header Card --}}
    <div class="bg-white rounded-3xl border border-rose-100 shadow-xs p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Manage Your Photos</h1>
                <span class="bg-rose-100 text-rose-700 text-xs font-black px-2.5 py-0.5 rounded-full">
                    {{ $currentCount }} / {{ $maxPhotos }} Photos
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Upload up to {{ $maxPhotos }} high-quality photos. Set one primary photo as your main profile avatar.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <x-ui.button :href="route('member.profile')" variant="outline" size="sm">
                ← Back to Profile
            </x-ui.button>
            @if (auth()->user()->memberProfile)
                <x-ui.button :href="route('members.show', auth()->user()->memberProfile->id)" variant="primary" size="sm">
                    View Public Profile 👁️
                </x-ui.button>
            @endif
        </div>
    </div>

    {{-- Alerts --}}
    @if ($errorMessage)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl shadow-2xs text-red-700 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>{{ $errorMessage }}</span>
            </div>
            <button wire:click="$set('errorMessage', null)" class="text-red-500 font-bold">&times;</button>
        </div>
    @endif

    @if ($successMessage)
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-2xl shadow-2xs text-emerald-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', null)" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif

    {{-- Upload Card --}}
    <div class="bg-white rounded-3xl border border-rose-100 shadow-xs p-6 sm:p-8 space-y-4">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span>📷 Add New Photos</span>
            <span class="text-xs text-slate-400 font-normal">(Max 5MB each • JPG, PNG, WEBP)</span>
        </h2>

        @if ($currentCount >= $maxPhotos)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs sm:text-sm text-amber-800 flex items-center gap-2">
                <span class="text-lg">⚠️</span>
                <span>You have reached the maximum photo limit ({{ $maxPhotos }} photos). To upload a new photo, please delete one of your existing gallery photos below.</span>
            </div>
        @else
            <form wire:submit.prevent="savePhotos" class="space-y-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <label for="photo-file-input" class="relative cursor-pointer inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition shadow-2xs">
                        <span>📁 Choose / Take Photos</span>
                        <input id="photo-file-input" type="file" wire:model="newPhotos" multiple accept="image/*" class="sr-only">
                    </label>

                    @if ($newPhotos)
                        <span class="text-xs text-slate-600 font-semibold">
                            {{ count($newPhotos) }} file(s) selected
                        </span>
                    @endif

                    <button type="submit" wire:loading.attr="disabled" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-2xl shadow-xs transition disabled:opacity-50 inline-flex items-center gap-2">
                        <span wire:loading.remove>Upload Photos</span>
                        <span wire:loading>Uploading...</span>
                    </button>
                </div>

                {{-- Selected Files Previews --}}
                @if ($newPhotos)
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 pt-2">
                        @foreach ($newPhotos as $idx => $tempFile)
                            <div class="relative aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                                <img src="{{ $tempFile->temporaryUrl() }}" class="w-full h-full object-cover">
                                <div class="absolute bottom-1 right-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded">
                                    New
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </form>
        @endif
    </div>

    {{-- Photo Gallery Grid Card --}}
    <div class="bg-white rounded-3xl border border-rose-100 shadow-xs p-6 sm:p-8 space-y-6">
        <h2 class="text-base font-bold text-slate-900 flex items-center justify-between">
            <span>🖼️ Your Photo Gallery</span>
            <span class="text-xs text-slate-400 font-normal">Reorder or set primary photo</span>
        </h2>

        @if ($photos->isEmpty())
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200 space-y-2">
                <div class="w-14 h-14 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 text-2xl mx-auto shadow-inner">
                    📷
                </div>
                <h3 class="text-sm font-bold text-slate-800">No photos in gallery yet</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Upload photos above to build your profile gallery. Your primary photo will be displayed on member discovery cards and your public profile.
                </p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($photos as $index => $photo)
                    <div class="bg-slate-50 rounded-2xl border border-slate-200/90 overflow-hidden flex flex-col justify-between shadow-2xs group hover:border-rose-300 transition">
                        
                        {{-- Photo Container --}}
                        <div class="relative aspect-square overflow-hidden bg-slate-200">
                            @if ($photo->url)
                                <img src="{{ $photo->url }}" alt="Gallery photo" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">
                                    File unavailable
                                </div>
                            @endif

                            {{-- Primary Badge --}}
                            @if ($photo->is_primary)
                                <div class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-md flex items-center gap-1">
                                    <span>★</span> PRIMARY
                                </div>
                            @endif

                            {{-- Sort Order Badge --}}
                            <div class="absolute top-2 right-2 bg-slate-900/70 backdrop-blur-xs text-white text-[9.5px] font-bold px-1.5 py-0.5 rounded-full">
                                #{{ $index + 1 }}
                            </div>
                        </div>

                        {{-- Action Controls --}}
                        <div class="p-3 space-y-2 bg-white border-t border-slate-100">
                            {{-- Set Primary Button --}}
                            @if (! $photo->is_primary)
                                <button wire:click="setPrimary({{ $photo->id }})" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold py-1.5 rounded-xl border border-rose-200 transition">
                                    Set as Main Photo
                                </button>
                            @else
                                <div class="w-full text-center text-xs font-bold text-emerald-700 bg-emerald-50 py-1.5 rounded-xl border border-emerald-200">
                                    Main Profile Photo
                                </div>
                            @endif

                            {{-- Reorder & Delete Row --}}
                            <div class="flex items-center justify-between gap-1 pt-0.5">
                                <div class="flex items-center gap-1">
                                    <button wire:click="moveUp({{ $photo->id }})"
                                            @if ($index === 0) disabled @endif
                                            class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 disabled:opacity-30 text-slate-700 text-xs font-bold flex items-center justify-center transition"
                                            title="Move earlier">
                                        ←
                                    </button>
                                    <button wire:click="moveDown({{ $photo->id }})"
                                            @if ($index === $photos->count() - 1) disabled @endif
                                            class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 disabled:opacity-30 text-slate-700 text-xs font-bold flex items-center justify-center transition"
                                            title="Move later">
                                        →
                                    </button>
                                </div>

                                <button wire:click="deletePhoto({{ $photo->id }})"
                                        wire:confirm="Are you sure you want to delete this photo?"
                                        class="text-xs font-bold text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded-lg transition">
                                    Delete
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
