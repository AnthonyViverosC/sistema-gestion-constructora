@props([
    'name',
    'label'    => null,
    'type'     => 'text',
    'value'    => null,
    'help'     => null,
    'required' => false,
])

@php
    $id = $attributes->get('id') ?? $name;
    $hasError = $errors->has($name);
    $val = old($name, $value);
@endphp

<div class="space-y-2">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-primary">
            {{ $label }}
            @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $val }}"
        @if ($required) required @endif
        {{ $attributes->class([
            'w-full rounded-lg border px-4 py-2.5 text-sm bg-white outline-none transition-colors',
            'border-primary/10 focus:ring-2 focus:ring-primary/20 focus:border-primary' => ! $hasError,
            'border-red-300 focus:ring-2 focus:ring-red-200 focus:border-red-500'        => $hasError,
        ])->except(['id']) }}
    >

    @if ($hasError)
        <p class="text-xs text-red-600">{{ $errors->first($name) }}</p>
    @elseif ($help)
        <p class="text-xs text-primary/50">{{ $help }}</p>
    @endif
</div>
