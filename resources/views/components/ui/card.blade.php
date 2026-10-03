@props([
    'padding' => 'normal', // none, compact, normal, spacious
    'hover' => false,
    'border' => true,
    'overflow' => 'hidden', // hidden, visible
])

@php
    $paddingClasses = match($padding) {
        'none' => 'p-0',
        'compact' => 'p-3 sm:p-4',
        'spacious' => 'p-6 sm:p-8',
        default => 'p-4 sm:p-6',
    };

    $borderClasses = $border ? 'border border-rose-100/90' : '';
    $hoverClasses = $hover ? 'hover:shadow-md hover:border-rose-200 hover:-translate-y-0.5 transition-all duration-200' : '';
    $overflowClass = $overflow === 'visible' ? 'overflow-visible' : 'overflow-hidden';
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-2xl sm:rounded-3xl shadow-xs {$borderClasses} {$paddingClasses} {$hoverClasses} {$overflowClass}"]) }}>
    {{ $slot }}
</div>
