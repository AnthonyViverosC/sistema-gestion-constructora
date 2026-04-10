@php
    $rolLabel = match (auth()->user()->rol ?? '') {
        'admin' => 'Administrador',
        'gestor' => 'Gestor',
        'consulta' => 'Consulta',
        default => 'Usuario',
    };
@endphp

<p class="text-primary/50 text-xs font-medium mt-2 pl-11">{{ $rolLabel }}</p>



