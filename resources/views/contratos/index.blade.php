@extends('layouts.app')
@section('title', 'Contratos')

@section('header')
    <h2 class="text-2xl font-bold text-primary tracking-tight">Listado de Contratos</h2>
    @auth
        @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
            <a href="{{ route('contratos.create') }}"
                class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-lg transition-all shadow-sm">
                <span class="text-sm font-bold tracking-wide">Nuevo Contrato</span>
            </a>
        @endif
    @endauth
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold text-primary">Buscar contratos</h3>
                <p class="text-sm text-primary/50 mt-1">Escriba por número, cédula, nombre del contratista, estado o fecha.</p>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" id="buscador" name="busqueda" value="{{ $busqueda }}"
                    placeholder="Buscar por número, cédula, nombre, estado o fecha..."
                    class="border border-gray-300 px-4 py-2 rounded-lg w-full md:w-96 focus:ring-2 focus:ring-gray-800 outline-none">
                <button type="button" id="limpiarBuscador"
                    class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 whitespace-nowrap">
                    Limpiar
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('contratos.index') }}"
            class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors {{ empty($filtro) ? 'bg-primary text-white border-primary' : 'bg-white text-primary/70 border-primary/10 hover:bg-primary/5' }}">
            Todos
        </a>
        <a href="{{ route('contratos.index', ['filtro' => 'Vigente', 'busqueda' => $busqueda]) }}"
            class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors {{ $filtro === 'Vigente' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-green-700 border-green-200 hover:bg-green-50' }}">
            Vigentes
        </a>
        <a href="{{ route('contratos.index', ['filtro' => 'Por vencer', 'busqueda' => $busqueda]) }}"
            class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors {{ $filtro === 'Por vencer' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50' }}">
            Por vencer
        </a>
        <a href="{{ route('contratos.index', ['filtro' => 'Vencido', 'busqueda' => $busqueda]) }}"
            class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors {{ $filtro === 'Vencido' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-700 border-red-200 hover:bg-red-50' }}">
            Vencidos
        </a>
    </div>

    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">N.°</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Número de Contrato</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Cédula</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contratista</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-center">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-center">Vigencia</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Descripción</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Responsable</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-contratos" class="divide-y divide-primary/5">
                    @forelse ($contratos as $contrato)
                        <tr class="hover:bg-primary/[0.02] transition-colors">
                            <td class="px-6 py-4 text-xs text-primary/40 font-mono">{{ $contrato->id }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-primary">
                                <a href="{{ route('contratos.show', $contrato) }}" class="hover:underline">
                                    {{ $contrato->numero_contrato }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-primary/70">
                                {{ $contrato->fecha_contrato?->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-primary/70 font-mono">{{ $contrato->cedula_contratista }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $contrato->nombre_contratista }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $estado = strtolower($contrato->estado);
                                    $badge = match (true) {
                                        str_contains($estado, 'activ')   => 'bg-green-100 text-green-700 border-green-200',
                                        str_contains($estado, 'pend')    => 'bg-amber-100 text-amber-700 border-amber-200',
                                        str_contains($estado, 'cancel')  => 'bg-red-100 text-red-700 border-red-200',
                                        str_contains($estado, 'finaliz') => 'bg-slate-100 text-slate-600 border-slate-200',
                                        default => 'bg-primary/10 text-primary border-primary/20',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                    {{ $contrato->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $vigencia = $contrato->estado_vigencia;
                                    $badgeVigencia = match ($vigencia) {
                                        'Vigente'    => 'bg-green-100 text-green-700 border-green-200',
                                        'Por vencer' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'Vencido'    => 'bg-red-100 text-red-700 border-red-200',
                                        default      => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeVigencia }}">
                                    {{ $vigencia }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-primary/60 max-w-xs truncate">{{ $contrato->descripcion }}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">{{ $contrato->createdBy?->name ?? 'No registrado' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('contratos.show', $contrato) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-slate-800 transition-colors">Ver</a>

                                    @if (in_array(auth()->user()->rol, ['admin', 'gestor', 'consulta']))
                                        <span class="text-primary/20">|</span>
                                        <a href="{{ route('documentos.create', $contrato) }}"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">Documentos</a>
                                    @endif

                                    @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                        <span class="text-primary/20">|</span>
                                        <a href="{{ route('contratos.edit', $contrato) }}"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">Editar</a>
                                    @endif

                                    @if (auth()->user()->rol === 'admin')
                                        <span class="text-primary/20">|</span>
                                        <button type="button"
                                            onclick="abrirModalEliminar('{{ route('contratos.destroy', $contrato) }}', '{{ $contrato->numero_contrato }}')"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-red-500 hover:text-red-700 transition-colors">
                                            Eliminar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center">
                                <p class="text-sm text-primary/40 font-medium">No hay contratos registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-between flex-wrap gap-4">
        <p id="contador-contratos" class="text-xs font-medium text-primary/50">
            @if ($contratos->total() > 0)
                Mostrando {{ $contratos->firstItem() }}–{{ $contratos->lastItem() }} de {{ $contratos->total() }}
                {{ $contratos->total() === 1 ? 'contrato' : 'contratos' }}
                @if (!empty($filtro)) — filtro: {{ $filtro }} @endif
            @else
                Sin resultados
            @endif
        </p>
        <div>
            {{ $contratos->withQueryString()->links() }}
        </div>
    </div>
@endsection

@push('modals')
    <div id="modalEliminar"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 transition-opacity duration-200">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-primary/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Confirmar eliminación</h3>
                <p class="text-sm text-primary/50 mt-1">Esta acción eliminará el contrato seleccionado.</p>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-primary/70">Está a punto de eliminar el contrato:</p>
                <p id="contratoEliminarTexto" class="mt-2 text-base font-bold text-red-600"></p>
                <p class="mt-4 text-xs text-primary/40">Esta acción no se puede deshacer.</p>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-primary/10 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEliminar()"
                    class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary/70 hover:bg-primary/5 transition-colors">
                    Cancelar
                </button>
                <form id="formEliminarContrato" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition-colors">
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador            = document.getElementById('buscador');
    const limpiarBuscador     = document.getElementById('limpiarBuscador');
    const tablaContratos      = document.getElementById('tabla-contratos');
    const contadorContratos   = document.getElementById('contador-contratos');
    const modalEliminar       = document.getElementById('modalEliminar');
    const formEliminarContrato= document.getElementById('formEliminarContrato');
    const contratoEliminarTexto = document.getElementById('contratoEliminarTexto');
    const filtroActual        = @json($filtro);
    let timeout = null;

    function obtenerBadgeEstado(estado) {
        const v = (estado || '').toLowerCase();
        if (v.includes('activ'))  return 'bg-green-100 text-green-700 border-green-200';
        if (v.includes('pend'))   return 'bg-amber-100 text-amber-700 border-amber-200';
        if (v.includes('cancel')) return 'bg-red-100 text-red-700 border-red-200';
        if (v.includes('finaliz'))return 'bg-slate-100 text-slate-600 border-slate-200';
        return 'bg-primary/10 text-primary border-primary/20';
    }

    function obtenerBadgeVigencia(vigencia) {
        const v = (vigencia || '').toLowerCase();
        if (v.includes('vigente'))    return 'bg-green-100 text-green-700 border-green-200';
        if (v.includes('por vencer')) return 'bg-amber-100 text-amber-700 border-amber-200';
        if (v.includes('vencido'))    return 'bg-red-100 text-red-700 border-red-200';
        return 'bg-slate-100 text-slate-600 border-slate-200';
    }

    function escapeHtml(text) {
        if (text == null) return '';
        return String(text).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function formatearFecha(fecha) {
        if (!fecha) return '';
        const p = String(fecha).split('T')[0].split('-');
        return p.length === 3 ? `${p[2]}/${p[1]}/${p[0]}` : fecha;
    }

    window.abrirModalEliminar = function(action, numeroContrato) {
        formEliminarContrato.action = action;
        contratoEliminarTexto.textContent = numeroContrato;
        modalEliminar.classList.remove('hidden');
        modalEliminar.classList.add('flex');
    };

    window.cerrarModalEliminar = function() {
        modalEliminar.classList.add('hidden');
        modalEliminar.classList.remove('flex');
    };

    modalEliminar.addEventListener('click', e => { if (e.target === modalEliminar) cerrarModalEliminar(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModalEliminar(); });

    function renderizarTabla(data) {
        tablaContratos.innerHTML = '';
        if (data.length === 0) {
            tablaContratos.innerHTML = `<tr><td colspan="10" class="px-6 py-16 text-center"><p class="text-sm text-primary/40 font-medium">No se encontraron contratos.</p></td></tr>`;
            contadorContratos.textContent = 'Sin resultados';
            return;
        }
        contadorContratos.textContent = `Mostrando ${data.length} ${data.length === 1 ? 'contrato' : 'contratos'}${filtroActual ? ' — filtro: ' + filtroActual : ''}`;
        const puedeGestionar = @json(in_array(auth()->user()->rol, ['admin', 'gestor']));
        const esAdmin = @json(auth()->user()->rol === 'admin');
        data.forEach(c => {
            tablaContratos.innerHTML += `
                <tr class="hover:bg-primary/[0.02] transition-colors">
                    <td class="px-6 py-4 text-xs text-primary/40 font-mono">${escapeHtml(c.id)}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-primary">
                        <a href="/contratos/${escapeHtml(c.id)}" class="hover:underline">${escapeHtml(c.numero_contrato)}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-primary/70">${escapeHtml(formatearFecha(c.fecha_contrato))}</td>
                    <td class="px-6 py-4 text-sm text-primary/70 font-mono">${escapeHtml(c.cedula_contratista)}</td>
                    <td class="px-6 py-4 text-sm text-primary/70">${escapeHtml(c.nombre_contratista)}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${obtenerBadgeEstado(c.estado)}">
                            ${escapeHtml(c.estado)}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${obtenerBadgeVigencia(c.estado_vigencia)}">
                            ${escapeHtml(c.estado_vigencia ?? 'Sin definir')}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-primary/60 max-w-xs truncate">${escapeHtml(c.descripcion)}</td>
                    <td class="px-6 py-4 text-sm text-primary/70">${escapeHtml(c.created_by_name ?? '')}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="/contratos/${escapeHtml(c.id)}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-slate-800 transition-colors">Ver</a>
                            ${puedeGestionar ? `<span class="text-primary/20">|</span><a href="/contratos/${escapeHtml(c.id)}/documentos/create" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">Documentos</a><span class="text-primary/20">|</span><a href="/contratos/${escapeHtml(c.id)}/edit" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">Editar</a>` : ''}
                            ${esAdmin ? `<span class="text-primary/20">|</span><button type="button" onclick="abrirModalEliminar('/contratos/${escapeHtml(c.id)}','${escapeHtml(c.numero_contrato)}')" class="inline-flex items-center gap-1 text-xs font-bold text-red-500 hover:text-red-700 transition-colors">Eliminar</button>` : ''}
                        </div>
                    </td>
                </tr>`;
        });
    }

    function buscarContratos(valor) {
        const params = new URLSearchParams();
        if (valor) params.append('busqueda', valor);
        if (filtroActual) params.append('filtro', filtroActual);
        fetch(`/contratos/buscar?${params.toString()}`)
            .then(r => r.json())
            .then(data => renderizarTabla(data))
            .catch(err => console.error('Error al buscar contratos:', err));
    }

    buscador.addEventListener('keyup', function() {
        clearTimeout(timeout);
        const valor = this.value;
        timeout = setTimeout(() => buscarContratos(valor), 300);
    });

    limpiarBuscador.addEventListener('click', function() {
        buscador.value = '';
        buscarContratos('');
        buscador.focus();
    });
});
</script>
@endpush
