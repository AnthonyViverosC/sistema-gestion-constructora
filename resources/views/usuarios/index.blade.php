@extends('layouts.app')
@section('title', 'Usuarios')

@php
    $filtros = $filtros ?? ['q' => '', 'rol' => ''];
    $editando = isset($usuario) && $usuario;
    $rolesOpciones = ['admin' => 'Administrador', 'gestor' => 'Gestor', 'consulta' => 'Consulta'];
    $rolBadgeColor = fn ($r) => match ($r) {
        'admin'  => 'red',
        'gestor' => 'green',
        default  => 'slate',
    };
@endphp

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Usuarios</h2>
        <p class="text-sm text-primary/50 mt-1">Crea cuentas y asigna roles de acceso al sistema.</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-6 lg:gap-8">
        <x-card title="Usuarios registrados" subtitle="Cuentas activas del sistema." :padded="false">
            <form method="GET" action="{{ route('usuarios.index') }}" id="usuariosFiltroForm"
                class="px-6 py-4 border-b border-primary/10 bg-primary/[0.02] flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-primary/40" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    <input type="search" name="q" id="usuariosFiltroQ" value="{{ $filtros['q'] }}"
                        placeholder="Buscar por nombre o correo..." autocomplete="off"
                        class="w-full pl-9 pr-9 py-2.5 text-sm border border-primary/10 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white transition-colors">
                    <span id="usuariosFiltroSpinner"
                        class="hidden absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 border-2 border-primary/30 border-t-primary rounded-full animate-spin"></span>
                </div>
                <select name="rol" id="usuariosFiltroRol"
                    class="sm:w-44 py-2.5 px-3 text-sm border border-primary/10 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white transition-colors">
                    <option value="">Todos los roles</option>
                    @foreach ($rolesOpciones as $valor => $texto)
                        <option value="{{ $valor }}" @selected($filtros['rol'] === $valor)>{{ $texto }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <noscript>
                        <x-button type="submit">Buscar</x-button>
                    </noscript>
                    @if ($filtros['q'] !== '' || $filtros['rol'] !== '')
                        <x-button variant="secondary" :href="route('usuarios.index')">Limpiar</x-button>
                    @endif
                </div>
            </form>

            <script>
                (function () {
                    const form    = document.getElementById('usuariosFiltroForm');
                    const input   = document.getElementById('usuariosFiltroQ');
                    const select  = document.getElementById('usuariosFiltroRol');
                    const spinner = document.getElementById('usuariosFiltroSpinner');
                    if (!form || !input || !select) return;

                    let timer = null;
                    const submit = () => {
                        if (spinner) spinner.classList.remove('hidden');
                        form.submit();
                    };

                    input.addEventListener('input', () => {
                        clearTimeout(timer);
                        if (spinner) spinner.classList.remove('hidden');
                        timer = setTimeout(submit, 350);
                    });

                    select.addEventListener('change', () => {
                        clearTimeout(timer);
                        submit();
                    });

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            clearTimeout(timer);
                            submit();
                        }
                    });

                    if (input.value !== '') {
                        requestAnimationFrame(() => {
                            input.focus();
                            const len = input.value.length;
                            try { input.setSelectionRange(len, len); } catch (_) {}
                        });
                    }
                })();
            </script>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary/5">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Nombre</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Correo</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Rol</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/5">
                        @forelse ($usuarios as $u)
                            <tr class="hover:bg-primary/[0.02] transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-primary">{{ $u->name }}</td>
                                <td class="px-6 py-4 text-sm text-primary/70">{{ $u->email }}</td>
                                <td class="px-6 py-4">
                                    <x-badge :color="$rolBadgeColor($u->rol)">{{ ucfirst($u->rol) }}</x-badge>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        <a href="{{ route('usuarios.edit', array_merge(['usuario' => $u->id], array_filter($filtros))) }}" title="Editar usuario"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                            <span class="sr-only">Editar</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        @if (auth()->user()->esAdmin() && $u->id !== auth()->id())
                                            <form action="{{ route('usuarios.destroy', $u) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar a {{ addslashes($u->name) }}? Esta acción no se puede deshacer.');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Eliminar usuario"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                                    <span class="sr-only">Eliminar</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2h12a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM5 8a1 1 0 011-1h8a1 1 0 011 1v8a2 2 0 01-2 2H7a2 2 0 01-2-2V8zm3 1a1 1 0 012 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-0 py-0">
                                    @if ($filtros['q'] !== '' || $filtros['rol'] !== '')
                                        <x-empty-state icon="search" title="Sin resultados"
                                            description="No se encontraron usuarios con esos criterios de búsqueda." />
                                    @else
                                        <x-empty-state icon="users" title="Sin usuarios"
                                            description="Crea el primero usando el formulario de la derecha." />
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <form action="{{ $editando ? route('usuarios.update', $usuario) : route('usuarios.store') }}" method="POST" class="h-fit">
            @csrf
            @if ($editando) @method('PUT') @endif

            <x-card :title="$editando ? 'Editar usuario' : 'Agregar usuario'"
                :subtitle="$editando ? 'Actualiza datos y rol del usuario.' : 'Define sus credenciales y rol.'">
                @if ($editando)
                    <x-slot:header>
                        <a href="{{ route('usuarios.index', array_filter($filtros)) }}"
                            class="text-xs font-bold text-primary/60 hover:text-primary transition-colors">Cancelar</a>
                    </x-slot:header>
                @endif

                <div class="space-y-5">
                    <x-form-input name="name" label="Nombre" :value="$editando ? $usuario->name : null" required />
                    <x-form-input name="email" label="Correo" type="email" :value="$editando ? $usuario->email : null" required />
                    <x-form-select name="rol" label="Rol" :options="$rolesOpciones"
                        :value="$editando ? $usuario->rol : 'consulta'" required />
                    <x-form-input name="password" type="password"
                        :label="'Contraseña' . ($editando ? ' (dejar en blanco para no cambiar)' : '')"
                        :required="! $editando" />
                    <x-form-input name="password_confirmation" type="password" label="Confirmar contraseña"
                        :required="! $editando" />
                </div>

                <x-slot:footer>
                    <x-button type="submit" class="w-full">
                        {{ $editando ? 'Guardar cambios' : 'Crear usuario' }}
                    </x-button>
                </x-slot:footer>
            </x-card>
        </form>
    </div>
@endsection
