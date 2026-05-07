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
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('usuarios.edit', $u) }}" class="text-xs font-bold text-primary hover:text-primary/70">
                                                    Editar
                                                </a>
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



