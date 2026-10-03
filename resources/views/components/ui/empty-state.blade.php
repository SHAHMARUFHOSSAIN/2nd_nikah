@props([
    'icon' => '💬',
    'title' => 'No Data Found',
    'description' => 'There are no items to display right now.',
    'actionHref' => null,
    'actionText' => null,
])

<div {{ $attributes->merge(['class' => 'py-10 px-4 text-center bg-white rounded-3xl border border-slate-200/80 shadow-2xs space-y-3 max-w-md mx-auto my-6']) }}>
    <div class="w-16 h-16 bg-rose-50/80 rounded-2xl flex items-center justify-center text-rose-600 mx-auto shadow-inner border border-rose-100">
        @if ($icon && str_contains($icon, '<svg'))
            {!! $icon !!}
        @else
            <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        @endif
    </div>
    <div class="space-y-1">
        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">{{ $title }}</h3>
        <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">{{ $description }}</p>
    </div>
    @if ($actionHref && $actionText)
        <div class="pt-2">
            <x-ui.button :href="$actionHref" variant="primary" size="sm">
                {{ $actionText }}
            </x-ui.button>
        </div>
    @endif
</div>
