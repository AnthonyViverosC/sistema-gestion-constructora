@extends('layouts.app')
@section('title', 'Centro de alertas')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Centro de alertas</h2>
        <p class="text-sm text-primary/50 mt-1">
            Pendientes importantes del sistema y acciones que requieren seguimiento.
        </p>
    </div>

    <span class="rounded-full border border-primary/10 bg-primary/5 px-4 py-2 text-sm font-bold text-primary">
        {{ $totalAlertas }} {{ $totalAlertas === 1 ? 'alerta' : 'alertas' }}
    </span>
@endsection

@section('content')
    <div class="space-y-8">
                @if ($totalAlertas === 0)
                    <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-5">
                        <p class="text-sm font-bold text-green-700">No hay alertas pendientes.</p>
                        <p class="text-sm text-green-600 mt-1">Las tareas, documentos y vencimientos principales est&aacute;n al d&iacute;a.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Tareas vencidas</h3>
                            <p class="text-sm text-primary/50 mt-1">Pendientes que ya superaron la fecha l&iacute;mite.</p>
                        </div>

                        <div class="divide-y divide-primary/5">
                            @forelse ($tareasVencidas as $tarea)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-primary">{{ $tarea->titulo }}</p>
                                            <p class="text-xs text-primary/50 mt-1">
                                                {{ $tarea->contrato?->numero_contrato ?? 'Sin contrato' }} ·
                                                {{ $tarea->assignedTo?->name ?? 'Sin responsable' }}
                                            </p>
                                        </div>
                                        <span class="rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-bold text-red-600">
                                            {{ $tarea->fecha_limite?->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="px-6 py-10 text-center text-sm text-primary/40">No hay tareas vencidas.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Tareas por vencer</h3>
                            <p class="text-sm text-primary/50 mt-1">Vencen hoy o en los pr&oacute;ximos dos d&iacute;as.</p>
                        </div>

                        <div class="divide-y divide-primary/5">
                            @forelse ($tareasPorVencer as $tarea)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-primary">{{ $tarea->titulo }}</p>
                                            <p class="text-xs text-primary/50 mt-1">
                                                {{ $tarea->contrato?->numero_contrato ?? 'Sin contrato' }} ·
                                                {{ $tarea->assignedTo?->name ?? 'Sin responsable' }}
                                            </p>
                                        </div>
                                        <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                            {{ $tarea->fecha_limite?->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="px-6 py-10 text-center text-sm text-primary/40">No hay tareas por vencer.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Documentos observados o rechazados</h3>
                            <p class="text-sm text-primary/50 mt-1">Archivos que necesitan revisi&oacute;n o correcci&oacute;n.</p>
                        </div>

                        <div class="divide-y divide-primary/5">
                            @forelse ($documentosConAlerta as $documento)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-primary">
                                                {{ $documento->nombre_original ?? $documento->nombre_documento }}
                                            </p>
                                            <p class="text-xs text-primary/50 mt-1">
                                                {{ $documento->contrato?->numero_contrato ?? 'Sin contrato' }} · {{ $documento->categoria }}
                                            </p>
                                        </div>
                                        <span class="rounded-full border {{ $documento->estado === 'Rechazado' ? 'border-red-200 bg-red-50 text-red-600' : 'border-orange-200 bg-orange-50 text-orange-700' }} px-3 py-1 text-xs font-bold">
                                            {{ $documento->estado }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="px-6 py-10 text-center text-sm text-primary/40">No hay documentos observados o rechazados.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Contratos por vencer</h3>
                            <p class="text-sm text-primary/50 mt-1">Contratos con vencimiento dentro de los pr&oacute;ximos 15 d&iacute;as.</p>
                        </div>

                        <div class="divide-y divide-primary/5">
                            @forelse ($contratosPorVencer as $contrato)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <a href="{{ route('contratos.show', $contrato) }}" class="text-sm font-bold text-primary hover:underline">
                                                {{ $contrato->numero_contrato }}
                                            </a>
                                            <p class="text-xs text-primary/50 mt-1">{{ $contrato->nombre_contratista }}</p>
                                        </div>
                                        <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                            {{ $contrato->fecha_fin?->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="px-6 py-10 text-center text-sm text-primary/40">No hay contratos por vencer.</p>
                            @endforelse
                        </div>
                    </section>
                </div>

                @if ($puedeVerTodo)
                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Expedientes incompletos</h3>
                            <p class="text-sm text-primary/50 mt-1">Contratos con documentos obligatorios pendientes de aprobaci&oacute;n.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contratista</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Avance</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Pendientes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse ($expedientesIncompletos as $contrato)
                                        <tr class="hover:bg-primary/[0.02]">
                                            <td class="px-6 py-4">
                                                <a href="{{ route('contratos.show', $contrato) }}" class="text-sm font-bold text-primary hover:underline">
                                                    {{ $contrato->numero_contrato }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-primary/70">{{ $contrato->nombre_contratista }}</td>
                                            <td class="px-6 py-4 text-sm text-primary/70">{{ $contrato->avance_documental }}%</td>
                                            <td class="px-6 py-4">
                                                <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                                    {{ $contrato->documentos_pendientes }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-sm text-primary/40">
                                                No hay expedientes incompletos.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-primary/10">
                        <h3 class="text-lg font-bold text-primary">Notificaciones registradas</h3>
                        <p class="text-sm text-primary/50 mt-1">Historial de correos y avisos generados por tareas proximas al vencimiento.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-primary/5">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Titulo</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Usuario</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Estado</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Enviada</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary/5">
                                @forelse ($notificacionesRecientes as $notificacion)
                                    <tr class="hover:bg-primary/[0.02]">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-primary">{{ $notificacion->titulo }}</p>
                                            <p class="text-xs text-primary/50 mt-1">{{ $notificacion->mensaje }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-primary/70">{{ $notificacion->user?->name ?? 'Sin usuario' }}</td>
                                        <td class="px-6 py-4 text-sm text-primary/70">{{ $notificacion->tarea?->contrato?->numero_contrato ?? 'Sin contrato' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="rounded-full border px-3 py-1 text-xs font-bold {{ $notificacion->estado === 'enviada' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-600' }}">
                                                {{ ucfirst($notificacion->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-primary/70">{{ $notificacion->sent_at?->format('d/m/Y H:i') ?? 'Pendiente' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-sm text-primary/40">
                                            Aun no se han registrado notificaciones.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
    </div>
@endsection
