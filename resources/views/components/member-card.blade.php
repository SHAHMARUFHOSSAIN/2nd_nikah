@props([
    'profile',
    'showActions' => true,
])

<x-ui.member-card :profile="$profile" :show-actions="$showActions" {{ $attributes }} />
