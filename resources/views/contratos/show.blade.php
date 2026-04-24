@extends('layouts.app')
@section('title', 'Detalle del Contrato')

@section('header')
    <div>
        <div class="flex items-center gap-2 text-xs text-primary/40 mb-1">
            <a href="{{ route('contratos.index') }}" class="hover:text-primary transition-colors">Contratos</a>
            <span>/</span>
            <span class="text-primary/70 font-medium">Detalle del contrato</span>
        </div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Detalle del Contrato</h2>
    </div>
    <div class="flex items-center gap-3">
        @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
            <a href="{{ route('contratos.edit', $contrato) }}"
                class="px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary/90 transition-colors">
                Editar contrato
            </a>
        @endif
        <a href="{{ route('contratos.index') }}"
            class="px-4 py-2.5 border border-primary/10 bg-white text-sm font-medium text-primary/70 rounded-xl hover:bg-primary/5 transition-colors">
            Volver
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-8">

                @php
                    $badgeEstado = match (true) {
                        str_contains(strtolower($contrato->estado), 'activ')
                            => 'bg-green-100 text-green-700 border-green-200',
                        str_contains(strtolower($contrato->estado), 'pend')
                            => 'bg-amber-100 text-amber-700 border-amber-200',
                        str_contains(strtolower($contrato->estado), 'cancel')
                            => 'bg-red-100 text-red-700 border-red-200',
                        str_contains(strtolower($contrato->estado), 'finaliz')
                            => 'bg-slate-100 text-slate-600 border-slate-200',
                        default => 'bg-primary/10 text-primary border-primary/20',
                    };

                    $badgeVigencia = match ($estadoVigencia ?? 'Sin definir') {
                        'Vigente' => 'bg-green-100 text-green-700 border-green-200',
                        'Por vencer' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'Vencido' => 'bg-red-100 text-red-700 border-red-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                    };
                    $tareasAbiertas = $contrato->tareas->where('estado', '!=', 'Completada')->count();
                    $documentosPendientes = $resumenDocumental['pendientes'];
                    $siguientePasoTitulo = 'Expediente al dia';
                    $siguientePasoTexto = 'Ya puedes revisar el historial y validar si el contrato esta listo para cierre.';

                    if ($documentosPendientes > 0) {
                        $siguientePasoTitulo = 'Subir o aprobar documentos';
                        $siguientePasoTexto = 'Aun faltan '.$documentosPendientes.' requisito(s) documentales para completar el expediente.';
                    } elseif ($tareasAbiertas > 0) {
                        $siguientePasoTitulo = 'Cerrar tareas pendientes';
                        $siguientePasoTexto = 'El expediente ya tiene soporte documental, pero quedan '.$tareasAbiertas.' tarea(s) abierta(s).';
                    } elseif ($contrato->estado !== \App\Enums\EstadoContrato::DocumentacionCompleta->value) {
                        $siguientePasoTitulo = 'Marcar documentacion completa';
                        $siguientePasoTexto = 'No hay pendientes documentales ni tareas abiertas. Puedes cerrar formalmente el expediente.';
                    }
                    $clasesRiesgo = [
                        'critico' => 'border-red-200 bg-red-50 text-red-700',
                        'alerta' => 'border-amber-200 bg-amber-50 text-amber-700',
                        'seguimiento' => 'border-blue-200 bg-blue-50 text-blue-700',
                    ];
                @endphp

                <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Contrato</p>
                        <p class="mt-2 text-lg font-bold text-primary">{{ $contrato->numero_contrato }}</p>
                        <p class="text-sm text-primary/50 mt-1">{{ $contrato->nombre_contratista }}</p>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Avance documental</p>
                        <p class="mt-2 text-lg font-bold text-primary">{{ $resumenDocumental['porcentaje'] }}%</p>
                        <p class="text-sm text-primary/50 mt-1">{{ $resumenDocumental['cumplidos'] }} de {{ $resumenDocumental['total'] }} requisitos</p>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Estado</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeEstado }}">
                                {{ $contrato->estado }}
                            </span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Vigencia</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeVigencia }}">
                                {{ $estadoVigencia ?? 'Sin definir' }}
                            </span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Tareas abiertas</p>
                        <p class="mt-2 text-lg font-bold text-primary">{{ $tareasAbiertas }}</p>
                        <p class="text-sm text-primary/50 mt-1">Pendientes por resolver</p>
                    </div>
                </section>

                <section class="rounded-xl border border-primary/10 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Siguiente paso recomendado</p>
                            <h3 class="mt-2 text-lg font-bold text-primary">{{ $siguientePasoTitulo }}</h3>
                            <p class="text-sm text-primary/60 mt-1">{{ $siguientePasoTexto }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('documentos.create', $contrato) }}"
                                class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                                Ver expediente
                            </a>
                            @if ($tareasAbiertas > 0)
                                <a href="#bloque-tareas"
                                    class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary/70 hover:bg-primary/5 transition-colors">
                                    Revisar tareas
                                </a>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-primary/10 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-primary/10">
                        <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                            <div class="space-y-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Resumen ejecutivo</p>
                                <h3 class="text-lg font-bold text-primary">Todo el contrato, sin perderse en una sola columna.</h3>
                                <p class="text-sm text-primary/60 max-w-3xl">
                                    Revisa riesgos, prioridad actual y entra por pestañas segun lo que quieras resolver: contexto general, control documental o seguimiento operativo.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($riesgos as $riesgo)
                                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $clasesRiesgo[$riesgo['nivel']] ?? $clasesRiesgo['seguimiento'] }}">
                                        {{ $riesgo['titulo'] }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                                        Sin alertas fuertes
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
                        @foreach ($resumenEjecutivo as $item)
                            <div class="rounded-xl border border-primary/10 bg-slate-50 px-4 py-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-primary/45">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-black text-primary">{{ $item['value'] }}</p>
                                <p class="mt-1 text-sm text-primary/55">{{ $item['hint'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-xl border border-primary/10 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-primary/10 px-4 sm:px-6 py-4">
                        <div class="flex flex-wrap gap-2" id="contractTabs" role="tablist" aria-label="Secciones del contrato">
                            <button type="button" data-tab-target="resumen" role="tab" aria-selected="true"
                                class="contract-tab inline-flex items-center rounded-lg border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white">
                                Resumen
                            </button>
                            <button type="button" data-tab-target="documentos" role="tab" aria-selected="false"
                                class="contract-tab inline-flex items-center rounded-lg border border-primary/10 bg-white px-4 py-2 text-sm font-semibold text-primary/70">
                                Documentos
                            </button>
                            <button type="button" data-tab-target="seguimiento" role="tab" aria-selected="false"
                                class="contract-tab inline-flex items-center rounded-lg border border-primary/10 bg-white px-4 py-2 text-sm font-semibold text-primary/70">
                                Seguimiento
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div data-tab-panel="resumen" role="tabpanel" class="space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
                                <div class="space-y-6">
                        <div data-section-group="resumen" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Información general</h3>
                                <p class="text-sm text-primary/50 mt-1">
                                    Datos principales del contrato registrado.
                                </p>
                            </div>

                            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Número de contrato
                                    </p>
                                    <p class="text-sm font-semibold text-primary">
                                        {{ $contrato->numero_contrato }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Fecha del contrato
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->fecha_contrato ? \Carbon\Carbon::parse($contrato->fecha_contrato)->format('d/m/Y') : 'No registrada' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Fecha de inicio
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->fecha_inicio ? \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') : 'No registrada' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Fecha de finalización
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') : 'No registrada' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Cédula del contratista
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->cedula_contratista }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Nombre del contratista
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->nombre_contratista }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Estado
                                    </p>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeEstado }}">
                                        {{ $contrato->estado }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Vigencia
                                    </p>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeVigencia }}">
                                        {{ $estadoVigencia ?? 'Sin definir' }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Total documentos
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->documentos->count() }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Responsable
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->createdBy?->name ?? 'No registrado' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Etiqueta
                                    </p>
                                    <p class="text-sm text-primary/80">
                                        {{ $contrato->etiqueta ?: 'Sin etiqueta' }}
                                    </p>
                                </div>

                                <div class="sm:col-span-2 lg:col-span-3">
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">
                                        Descripción
                                    </p>
                                    <div
                                        class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4 text-sm text-primary/80">
                                        {{ $contrato->descripcion ?: 'Sin descripción registrada.' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div data-section-group="resumen" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-primary">Plantilla documental</h3>
                                        <p class="text-sm text-primary/50 mt-1">
                                            Documentos obligatorios esperados para completar el expediente.
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-black text-primary">
                                            {{ $resumenDocumental['porcentaje'] }}%
                                        </p>
                                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">
                                            {{ $resumenDocumental['cumplidos'] }} de {{ $resumenDocumental['total'] }} aprobados
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full {{ $resumenDocumental['pendientes'] === 0 ? 'bg-green-500' : 'bg-amber-500' }}"
                                        style="width: {{ $resumenDocumental['porcentaje'] }}%"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($resumenDocumental['items'] as $item)
                                        @php
                                            $requisito = $item['requisito'];
                                        @endphp

                                        <div class="rounded-xl border {{ $item['cumplido'] ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50' }} px-4 py-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-bold text-primary">
                                                        {{ $requisito->nombre }}
                                                    </p>
                                                    <p class="text-xs text-primary/50 mt-1">
                                                        {{ $requisito->categoria }} · {{ $item['documentos_cargados'] }} cargado(s)
                                                    </p>
                                                </div>
                                                <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold border {{ $item['cumplido'] ? 'bg-white text-green-700 border-green-200' : 'bg-white text-amber-700 border-amber-200' }}">
                                                    {{ $item['cumplido'] ? 'Aprobado' : 'Pendiente' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div data-section-group="documentos" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-primary">Documentos asociados</h3>
                                    <p class="text-sm text-primary/50 mt-1">
                                        Archivos registrados para este contrato.
                                    </p>
                                </div>

                                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                    <a href="{{ route('documentos.create', $contrato) }}"
                                        class="px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary/90 transition-colors">
                                        Gestionar documentos
                                    </a>
                                @endif
                            </div>

                            <div class="max-h-[520px] overflow-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-primary/5">
                                            <th
                                                class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                                Documento
                                            </th>
                                            <th
                                                class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                                Categoría
                                            </th>
                                            <th
                                                class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                                Fecha
                                            </th>
                                            <th
                                                class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                                Estado
                                            </th>
                                            <th
                                                class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-primary/5">
                                        @forelse($contrato->documentos as $documento)
                                            <tr class="hover:bg-primary/[0.02] transition-colors">
                                                <td class="px-6 py-4">
                                                    <p class="text-sm font-semibold text-primary">
                                                        {{ $documento->nombre_original ?? $documento->nombre_documento }}
                                                    </p>
                                                    <p class="text-xs text-primary/50 mt-1 uppercase">
                                                        {{ pathinfo($documento->archivo, PATHINFO_EXTENSION) }}
                                                    </p>
                                                </td>

                                                <td class="px-6 py-4 text-sm text-primary/70">
                                                    {{ $documento->categoria }}
                                                </td>

                                                <td class="px-6 py-4 text-sm text-primary/70">
                                                    {{ $documento->fecha_carga }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    @php
                                                        $estadoDoc = strtolower($documento->estado);
                                                        $badgeDoc = match (true) {
                                                            str_contains($estadoDoc, 'aprob')
                                                                => 'bg-green-100 text-green-700 border-green-200',
                                                            str_contains($estadoDoc, 'activ')
                                                                => 'bg-green-100 text-green-700 border-green-200',
                                                            str_contains($estadoDoc, 'rechaz')
                                                                => 'bg-red-100 text-red-700 border-red-200',
                                                            str_contains($estadoDoc, 'observ')
                                                                => 'bg-orange-100 text-orange-700 border-orange-200',
                                                            str_contains($estadoDoc, 'revisi')
                                                                => 'bg-blue-100 text-blue-700 border-blue-200',
                                                            str_contains($estadoDoc, 'pend')
                                                                => 'bg-amber-100 text-amber-700 border-amber-200',
                                                            default => 'bg-primary/10 text-primary border-primary/20',
                                                        };
                                                    @endphp

                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeDoc }}">
                                                        {{ $documento->estado }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4 text-right">
                                                    <div class="flex items-center justify-end gap-3">
                                                        <a href="{{ route('documentos.view', $documento) }}"
                                                            target="_blank"
                                                            class="text-xs font-bold text-primary hover:text-primary/70 transition-colors">
                                                            Ver
                                                        </a>

                                                        <span class="text-primary/20">|</span>

                                                        <a href="{{ route('documentos.download', $documento) }}"
                                                            class="text-xs font-bold text-green-600 hover:text-green-800">
                                                            Descargar
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5"
                                                    class="px-6 py-16 text-center text-sm text-primary/40 font-medium">
                                                    Este contrato no tiene documentos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if (auth()->user()->puedeGestionar())
                            <div id="bloque-tareas" data-section-group="seguimiento" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                                <div class="px-6 py-5 border-b border-primary/10">
                                    <h3 class="text-lg font-bold text-primary">Nueva tarea</h3>
                                    <p class="text-sm text-primary/50 mt-1">Asigna responsable, fecha límite y documento relacionado.</p>
                                </div>

                                <form action="{{ route('tareas.store', $contrato) }}" method="POST" class="p-6 space-y-5">
                                    @csrf

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Título de la tarea</label>
                                        <input type="text" name="titulo" placeholder="Ej: Verificar pólizas"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Responsable</label>
                                            <select name="assigned_to"
                                                class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30">
                                                <option value="">Sin responsable asignado</option>
                                                @foreach ($usuarios as $usuario)
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }} · {{ ucfirst($usuario->rol) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Fecha límite</label>
                                            <input type="date" name="fecha_limite"
                                                class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Documento asociado</label>
                                        <select name="documento_id"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30">
                                            <option value="">Sin documento asociado</option>
                                            @foreach ($contrato->documentos as $documento)
                                                <option value="{{ $documento->id }}">{{ $documento->nombre_original ?? $documento->nombre_documento }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Descripción</label>
                                        <textarea name="descripcion" rows="3" placeholder="Detalles o instrucciones para la tarea"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30 resize-none"></textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary/90">
                                            Crear tarea
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div data-section-group="documentos" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Estructura documental</h3>
                                <p class="text-sm text-primary/50 mt-1">Secciones activas del expediente por categoria.</p>
                            </div>

                            <div class="p-6 space-y-4 max-h-[620px] overflow-y-auto">
                                @foreach ($seccionesDocumentales as $seccion)
                                    <div class="rounded-xl border border-primary/10 bg-slate-50 p-4">
                                        <div class="flex items-center justify-between gap-3 mb-3">
                                            <p class="text-sm font-bold text-primary">{{ $seccion['categoria'] }}</p>
                                            <span class="text-xs font-semibold text-primary/50">
                                                {{ $seccion['items']->where('cumplido', true)->count() }}/{{ $seccion['items']->count() }} aprobados
                                            </span>
                                        </div>

                                        <div class="space-y-2">
                                            @foreach ($seccion['items'] as $item)
                                                <div class="flex items-center justify-between gap-3 rounded-lg bg-white px-3 py-2 border border-primary/10">
                                                    <div>
                                                        <p class="text-sm font-medium text-primary">{{ $item['requisito']->nombre }}</p>
                                                        @if ($item['requisito']->descripcion)
                                                            <p class="text-xs text-primary/50 mt-1">{{ $item['requisito']->descripcion }}</p>
                                                        @endif
                                                    </div>
                                                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold border {{ $item['cumplido'] ? 'border-green-200 bg-green-50 text-green-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
                                                        {{ $item['cumplido'] ? 'Listo' : 'Pendiente' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                    <form action="{{ route('contratos.estructura-documental.store', $contrato) }}" method="POST" class="rounded-xl border border-primary/10 bg-slate-50 p-4 space-y-3">
                                        @csrf
                                        <p class="text-sm font-bold text-primary">Agregar seccion documental</p>

                                        <input type="text" name="nombre" placeholder="Nombre de la seccion"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30" required>

                                        <input type="text" name="categoria" placeholder="Categoria"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30" required>

                                        <textarea name="descripcion" rows="2" placeholder="Descripcion opcional"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30"></textarea>

                                        <label class="flex items-center gap-2 text-sm text-primary/70">
                                            <input type="checkbox" name="obligatorio" value="1" checked class="rounded border-primary/20">
                                            Marcar como obligatoria
                                        </label>

                                        <button type="submit"
                                            class="w-full rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">
                                            Guardar seccion
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div data-section-group="documentos" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Acciones rápidas</h3>
                            </div>

                            <div class="p-6 space-y-3">
                                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                    <a href="{{ route('contratos.edit', $contrato) }}"
                                        class="block w-full text-center px-4 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                                        Editar contrato
                                    </a>

                                    <form action="{{ route('contratos.completar-documentacion', $contrato) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-center px-4 py-3 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm font-semibold hover:bg-green-100 transition-colors">
                                            Marcar documentación completa
                                        </button>
                                    </form>
                                @endif

                                @if (in_array(auth()->user()->rol, ['admin', 'gestor', 'consulta']))
                                    <a href="{{ route('documentos.create', $contrato) }}"
                                        class="block w-full text-center px-4 py-3 rounded-xl border border-primary/10 bg-white text-primary/70 text-sm font-semibold hover:bg-primary/5 transition-colors">
                                        {{ in_array(auth()->user()->rol, ['admin', 'gestor']) ? 'Administrar documentos' : 'Ver documentos' }}
                                    </a>
                                @endif

                                @if (auth()->user()->rol === 'admin')
                                    <form action="{{ route('contratos.destroy', $contrato) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('¿Está seguro de que desea eliminar este contrato?')"
                                            class="block w-full text-center px-4 py-3 rounded-xl border border-red-200 bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors">
                                            Eliminar contrato
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div data-section-group="seguimiento" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Tareas registradas</h3>
                                <p class="text-sm text-primary/50 mt-1">Pendientes y fechas límite ({{ $contrato->tareas->count() }} en total).</p>
                            </div>

                            <div class="p-6 space-y-4 max-h-[540px] overflow-y-auto">
                                @forelse ($contrato->tareas as $tarea)
                                    @php
                                        $vencida = $tarea->estado !== 'Completada' && $tarea->fecha_limite?->isPast();
                                    @endphp
                                    <div class="rounded-xl border {{ $vencida ? 'border-red-200 bg-red-50' : 'border-primary/10 bg-white' }} p-4">
                                        <p class="text-sm font-bold text-primary">{{ $tarea->titulo }}</p>
                                        <p class="text-xs text-primary/50 mt-1">
                                            Límite: {{ $tarea->fecha_limite?->format('d/m/Y') }} · {{ $tarea->estado }}
                                        </p>
                                        <p class="text-xs text-primary/50 mt-1">
                                            Responsable: {{ $tarea->assignedTo?->name ?? 'No asignado' }}
                                        </p>
                                        @if ($tarea->documento)
                                            <p class="text-xs text-primary/50 mt-1">
                                                Documento: {{ $tarea->documento->nombre_original ?? $tarea->documento->nombre_documento }}
                                            </p>
                                        @endif

                                        @if ((auth()->user()->puedeGestionar() || $tarea->assigned_to === auth()->id()) && $tarea->estado !== 'Completada')
                                            <form action="{{ route('tareas.complete', $tarea) }}" method="POST" class="mt-3">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs font-bold text-green-700 hover:text-green-900">
                                                    Completar tarea
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-primary/40">No hay tareas registradas.</p>
                                @endforelse
                            </div>
                        </div>

                        <div id="bloque-historial" data-section-group="seguimiento" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Historial</h3>
                                <p class="text-sm text-primary/50 mt-1">Últimas acciones sobre este contrato.</p>
                            </div>

                            <div class="p-6 space-y-4 max-h-[480px] overflow-y-auto">
                                @forelse ($auditorias as $auditoria)
                                    <div class="rounded-xl border border-primary/10 bg-slate-50 px-4 py-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-primary">
                                                    {{ ucfirst($auditoria->accion) }} · {{ ucfirst($auditoria->modulo) }}
                                                </p>
                                                <p class="text-xs text-primary/60 mt-1">
                                                    {{ $auditoria->detalle }}
                                                </p>
                                                <p class="text-xs text-primary/40 mt-2">
                                                    {{ $auditoria->user?->name ?? 'Sistema' }}
                                                </p>
                                            </div>
                                            <p class="text-xs text-primary/40 whitespace-nowrap">
                                                {{ $auditoria->created_at?->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-primary/40">No hay historial registrado para este contrato.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
                        </div>
                    </div>
                </section>
            </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = Array.from(document.querySelectorAll('.contract-tab'));
        const groupedSections = Array.from(document.querySelectorAll('[data-section-group]'));

        function activarSeccion(seccion) {
            tabs.forEach((tab) => {
                const activa = tab.dataset.tabTarget === seccion;
                tab.setAttribute('aria-selected', activa ? 'true' : 'false');
                tab.classList.toggle('bg-primary', activa);
                tab.classList.toggle('text-white', activa);
                tab.classList.toggle('border-primary', activa);
                tab.classList.toggle('bg-white', !activa);
                tab.classList.toggle('text-primary/70', !activa);
                tab.classList.toggle('border-primary/10', !activa);
            });

            groupedSections.forEach((section) => {
                section.classList.toggle('hidden', section.dataset.sectionGroup !== seccion);
            });
        }

        if (tabs.length && groupedSections.length) {
            let seccionInicial = 'resumen';
            if (window.location.hash === '#bloque-documentos') seccionInicial = 'documentos';
            if (window.location.hash === '#bloque-tareas' || window.location.hash === '#bloque-historial') seccionInicial = 'seguimiento';

            activarSeccion(seccionInicial);

            tabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    activarSeccion(tab.dataset.tabTarget);
                });
            });
        }
    });
</script>
@endpush
