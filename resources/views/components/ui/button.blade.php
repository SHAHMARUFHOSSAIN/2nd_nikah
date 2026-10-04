@props([
    'variant' => 'primary', // primary, secondary, outline, soft, ghost, danger
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
])

@php
    $baseClasses = 'btn inline-flex items-center justify-center font-extrabold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 select-none whitespace-normal sm:whitespace-nowrap text-center max-w-full cursor-pointer text-decoration-none';

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs rounded-xl gap-1.5 min-h-[32px]',
        'lg' => 'px-6 py-3.5 text-base rounded-2xl gap-2.5 min-h-[48px]',
        default => 'px-4 py-2.5 text-sm rounded-xl gap-2 min-h-[40px]',
    };

    $variantClasses = match($variant) {
        'secondary' => 'bg-slate-900 hover:bg-slate-800 text-white shadow-xs focus:ring-slate-900 active:bg-slate-950',
        'outline' => 'bg-white hover:bg-rose-50/60 text-slate-800 hover:text-rose-700 border border-slate-200/90 hover:border-rose-200 shadow-2xs focus:ring-rose-500',
        'soft' => 'bg-rose-50 hover:bg-rose-100/90 text-rose-800 border border-rose-200/80 focus:ring-rose-500',
        'ghost' => 'bg-transparent hover:bg-slate-100 text-slate-700 hover:text-slate-900 focus:ring-slate-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-xs focus:ring-red-600 active:bg-red-800',
        default => 'bg-rose-600 hover:bg-rose-700 text-white shadow-xs hover:shadow-sm focus:ring-rose-600 active:bg-rose-800',
    };

    $disabledClasses = $disabled ? 'opacity-60 cursor-not-allowed pointer-events-none' : '';
    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses} {$disabledClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <span class="shrink-0">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if ($loading)
            <svg class="animate-spin w-4 h-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        @elseif ($icon)
            <span class="shrink-0">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
