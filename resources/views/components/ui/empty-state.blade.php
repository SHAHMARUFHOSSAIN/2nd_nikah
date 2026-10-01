@props([
    'icon' => '💬',
    'title' => 'No Data Found',
    'description' => 'There are no items to display right now.',
    'actionHref' => null,
    'actionText' => null,
])

<div {{ $attributes->merge(['class' => 'py-10 px-4 text-center bg-white rounded-3xl border border-slate-200/80 shadow-2xs space-y-3 max-w-md mx-auto my-6']) }}>
    <div class="w-16 h-16 bg-rose-50/80 rounded-2xl flex items-center justify-center text-2xl text-rose-600 mx-auto shadow-inner border border-rose-100">
        {{ $icon }}
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
