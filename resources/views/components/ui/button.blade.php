@props([
    'type'    => 'button',
    'variant' => 'primary',
    'tag'     => 'button',
])

@php
    $base = 'w-full rounded-lg py-3 text-sm font-semibold transition cursor-pointer flex items-center justify-center gap-3';

    $variants = [
        'primary' => 'bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white',
        'outline' => 'border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

@if ($tag === 'a')
    <a {{ $attributes->merge(['class' => "$base $variantClass"]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => "$base $variantClass"]) }}
    >
        {{ $slot }}
    </button>
@endif
