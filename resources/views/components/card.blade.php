@props([
    'title'    => null,
    'subtitle' => null,
    'padded'   => true,
    'as'       => 'div',
])

@php
    $tag = in_array($as, ['div', 'section', 'article', 'aside'], true) ? $as : 'div';
@endphp

<{{ $tag }} {{ $attributes->class([
    'bg-white rounded-xl border border-primary/10 shadow-soft overflow-hidden',
]) }}>
    @if ($title || $subtitle || isset($header))
        <div class="px-6 py-5 border-b border-primary/10 flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                @if ($title)
                    <h3 class="text-lg font-bold text-primary truncate">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="text-sm text-primary/50 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($header)
                <div class="shrink-0 flex items-center gap-2">{{ $header }}</div>
            @endisset
        </div>
    @endif

    <div @class([
        'p-6'         => $padded && ! isset($flush),
        'p-0'         => ! $padded || isset($flush),
    ])>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="px-6 py-4 border-t border-primary/10 bg-primary/[0.02]">
            {{ $footer }}
        </div>
    @endisset
</{{ $tag }}>
