@extends('layouts.app')
@section('title', 'Centro de alertas')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Centro de alertas</h2>
        <p class="text-sm text-primary/50 mt-1">
            Pendientes importantes del sistema y acciones que requieren seguimiento.
        </p>
    </div>

    <x-badge color="primary" size="md">
        {{ $totalAlertas }} {{ $totalAlertas === 1 ? 'alerta' : 'alertas' }}
    </x-badge>
@endsection

@section('content')
    <div class="space-y-8">
        @if ($totalAlertas === 0)
            <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-5">
                <p class="text-sm font-bold text-green-700">No hay alertas pendientes.</p>
                <p class="text-sm text-green-600 mt-1">Las tareas, documentos y vencimientos principales están al día.</p>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
            <x-card title="Tareas vencidas" subtitle="Pendientes que ya superaron la fecha límite." :padded="false">
                <div class="divide-y divide-primary/5">
                    @forelse ($tareasVencidas as $tarea)
                        <div class="px-6 py-4 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-primary truncate">{{ $tarea->titulo }}</p>
                                <p class="text-xs text-primary/50 mt-1">
                                    {{ $tarea->contrato?->numero_contrato ?? 'Sin contrato' }} ·
                                    {{ $tarea->assignedTo?->name ?? 'Sin responsable' }}
                                </p>
                            </div>
                            <x-badge color="red">{{ $tarea->fecha_limite?->format('d/m/Y') }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="task" title="Sin tareas vencidas"
                            description="Todas las tareas están al día." />
                    @endforelse
                </div>
            </x-card>

            <x-card title="Tareas por vencer" subtitle="Vencen hoy o en los próximos dos días." :padded="false">
                <div class="divide-y divide-primary/5">
                    @forelse ($tareasPorVencer as $tarea)
                        <div class="px-6 py-4 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-primary truncate">{{ $tarea->titulo }}</p>
                                <p class="text-xs text-primary/50 mt-1">
                                    {{ $tarea->contrato?->numero_contrato ?? 'Sin contrato' }} ·
                                    {{ $tarea->assignedTo?->name ?? 'Sin responsable' }}
                                </p>
                            </div>
                            <x-badge color="amber">{{ $tarea->fecha_limite?->format('d/m/Y') }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="task" title="Sin tareas próximas a vencer"
                            description="No hay urgencias inmediatas." />
                    @endforelse
                </div>
            </x-card>

            <x-card title="Documentos observados o rechazados"
                subtitle="Archivos que necesitan revisión o corrección." :padded="false">
                <div class="divide-y divide-primary/5">
                    @forelse ($documentosConAlerta as $documento)
                        <div class="px-6 py-4 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-primary truncate">
                                    {{ $documento->nombre_original ?? $documento->nombre_documento }}
                                </p>
                                <p class="text-xs text-primary/50 mt-1">
                                    {{ $documento->contrato?->numero_contrato ?? 'Sin contrato' }} · {{ $documento->categoria }}
                                </p>
                            </div>
                            <x-badge :color="$documento->estado === 'Rechazado' ? 'red' : 'amber'">
                                {{ $documento->estado }}
                            </x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="document" title="Sin documentos observados"
                            description="Todos los soportes están en orden." />
                    @endforelse
                </div>
            </x-card>

            <x-card title="Contratos por vencer"
                subtitle="Contratos con vencimiento dentro de los próximos 15 días." :padded="false">
                <div class="divide-y divide-primary/5">
                    @forelse ($contratosPorVencer as $contrato)
                        <div class="px-6 py-4 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <a href="{{ route('contratos.show', $contrato) }}" class="text-sm font-bold text-primary hover:underline">
                                    {{ $contrato->numero_contrato }}
                                </a>
                                <p class="text-xs text-primary/50 mt-1 truncate">{{ $contrato->nombre_contratista }}</p>
                            </div>
                            <x-badge color="amber">{{ $contrato->fecha_fin?->format('d/m/Y') }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="document" title="Sin contratos por vencer"
                            description="Todos están dentro de plazo." />
                    @endforelse
                </div>
            </x-card>
        </div>

        @if ($puedeVerTodo)
            <x-card title="Expedientes incompletos"
                subtitle="Contratos con documentos obligatorios pendientes de aprobación." :padded="false">
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
                                <tr class="hover:bg-primary/[0.02] transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('contratos.show', $contrato) }}" class="text-sm font-bold text-primary hover:underline">
                                            {{ $contrato->numero_contrato }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-primary/70">{{ $contrato->nombre_contratista }}</td>
                                    <td class="px-6 py-4 text-sm text-primary/70">{{ $contrato->avance_documental }}%</td>
                                    <td class="px-6 py-4">
                                        <x-badge color="amber">{{ $contrato->documentos_pendientes }}</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-0 py-0">
                                        <x-empty-state icon="document" title="Sin expedientes incompletos"
                                            description="Todos los contratos tienen su documentación al día." />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endif

        <x-card title="Notificaciones registradas"
            subtitle="Historial de correos y avisos generados por tareas próximas al vencimiento." :padded="false">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary/5">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Título</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Usuario</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Estado</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Enviada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/5">
                        @forelse ($notificacionesRecientes as $notificacion)
                            <tr class="hover:bg-primary/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-primary">{{ $notificacion->titulo }}</p>
                                    <p class="text-xs text-primary/50 mt-1">{{ $notificacion->mensaje }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-primary/70">{{ $notificacion->user?->name ?? 'Sin usuario' }}</td>
                                <td class="px-6 py-4 text-sm text-primary/70">{{ $notificacion->tarea?->contrato?->numero_contrato ?? 'Sin contrato' }}</td>
                                <td class="px-6 py-4">
                                    <x-badge :color="$notificacion->estado === 'enviada' ? 'green' : 'red'">
                                        {{ ucfirst($notificacion->estado) }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 text-sm text-primary/70 whitespace-nowrap">
                                    {{ $notificacion->sent_at?->format('d/m/Y H:i') ?? 'Pendiente' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-0 py-0">
                                    <x-empty-state icon="search" title="Aún no hay notificaciones"
                                        description="Aparecerán aquí cuando el sistema envíe avisos." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
@endsection
