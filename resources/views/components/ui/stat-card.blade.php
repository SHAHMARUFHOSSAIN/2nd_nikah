@props([
    'title' => '',
    'value' => '0',
    'subtitle' => null,
    'icon' => '📊',
    'badge' => null,
    'href' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl sm:rounded-3xl border border-rose-100/90 p-4 sm:p-5 shadow-2xs hover:shadow-md hover:border-rose-200 transition-all duration-200 space-y-2 group']) }}>
    <div class="flex items-center justify-between gap-2">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block group-hover:text-rose-600 transition">{{ $title }}</span>
        <div class="w-9 h-9 rounded-xl bg-rose-50/80 border border-rose-100 flex items-center justify-center text-lg shrink-0">
            {{ $icon }}
        </div>
    </div>
    <div class="flex items-baseline justify-between gap-2">
        <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-none">{{ $value }}</span>
        @if ($badge)
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">{{ $badge }}</span>
        @endif
    </div>
    @if ($subtitle)
        <span class="text-xs font-medium text-slate-500 block">{{ $subtitle }}</span>
    @endif
    @if ($href)
        <a href="{{ $href }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1 pt-1">
            <span>View details</span>
            <span>&rarr;</span>
        </a>
    @endif
</div>
