@extends('layouts.app')
@section('title', 'Usuarios')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Usuarios</h2>
        <p class="text-sm text-primary/50 mt-1">Crea cuentas y asigna roles de acceso al sistema.</p>
    </div>
@endsection

@section('content')
<div class="space-y-8">
                <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-8">
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Usuarios registrados</h3>
                            <p class="text-sm text-primary/50 mt-1">Cuentas activas del sistema.</p>
                        </div>

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
                                                @php
                                                    $badge = match ($u->rol) {
                                                        'admin' => 'bg-red-100 text-red-700 border-red-200',
                                                        'gestor' => 'bg-green-100 text-green-700 border-green-200',
                                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                    {{ ucfirst($u->rol) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                                    <a href="{{ route('usuarios.edit', $u) }}" title="Editar usuario"
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
                                            <td colspan="4" class="px-6 py-12 text-center text-sm text-primary/40">
                                                No hay usuarios registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @php $editando = isset($usuario) && $usuario; @endphp
                    <form action="{{ $editando ? route('usuarios.update', $usuario) : route('usuarios.store') }}" method="POST"
                        class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden h-fit">
                        @csrf
                        @if ($editando) @method('PUT') @endif

                        <div class="px-6 py-5 border-b border-primary/10 flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-primary">{{ $editando ? 'Editar usuario' : 'Agregar usuario' }}</h3>
                                <p class="text-sm text-primary/50 mt-1">
                                    {{ $editando ? 'Actualiza datos y rol del usuario.' : 'Define sus credenciales y rol.' }}
                                </p>
                            </div>
                            @if ($editando)
                                <a href="{{ route('usuarios.index') }}" class="text-xs font-bold text-primary/60 hover:text-primary">Cancelar</a>
                            @endif
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $editando ? $usuario->name : '') }}"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Correo</label>
                                <input type="email" name="email" value="{{ old('email', $editando ? $usuario->email : '') }}"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Rol</label>
                                <select name="rol"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                                    @foreach (['admin' => 'Administrador', 'gestor' => 'Gestor', 'consulta' => 'Consulta'] as $valor => $texto)
                                        <option value="{{ $valor }}" @selected(old('rol', $editando ? $usuario->rol : 'consulta') === $valor)>{{ $texto }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">
                                    Contraseña {{ $editando ? '(dejar en blanco para no cambiar)' : '' }}
                                </label>
                                <input type="password" name="password"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    {{ $editando ? '' : 'required' }}>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    {{ $editando ? '' : 'required' }}>
                            </div>
                        </div>

                        <div class="px-6 py-5 border-t border-primary/10 bg-primary/[0.02]">
                            <button type="submit"
                                class="w-full px-5 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                                {{ $editando ? 'Guardar cambios' : 'Crear usuario' }}
                            </button>
                        </div>
                    </form>
                </div>
</div>
@endsection



