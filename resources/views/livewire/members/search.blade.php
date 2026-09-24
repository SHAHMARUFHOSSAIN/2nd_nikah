<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    {{-- Page Title Header --}}
    <div style="margin-bottom: 2rem; text-align: center;">
        <h1 style="font-size: 2.25rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.5rem;">
            Discover Matrimonial Members
        </h1>
        <p style="color: var(--text-muted); font-size: 1.05rem; max-width: 600px; margin: 0 auto;">
            Find verified, visible members aligned with your values and criteria for a blessed 2nd Nikah.
        </p>
    </div>

    {{-- Main Search Layout: Filter Sidebar + Results Area --}}
    <div style="display: grid; grid-template-columns: minmax(0, 300px) 1fr; gap: 2rem; align-items: start;">
        
        {{-- FILTER SIDEBAR PANEL --}}
        <div class="card" style="position: sticky; top: 90px; padding: 1.5rem; border-radius: var(--radius-lg); background: #FFFFFF; box-shadow: var(--shadow-md);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-warm); padding-bottom: 0.75rem;">
                <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                    🔍 Filter Members
                </h2>
                <button wire:click="clearFilters" type="button" class="btn btn-outline" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; border-radius: 6px;">
                    Clear Filters
                </button>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.15rem;">
                
                {{-- Gender --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Gender</label>
                    <select wire:model.live="gender" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">
                        <option value="">Any Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                {{-- Age Range --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Age Range (Years)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <input type="number" min="18" max="90" wire:model.live.debounce.300ms="min_age" placeholder="Min (18)" class="form-input" style="padding: 0.5rem 0.6rem; font-size: 0.85rem;">
                        <input type="number" min="18" max="90" wire:model.live.debounce.300ms="max_age" placeholder="Max (70)" class="form-input" style="padding: 0.5rem 0.6rem; font-size: 0.85rem;">
                    </div>
                </div>

                {{-- Religion --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Religion</label>
                    <select wire:model.live="religion" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">
                        <option value="">Any Religion</option>
                        <option value="Islam">Islam</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Christianity">Christianity</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                {{-- Marital Status --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Marital Status</label>
                    <select wire:model.live="marital_status" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">
                        <option value="">Any Marital Status</option>
                        <option value="Never Married">Never Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Single Parent">Single Parent</option>
                    </select>
                </div>

                {{-- City --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">City</label>
                    <input type="text" wire:model.live.debounce.300ms="city" placeholder="e.g. Dhaka, Chittagong" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">
                </div>

                {{-- Country --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Country</label>
                    <input type="text" wire:model.live.debounce.300ms="country" placeholder="e.g. Bangladesh" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">
                </div>

                {{-- Education --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Education</label>
                    <input type="text" wire:model.live.debounce.300ms="education" placeholder="e.g. Masters, Bachelor" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">
                </div>

                {{-- Children --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Children</label>
                    <select wire:model.live="children" class="form-input" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">
                        <option value="">Any</option>
                        <option value="no">No Children</option>
                        <option value="yes">Has Children</option>
                    </select>
                </div>

                {{-- Height Range --}}
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.85rem;">Height Range (cm)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <input type="number" min="100" max="230" wire:model.live.debounce.300ms="min_height" placeholder="Min cm" class="form-input" style="padding: 0.5rem 0.6rem; font-size: 0.85rem;">
                        <input type="number" min="100" max="230" wire:model.live.debounce.300ms="max_height" placeholder="Max cm" class="form-input" style="padding: 0.5rem 0.6rem; font-size: 0.85rem;">
                    </div>
                </div>

            </div>
        </div>

        {{-- RESULTS CONTENT AREA --}}
        <div>
            {{-- Results Top Bar: Count & Sorting --}}
            <div style="display: flex; justify-content: space-between; align-items: center; background: #FFFFFF; border: 1px solid var(--border-warm); padding: 1rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--bg-wine);">
                    Showing <span style="color: var(--primary); font-weight: 700;">{{ $profiles->total() }}</span> {{ Str::plural('member', $profiles->total()) }}
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label for="sort" style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); white-space: nowrap;">
                        Sort by:
                    </label>
                    <select id="sort" wire:model.live="sort" class="form-input" style="padding: 0.4rem 0.75rem; font-size: 0.85rem; width: auto; min-width: 180px;">
                        <option value="newest">Newest Members</option>
                        <option value="oldest">Oldest Members</option>
                        <option value="youngest">Age: Youngest First</option>
                        <option value="oldest_age">Age: Oldest First</option>
                        <option value="height_asc">Height: Shortest First</option>
                        <option value="height_desc">Height: Tallest First</option>
                    </select>
                </div>
            </div>

            {{-- MEMBER CARDS GRID / EMPTY STATE --}}
            @if ($profiles->total() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    @foreach ($profiles as $profile)
                        <x-member-card :profile="$profile" />
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $profiles->links() }}
                </div>
            @else
                {{-- POLISHED EMPTY STATE --}}
                <div class="empty-state" style="margin-top: 1rem;">
                    <div class="empty-state-icon">
                        🔍
                    </div>
                    <h3 class="empty-state-title">No members found</h3>
                    <p class="empty-state-desc">
                        Try adjusting your search filters to discover more members.
                    </p>
                    <div style="margin-top: 1.5rem;">
                        <button wire:click="clearFilters" type="button" class="btn btn-primary">
                            Clear Filters
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
