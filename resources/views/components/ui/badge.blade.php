@props([
    'variant' => 'default', // default, success, warning, danger, info, pink
    'size' => 'md', // sm, md
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-[10px]',
        default => 'px-2.5 py-1 text-xs',
    };

    $variantClasses = match($variant) {
        'success', 'accepted', 'matched', 'active' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/80 font-bold',
        'warning', 'pending' => 'bg-amber-50 text-amber-800 border border-amber-200/80 font-bold',
        'danger', 'rejected', 'cancelled', 'expired' => 'bg-red-50 text-red-700 border border-red-200/80 font-bold',
        'info' => 'bg-blue-50 text-blue-700 border border-blue-200/80 font-bold',
        'pink' => 'bg-rose-50 text-rose-700 border border-rose-200/80 font-bold',
        default => 'bg-slate-100 text-slate-700 border border-slate-200/80 font-semibold',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full whitespace-nowrap shrink-0 {$sizeClasses} {$variantClasses}"]) }}>
    {{ $slot }}
</span>
