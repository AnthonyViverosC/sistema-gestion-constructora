@props([
    'variant' => 'primary',
    'size'    => 'md',
    'icon'    => null,
    'href'    => null,
    'type'    => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap';

    $variants = [
        'primary'   => 'bg-primary text-white hover:bg-primary/90 focus:ring-primary/30 shadow-sm',
        'secondary' => 'bg-white text-primary border border-primary/15 hover:bg-primary/5 focus:ring-primary/20',
        'ghost'     => 'text-primary/70 hover:text-primary hover:bg-primary/5 focus:ring-primary/20',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300 shadow-sm',
        'success'   => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-300 shadow-sm',
        'amber'     => 'bg-amber-600 text-white hover:bg-amber-700 focus:ring-amber-300 shadow-sm',
        'outline'   => 'bg-transparent text-primary border border-primary/15 hover:bg-primary/5 focus:ring-primary/20',
    ];

    $sizes = [
        'sm' => 'text-xs px-3 py-2',
        'md' => 'text-sm px-4 py-2.5',
        'lg' => 'text-sm px-5 py-3',
    ];

    $classes = trim($base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
