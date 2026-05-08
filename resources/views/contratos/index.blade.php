@extends('layouts.app')
@section('title', 'Contratos')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Listado de Contratos</h2>
        <p class="text-sm text-primary/50 mt-1">Búsqueda, filtros y administración del expediente contractual.</p>
    </div>
    @auth
        @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
            <x-button :href="route('contratos.create')">Nuevo Contrato</x-button>
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

    <x-card :padded="false">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-primary/10 border-b border-primary/10">
                        <th class="hidden xl:table-cell px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">N.°</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Número</th>
                        <th class="hidden md:table-cell px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha</th>
                        <th class="hidden xl:table-cell px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Cédula</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contratista</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-center">Estado</th>
                        <th class="hidden lg:table-cell px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-center">Vigencia</th>
                        <th class="hidden 2xl:table-cell px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Descripción</th>
                        <th class="hidden 2xl:table-cell px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Responsable</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-contratos" class="divide-y divide-primary/5">
                    @forelse ($contratos as $contrato)
                        <tr class="hover:bg-primary/[0.02] transition-colors">
                            <td class="hidden xl:table-cell px-6 py-4 text-xs text-primary/40 font-mono">{{ $contrato->id }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-primary">
                                <a href="{{ route('contratos.show', $contrato) }}" class="hover:underline">
                                    {{ $contrato->numero_contrato }}
                                </a>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 text-sm text-primary/70 whitespace-nowrap">
                                {{ $contrato->fecha_contrato?->format('d/m/Y') }}
                            </td>
                            <td class="hidden xl:table-cell px-6 py-4 text-sm text-primary/70 font-mono">{{ $contrato->cedula_contratista }}</td>
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
                            <td class="hidden lg:table-cell px-6 py-4 text-center">
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
                            <td class="hidden 2xl:table-cell px-6 py-4 text-sm text-primary/60 max-w-xs truncate">{{ $contrato->descripcion }}</td>
                            <td class="hidden 2xl:table-cell px-6 py-4 text-sm text-primary/70">{{ $contrato->createdBy?->name ?? 'No registrado' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    <a href="{{ route('contratos.show', $contrato) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/5 border border-primary/10 text-primary text-xs font-semibold hover:bg-primary/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Ver
                                    </a>

                                    @if (in_array(auth()->user()->rol, ['admin', 'gestor', 'consulta']) || auth()->user()->rol === 'admin')
                                        <span class="h-5 w-px bg-primary/10"></span>
                                    @endif

                                    @if (in_array(auth()->user()->rol, ['admin', 'gestor', 'consulta']))
                                        <a href="{{ route('documentos.create', $contrato) }}" title="Documentos"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-primary/10 bg-white text-primary/70 hover:bg-primary/5 hover:text-primary transition-colors">
                                            <span class="sr-only">Documentos</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                            </svg>
                                        </a>
                                    @endif

                                    @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                        <a href="{{ route('contratos.edit', $contrato) }}" title="Editar contrato"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                            <span class="sr-only">Editar</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endif

                                    @if (auth()->user()->rol === 'admin')
                                        <button type="button" title="Eliminar contrato"
                                            data-confirmar
                                            data-confirmar-action="{{ route('contratos.destroy', $contrato) }}"
                                            data-confirmar-method="DELETE"
                                            data-confirmar-titulo="Eliminar contrato"
                                            data-confirmar-subtitulo="Esta acción no se puede deshacer."
                                            data-confirmar-mensaje="Estás a punto de eliminar el contrato:"
                                            data-confirmar-detalle="{{ $contrato->numero_contrato }}"
                                            data-confirmar-confirm-texto="Sí, eliminar"
                                            data-confirmar-color="red"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                            <span class="sr-only">Eliminar</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-0 py-0">
                                <x-empty-state icon="document" title="No hay contratos registrados"
                                    description="Crea el primer contrato para comenzar." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador            = document.getElementById('buscador');
    const limpiarBuscador     = document.getElementById('limpiarBuscador');
    const tablaContratos      = document.getElementById('tabla-contratos');
    const contadorContratos   = document.getElementById('contador-contratos');
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
                    <td class="hidden xl:table-cell px-6 py-4 text-xs text-primary/40 font-mono">${escapeHtml(c.id)}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-primary">
                        <a href="/contratos/${escapeHtml(c.id)}" class="hover:underline">${escapeHtml(c.numero_contrato)}</a>
                    </td>
                    <td class="hidden md:table-cell px-6 py-4 text-sm text-primary/70 whitespace-nowrap">${escapeHtml(formatearFecha(c.fecha_contrato))}</td>
                    <td class="hidden xl:table-cell px-6 py-4 text-sm text-primary/70 font-mono">${escapeHtml(c.cedula_contratista)}</td>
                    <td class="px-6 py-4 text-sm text-primary/70">${escapeHtml(c.nombre_contratista)}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${obtenerBadgeEstado(c.estado)}">
                            ${escapeHtml(c.estado)}
                        </span>
                    </td>
                    <td class="hidden lg:table-cell px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${obtenerBadgeVigencia(c.estado_vigencia)}">
                            ${escapeHtml(c.estado_vigencia ?? 'Sin definir')}
                        </span>
                    </td>
                    <td class="hidden 2xl:table-cell px-6 py-4 text-sm text-primary/60 max-w-xs truncate">${escapeHtml(c.descripcion)}</td>
                    <td class="hidden 2xl:table-cell px-6 py-4 text-sm text-primary/70">${escapeHtml(c.created_by_name ?? '')}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                            <a href="/contratos/${escapeHtml(c.id)}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/5 border border-primary/10 text-primary text-xs font-semibold hover:bg-primary/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                Ver
                            </a>
                            ${puedeGestionar || esAdmin ? `<span class="h-5 w-px bg-primary/10"></span>` : ''}
                            ${puedeGestionar ? `
                                <a href="/contratos/${escapeHtml(c.id)}/documentos/create" title="Documentos" class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-primary/10 bg-white text-primary/70 hover:bg-primary/5 hover:text-primary transition-colors">
                                    <span class="sr-only">Documentos</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
                                </a>
                                <a href="/contratos/${escapeHtml(c.id)}/edit" title="Editar contrato" class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                    <span class="sr-only">Editar</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                </a>` : ''}
                            ${esAdmin ? `
                                <button type="button" title="Eliminar contrato"
                                    data-confirmar
                                    data-confirmar-action="/contratos/${escapeHtml(c.id)}"
                                    data-confirmar-method="DELETE"
                                    data-confirmar-titulo="Eliminar contrato"
                                    data-confirmar-subtitulo="Esta acción no se puede deshacer."
                                    data-confirmar-mensaje="Estás a punto de eliminar el contrato:"
                                    data-confirmar-detalle="${escapeHtml(c.numero_contrato)}"
                                    data-confirmar-confirm-texto="Sí, eliminar"
                                    data-confirmar-color="red"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                    <span class="sr-only">Eliminar</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </button>` : ''}
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
