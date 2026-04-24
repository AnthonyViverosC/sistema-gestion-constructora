@extends('layouts.app')
@section('title', 'Dashboard Ejecutivo')

@php
    $riesgosInmediatos = $tareasVencidas + $contratosVencidos + $documentosRechazados;
    $maxContratos = max($totalContratos, 1);
    $maxDocumentos = max($totalDocumentos, 1);
@endphp

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Dashboard ejecutivo</h2>
        <p class="text-sm text-primary/50 mt-1">
            Panorama general del estado contractual, documental y operativo.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('notificaciones.index') }}"
            class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary hover:bg-primary/5 transition-colors">
            Ver alertas
        </a>

        @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
            <a href="{{ route('contratos.create') }}"
                class="px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                Nuevo contrato
            </a>
        @endif
    </div>
@endsection

@section('content')
    <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Total contratos</p>
                        <h3 class="text-3xl font-extrabold text-primary">{{ $totalContratos }}</h3>
                        <p class="text-sm text-primary/50 mt-2">
                            {{ $contratosActivos }} activos y {{ $contratosPendientes }} pendientes.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl border border-green-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-green-600 mb-2">Avance documental</p>
                        <h3 class="text-3xl font-extrabold text-green-700">{{ $promedioAvanceDocumental }}%</h3>
                        <p class="text-sm text-green-700/80 mt-2">
                            {{ $contratosDocumentacionCompleta }} expedientes completos.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-2">Tareas pendientes</p>
                        <h3 class="text-3xl font-extrabold text-amber-700">{{ $tareasPendientes }}</h3>
                        <p class="text-sm text-amber-700/80 mt-2">
                            {{ $tareasPorVencer }} por vencer en 48 horas.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl border border-red-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-red-500 mb-2">Riesgos inmediatos</p>
                        <h3 class="text-3xl font-extrabold text-red-600">{{ $riesgosInmediatos }}</h3>
                        <p class="text-sm text-red-600/80 mt-2">
                            Tareas vencidas, contratos vencidos y documentos rechazados.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <div class="xl:col-span-2 bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Distribuci&oacute;n contractual</h3>
                            <p class="text-sm text-primary/50 mt-1">Resumen por estado para lectura r&aacute;pida.</p>
                        </div>

                        <div class="p-6 space-y-5">
                            @foreach ($distribucionEstadosContrato as $estado)
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <span class="w-3 h-3 rounded-full {{ $estado['color'] }}"></span>
                                            <span class="text-sm font-semibold text-primary">{{ $estado['label'] }}</span>
                                        </div>
                                        <div class="text-sm text-primary/60">
                                            {{ $estado['value'] }} / {{ $totalContratos }}
                                        </div>
                                    </div>
                                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full {{ $estado['color'] }}"
                                            style="width: {{ round(($estado['value'] / $maxContratos) * 100, 2) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Indicadores clave</h3>
                            <p class="text-sm text-primary/50 mt-1">Estado de vigencia y control documental.</p>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                                <span class="text-sm font-semibold text-green-700">Contratos vigentes</span>
                                <span class="text-xl font-bold text-green-800">{{ $contratosVigentes }}</span>
                            </div>

                            <div class="flex items-center justify-between rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
                                <span class="text-sm font-semibold text-amber-700">Por vencer</span>
                                <span class="text-xl font-bold text-amber-800">{{ $contratosPorVencer }}</span>
                            </div>

                            <div class="flex items-center justify-between rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                                <span class="text-sm font-semibold text-red-700">Vencidos</span>
                                <span class="text-xl font-bold text-red-800">{{ $contratosVencidos }}</span>
                            </div>

                            <div class="flex items-center justify-between rounded-lg border border-orange-200 bg-orange-50 px-4 py-3">
                                <span class="text-sm font-semibold text-orange-700">Documentos observados</span>
                                <span class="text-xl font-bold text-orange-800">{{ $documentosObservados }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <div class="xl:col-span-2 bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-primary">Contratos con menor avance</h3>
                                <p class="text-sm text-primary/50 mt-1">Prioriza los expedientes con mayor brecha documental.</p>
                            </div>
                            <a href="{{ route('contratos.index') }}"
                                class="text-sm font-semibold text-primary hover:text-primary/70 transition-colors">
                                Ver contratos
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Responsable</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Pendientes</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Avance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse($contratosCriticos as $item)
                                        <tr class="hover:bg-primary/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <a href="{{ route('contratos.show', $item['contrato']) }}"
                                                    class="text-sm font-semibold text-primary hover:underline">
                                                    {{ $item['contrato']->numero_contrato }}
                                                </a>
                                                <p class="text-xs text-primary/50 mt-1">
                                                    {{ $item['contrato']->nombre_contratista }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-primary/70">
                                                {{ $item['contrato']->createdBy?->name ?? 'Sin responsable' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border border-amber-200 bg-amber-50 text-amber-700">
                                                    {{ $item['pendientes'] }} pendientes
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-28 h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                                        <div class="h-full bg-primary"
                                                            style="width: {{ $item['porcentaje'] }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-semibold text-primary">{{ $item['porcentaje'] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-sm text-primary/40">
                                                No hay contratos con pendientes relevantes.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Distribuci&oacute;n documental</h3>
                            <p class="text-sm text-primary/50 mt-1">Estado actual de los documentos cargados.</p>
                        </div>

                        <div class="p-6 space-y-5">
                            @foreach ($distribucionEstadosDocumento as $estado)
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <span class="w-3 h-3 rounded-full {{ $estado['color'] }}"></span>
                                            <span class="text-sm font-semibold text-primary">{{ $estado['label'] }}</span>
                                        </div>
                                        <div class="text-sm text-primary/60">
                                            {{ $estado['value'] }} / {{ $totalDocumentos }}
                                        </div>
                                    </div>
                                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full {{ $estado['color'] }}"
                                            style="width: {{ round(($estado['value'] / $maxDocumentos) * 100, 2) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Rendimiento por responsable</h3>
                            <p class="text-sm text-primary/50 mt-1">Carga operativa y actividad reciente por usuario.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Usuario</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Pendientes</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Completadas</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Documentos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse($rendimientoResponsables as $item)
                                        <tr class="hover:bg-primary/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-semibold text-primary">{{ $item['usuario']->name }}</p>
                                                <p class="text-xs text-primary/50 mt-1">{{ ucfirst($item['usuario']->rol) }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-primary/70">{{ $item['pendientes'] }}</td>
                                            <td class="px-6 py-4 text-sm text-primary/70">{{ $item['completadas'] }}</td>
                                            <td class="px-6 py-4 text-sm text-primary/70">{{ $item['documentos'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-sm text-primary/40">
                                                Todav&iacute;a no hay actividad suficiente para mostrar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Actividad reciente</h3>
                            <p class="text-sm text-primary/50 mt-1">Seguimiento de movimientos relevantes del sistema.</p>
                        </div>

                        <div class="p-6 space-y-4">
                            @forelse($actividadReciente as $registro)
                                <div class="flex items-start gap-4">
                                    <div class="mt-1 w-2.5 h-2.5 rounded-full bg-primary"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-primary">{{ $registro->descripcion }}</p>
                                        <p class="text-xs text-primary/50 mt-1">
                                            {{ $registro->user?->name ?? 'Sistema' }} · {{ $registro->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-primary/40">No hay movimientos recientes registrados.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <div class="xl:col-span-2 bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-primary">Prioridades inmediatas</h3>
                                <p class="text-sm text-primary/50 mt-1">Solo lo que necesita atenci&oacute;n cercana.</p>
                            </div>
                            <a href="{{ route('tareas.index') }}"
                                class="text-sm font-semibold text-primary hover:text-primary/70 transition-colors">
                                Ver tareas
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Tarea</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">L&iacute;mite</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse($ultimasTareas as $tarea)
                                        <tr class="hover:bg-primary/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-semibold text-primary">{{ $tarea->titulo }}</p>
                                                <p class="text-xs text-primary/50 mt-1">{{ $tarea->assignedTo?->name ?? 'Sin responsable' }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-primary/70">
                                                @if ($tarea->contrato)
                                                    <a href="{{ route('contratos.show', $tarea->contrato) }}" class="hover:underline">
                                                        {{ $tarea->contrato->numero_contrato }}
                                                    </a>
                                                @else
                                                    Sin contrato
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-primary/70">
                                                {{ $tarea->fecha_limite?->format('d/m/Y') ?? 'Sin fecha' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-sm text-primary/40">
                                                No hay tareas pendientes.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Accesos r&aacute;pidos</h3>
                        </div>

                        <div class="p-6 flex flex-col gap-3">
                            @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                <a href="{{ route('contratos.create') }}"
                                    class="px-4 py-3 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors text-center">
                                    Crear contrato
                                </a>
                                <a href="{{ route('reportes.documentos.csv') }}"
                                    class="px-4 py-3 rounded-lg border border-green-200 bg-green-50 text-green-700 text-sm font-semibold hover:bg-green-100 transition-colors text-center">
                                    Descargar reporte documental
                                </a>
                            @endif

                            <a href="{{ route('contratos.index') }}"
                                class="px-4 py-3 rounded-lg border border-primary/10 bg-white text-primary/70 text-sm font-semibold hover:bg-primary/5 transition-colors text-center">
                                Ver contratos
                            </a>

                            <a href="{{ route('perfil.show') }}"
                                class="px-4 py-3 rounded-lg border border-primary/10 bg-white text-primary/70 text-sm font-semibold hover:bg-primary/5 transition-colors text-center">
                                Ir a mi perfil
                            </a>

                            <a href="{{ route('notificaciones.index') }}"
                                class="px-4 py-3 rounded-lg border border-primary/10 bg-white text-primary/70 text-sm font-semibold hover:bg-primary/5 transition-colors text-center">
                                Ir al centro de alertas
                            </a>
                        </div>
                    </div>
                </div>
    </div>
@endsection
