@php
    $rolLabel = match (auth()->user()->rol ?? '') {
        'admin' => 'Administrador',
        'gestor' => 'Gestor',
        'consulta' => 'Consulta',
        default => 'Usuario',
    };
@endphp

<p {{ $attributes->class(['text-primary/50 text-xs font-medium']) }}>{{ $rolLabel }}</p>
