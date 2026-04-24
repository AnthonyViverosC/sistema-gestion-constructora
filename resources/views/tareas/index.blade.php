@extends('layouts.app')
@section('title', 'Tareas')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Tareas</h2>
        <p class="text-sm text-primary/50 mt-1">Seguimiento general de pendientes, vencimientos y responsables.</p>
    </div>
@endsection

@section('content')
<div class="space-y-6">
                @if (session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-4">
                        <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('tareas.index') }}" class="bg-white rounded-xl border border-primary/10 shadow-sm p-5 flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Estado</label>
                        <select name="estado" class="rounded-lg border border-primary/10 px-4 py-2 text-sm">
                            <option value="">Todos</option>
                            @foreach (['Pendiente', 'Por vencer', 'Vencida', 'Completada'] as $opcion)
                                <option value="{{ $opcion }}" @selected($estado === $opcion)>{{ $opcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($puedeVerTodas)
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Responsable</label>
                            <select name="responsable" class="rounded-lg border border-primary/10 px-4 py-2 text-sm">
                                <option value="">Todos</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" @selected((string) $responsable === (string) $usuario->id)>{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:bg-primary/90">
                        Filtrar
                    </button>
                    <a href="{{ route('tareas.index') }}" class="rounded-lg border border-primary/10 bg-white px-5 py-2 text-sm font-semibold text-primary/70 hover:bg-primary/5">
                        Limpiar
                    </a>
                </form>

                <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-primary/5">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Tarea</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Documento</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Responsable</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha límite</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Estado</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary/5">
                                @forelse ($tareas as $tarea)
                                    @php
                                        $vencida = $tarea->estado !== 'Completada' && $tarea->fecha_limite?->isPast();
                                        $porVencer = $tarea->estado !== 'Completada' && ! $vencida && $tarea->fecha_limite?->lte(now()->addDays(2));
                                        $badge = match (true) {
                                            $tarea->estado === 'Completada' => 'bg-green-100 text-green-700 border-green-200',
                                            $vencida => 'bg-red-100 text-red-700 border-red-200',
                                            $porVencer => 'bg-amber-100 text-amber-700 border-amber-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                        $estadoVisible = $vencida ? 'Vencida' : ($porVencer ? 'Por vencer' : $tarea->estado);
                                    @endphp
                                    <tr class="hover:bg-primary/[0.02]">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-primary">{{ $tarea->titulo }}</p>
                                            <p class="text-xs text-primary/50 mt-1">{{ $tarea->descripcion ?: 'Sin descripción' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            @if ($tarea->contrato)
                                                <a href="{{ route('contratos.show', $tarea->contrato) }}" class="font-semibold text-primary hover:underline">
                                                    {{ $tarea->contrato->numero_contrato }}
                                                </a>
                                            @else
                                                Sin contrato
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            {{ $tarea->documento ? ($tarea->documento->nombre_original ?? $tarea->documento->nombre_documento) : 'Sin documento' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-primary/70">{{ $tarea->assignedTo?->name ?? 'No asignado' }}</td>
                                        <td class="px-6 py-4 text-sm text-primary/70">{{ $tarea->fecha_limite?->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                {{ $estadoVisible }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if ($tarea->estado !== 'Completada')
                                                <form action="{{ route('tareas.complete', $tarea) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs font-bold text-green-700 hover:text-green-900">
                                                        Completar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-primary/40">Finalizada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-sm text-primary/40">No hay tareas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
</div>
@endsection
