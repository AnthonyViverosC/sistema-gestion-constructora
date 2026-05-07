@props([
    'title'       => 'Sin resultados',
    'description' => null,
    'icon'        => 'inbox',
])

@php
    $icons = [
        'inbox'    => '<path d="M4 13h4l2 3h4l2-3h4M4 13V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v6M4 13v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4" stroke-linecap="round" stroke-linejoin="round" />',
        'search'   => '<circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" stroke-linecap="round" />',
        'users'    => '<circle cx="9" cy="9" r="3.5" /><path d="M3.5 19a5.5 5.5 0 0 1 11 0M16 11a2.5 2.5 0 1 0 0-5M19.5 19a4 4 0 0 0-2.5-3.7" stroke-linecap="round" />',
        'document' => '<path d="M7 3.5h7l3 3V20a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1Z" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 3.5V7h3" stroke-linecap="round" />',
        'task'     => '<path d="M8.5 6.5h10M8.5 12h10M8.5 17.5h10" stroke-linecap="round" /><path d="m4.5 6.5.8.8 1.7-1.8M4.5 12l.8.8L7 11M4.5 17.5l.8.8 1.7-1.8" stroke-linecap="round" stroke-linejoin="round" />',
    ];
    $svg = $icons[$icon] ?? $icons['inbox'];
@endphp

<div {{ $attributes->class(['flex flex-col items-center justify-center text-center py-12 px-4']) }}>
    <div class="mb-4 size-14 rounded-full bg-primary/5 flex items-center justify-center text-primary/40">
        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            {!! $svg !!}
        </svg>
    </div>
    <h3 class="text-sm font-bold text-primary">{{ $title }}</h3>
    @if ($description)
        <p class="text-sm text-primary/50 mt-1 max-w-sm">{{ $description }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
