@props([
    'color' => 'slate',
    'size'  => 'md',
])

@php
    $colors = [
        'slate'  => 'bg-slate-100 text-slate-700 border-slate-200',
        'red'    => 'bg-red-100 text-red-700 border-red-200',
        'amber'  => 'bg-amber-100 text-amber-800 border-amber-200',
        'green'  => 'bg-green-100 text-green-700 border-green-200',
        'blue'   => 'bg-blue-100 text-blue-700 border-blue-200',
        'indigo' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
        'primary'=> 'bg-primary/10 text-primary border-primary/20',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-[11px]',
        'md' => 'px-3 py-1 text-xs',
    ];

    $classes = 'inline-flex items-center gap-1.5 rounded-full font-bold border whitespace-nowrap '
        .($colors[$color] ?? $colors['slate']).' '
        .($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->class($classes) }}>
    {{ $slot }}
</span>
