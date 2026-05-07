@extends('layouts.app')
@section('title', 'Mi perfil')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Mi perfil</h2>
        <p class="text-sm text-primary/50 mt-1">
            Datos de acceso, rol y resumen de actividad dentro del sistema.
        </p>
    </div>

    <x-badge color="primary" size="md">{{ ucfirst($usuario->rol) }}</x-badge>
@endsection

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
            <div class="xl:col-span-2">
                <x-card title="Información de usuario"
                    subtitle="Actualiza tus datos básicos y tu contraseña.">
                    <form action="{{ route('perfil.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form-input name="name" label="Nombre" :value="$usuario->name" required />
                            <x-form-input name="email" label="Correo" type="email" :value="$usuario->email" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form-input name="password" label="Nueva contraseña" type="password"
                                placeholder="Dejar vacío para no cambiar" />
                            <x-form-input name="password_confirmation" label="Confirmar contraseña" type="password" />
                        </div>

                        <div class="flex justify-end pt-4 border-t border-primary/5">
                            <x-button type="submit">Guardar cambios</x-button>
                        </div>
                    </form>
                </x-card>
            </div>

            <aside class="space-y-6">
                <x-card>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Rol actual</p>
                    <p class="mt-2 text-2xl font-black text-primary">{{ ucfirst($usuario->rol) }}</p>
                </x-card>

                <div class="bg-white rounded-xl border border-amber-200 shadow-soft p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Tareas pendientes</p>
                    <p class="mt-2 text-2xl font-black text-amber-700">{{ $tareasPendientes->count() }}</p>
                </div>

                <div class="bg-white rounded-xl border border-green-200 shadow-soft p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-green-600">Tareas completadas</p>
                    <p class="mt-2 text-2xl font-black text-green-700">{{ $tareasCompletadas }}</p>
                </div>
            </aside>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
            <x-card title="Mis tareas pendientes" subtitle="Tareas asignadas ordenadas por fecha límite." :padded="false">
                <div class="divide-y divide-primary/5">
                    @forelse ($tareasPendientes as $tarea)
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-primary truncate">{{ $tarea->titulo }}</p>
                                    <p class="text-xs text-primary/50 mt-1">
                                        {{ $tarea->contrato?->numero_contrato ?? 'Sin contrato' }}
                                    </p>
                                </div>
                                <x-badge color="amber">{{ $tarea->fecha_limite?->format('d/m/Y') }}</x-badge>
                            </div>
                        </div>
                    @empty
                        <x-empty-state icon="task" title="Sin tareas pendientes"
                            description="Cuando tengas tareas asignadas aparecerán acá." />
                    @endforelse
                </div>
            </x-card>

            <x-card title="Actividad reciente" subtitle="Acciones registradas con tu usuario." :padded="false">
                <div class="divide-y divide-primary/5">
                    @forelse ($auditorias as $auditoria)
                        <div class="px-6 py-4">
                            <p class="text-sm font-bold text-primary">
                                {{ ucfirst($auditoria->accion) }} · {{ ucfirst($auditoria->modulo) }}
                            </p>
                            <p class="text-xs text-primary/60 mt-1">{{ $auditoria->detalle }}</p>
                            <p class="text-xs text-primary/40 mt-2">{{ $auditoria->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    @empty
                        <x-empty-state icon="search" title="Sin actividad"
                            description="Tus acciones quedarán registradas aquí." />
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
@endsection
