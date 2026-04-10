<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratos - SALAZAR & DÍAZ S.A.S</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#1a2a47",
                        "background-light": "#f6f7f8"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style>
        .toast {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
            animation: toastIn 0.3s ease-out forwards;
        }

        .toast-out {
            animation: toastOut 0.2s ease-in forwards;
        }

        .toast-bar {
            transform-origin: left;
            animation: toastTimer 5s linear forwards;
        }

        @keyframes toastIn {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes toastOut {
            to {
                opacity: 0;
                transform: translateY(-8px) scale(0.98);
            }
        }

        @keyframes toastTimer {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }
    </style>
</head>

<body class="bg-background-light font-display text-slate-900 antialiased min-h-screen">

    @if (session('success') || session('error') || $errors->any())
        <div id="toastContainer" class="fixed top-5 right-5 z-[9999] space-y-3 w-full max-w-sm pointer-events-none">
            @if (session('success'))
                <div
                    class="toast pointer-events-auto rounded-xl border border-green-200 bg-white shadow-lg overflow-hidden">
                    <div class="flex items-start gap-3 p-4">
                        <div
                            class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 font-bold">
                            ✓
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Operación exitosa</p>
                            <p class="text-sm text-slate-600 mt-1">{{ session('success') }}</p>
                        </div>
                        <button type="button" onclick="cerrarToast(this)"
                            class="text-slate-400 hover:text-slate-700 text-lg leading-none">
                            ×
                        </button>
                    </div>
                    <div class="h-1 bg-green-500 toast-bar"></div>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="toast pointer-events-auto rounded-xl border border-red-200 bg-white shadow-lg overflow-hidden">
                    <div class="flex items-start gap-3 p-4">
                        <div
                            class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600 font-bold">
                            !
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Ocurrió un problema</p>
                            <p class="text-sm text-slate-600 mt-1">{{ session('error') }}</p>
                        </div>
                        <button type="button" onclick="cerrarToast(this)"
                            class="text-slate-400 hover:text-slate-700 text-lg leading-none">
                            ×
                        </button>
                    </div>
                    <div class="h-1 bg-red-500 toast-bar"></div>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="toast pointer-events-auto rounded-xl border border-amber-200 bg-white shadow-lg overflow-hidden">
                    <div class="flex items-start gap-3 p-4">
                        <div
                            class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600 font-bold">
                            !
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Hay errores en el formulario</p>
                            <p class="text-sm text-slate-600 mt-1">{{ $errors->first() }}</p>
                        </div>
                        <button type="button" onclick="cerrarToast(this)"
                            class="text-slate-400 hover:text-slate-700 text-lg leading-none">
                            ×
                        </button>
                    </div>
                    <div class="h-1 bg-amber-500 toast-bar"></div>
                </div>
            @endif
        </div>
    @endif

    <div class="flex h-screen overflow-hidden">

        <x-sidebar :contrato="$contrato ?? null" :documento="$documento ?? null" />

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <h2 class="text-2xl font-bold text-primary tracking-tight">Listado de Contratos</h2>

                @auth
                    @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                        <a href="{{ route('contratos.create') }}"
                            class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-lg transition-all shadow-sm">
                            <span class="text-sm font-bold tracking-wide">Nuevo Contrato</span>
                        </a>
                    @endif
                @endauth
            </header>

            <div class="flex-1 overflow-y-auto p-8">

                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-primary">Buscar contratos</h3>
                            <p class="text-sm text-primary/50 mt-1">
                                Escriba por número, cédula, nombre del contratista, estado o fecha.
                            </p>
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
                        class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors
                        {{ empty($filtro) ? 'bg-primary text-white border-primary' : 'bg-white text-primary/70 border-primary/10 hover:bg-primary/5' }}">
                        Todos
                    </a>

                    <a href="{{ route('contratos.index', ['filtro' => 'Vigente', 'busqueda' => $busqueda]) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors
                        {{ $filtro === 'Vigente' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-green-700 border-green-200 hover:bg-green-50' }}">
                        Vigentes
                    </a>

                    <a href="{{ route('contratos.index', ['filtro' => 'Por vencer', 'busqueda' => $busqueda]) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors
                        {{ $filtro === 'Por vencer' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50' }}">
                        Por vencer
                    </a>

                    <a href="{{ route('contratos.index', ['filtro' => 'Vencido', 'busqueda' => $busqueda]) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors
                        {{ $filtro === 'Vencido' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-700 border-red-200 hover:bg-red-50' }}">
                        Vencidos
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-primary/5">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        N.°
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Número de Contrato
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Cédula
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Contratista
                                    </th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-center">
                                        Estado
                                    </th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-center">
                                        Vigencia
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Descripción
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Responsable
                                    </th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="tabla-contratos" class="divide-y divide-primary/5">
                                @forelse ($contratos as $contrato)
                                    <tr class="hover:bg-primary/[0.02] transition-colors">
                                        <td class="px-6 py-4 text-xs text-primary/40 font-mono">{{ $contrato->id }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-semibold text-primary">
                                            <a href="{{ route('contratos.show', $contrato) }}"
                                                class="hover:underline">
                                                {{ $contrato->numero_contrato }}
                                            </a>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            {{ $contrato->fecha_contrato ? \Carbon\Carbon::parse($contrato->fecha_contrato)->format('d/m/Y') : '' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/70 font-mono">
                                            {{ $contrato->cedula_contratista }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            {{ $contrato->nombre_contratista }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $estado = strtolower($contrato->estado);
                                                $badge = match (true) {
                                                    str_contains($estado, 'activ')
                                                        => 'bg-green-100 text-green-700 border-green-200',
                                                    str_contains($estado, 'pend')
                                                        => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    str_contains($estado, 'cancel')
                                                        => 'bg-red-100 text-red-700 border-red-200',
                                                    str_contains($estado, 'finaliz')
                                                        => 'bg-slate-100 text-slate-600 border-slate-200',
                                                    default => 'bg-primary/10 text-primary border-primary/20',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                {{ $contrato->estado }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $vigencia = $contrato->estado_vigencia ?? 'Sin definir';
                                                $badgeVigencia = match ($vigencia) {
                                                    'Vigente' => 'bg-green-100 text-green-700 border-green-200',
                                                    'Por vencer' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    'Vencido' => 'bg-red-100 text-red-700 border-red-200',
                                                    default => 'bg-slate-100 text-slate-600 border-slate-200',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeVigencia }}">
                                                {{ $vigencia }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/60 max-w-xs truncate">
                                            {{ $contrato->descripcion }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            {{ $contrato->createdBy?->name ?? 'No registrado' }}
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('contratos.show', $contrato) }}"
                                                    class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-slate-800 transition-colors">
                                                    Ver
                                                </a>

                                                @if (in_array(auth()->user()->rol, ['admin', 'gestor', 'consulta']))
                                                    <span class="text-primary/20">|</span>

                                                    <a href="{{ route('documentos.create', $contrato) }}"
                                                        class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">
                                                        Documentos
                                                    </a>
                                                @endif

                                                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                                    <span class="text-primary/20">|</span>

                                                    <a href="{{ route('contratos.edit', $contrato) }}"
                                                        class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">
                                                        Editar
                                                    </a>
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
                                            <p class="text-sm text-primary/40 font-medium">No hay contratos
                                                registrados.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between">
                    <p id="contador-contratos" class="text-xs font-medium text-primary/50">
                        @if ($contratos->count() > 0)
                            Mostrando {{ $contratos->count() }}
                            {{ $contratos->count() === 1 ? 'contrato' : 'contratos' }}
                            @if (!empty($filtro))
                                - filtro: {{ $filtro }}
                            @endif
                        @else
                            Sin resultados
                        @endif
                    </p>
                </div>

            </div>
        </main>
    </div>

    <div id="modalEliminar"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 transition-opacity duration-200">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-primary/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Confirmar eliminación</h3>
                <p class="text-sm text-primary/50 mt-1">
                    Esta acción eliminará el contrato seleccionado.
                </p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscador');
            const limpiarBuscador = document.getElementById('limpiarBuscador');
            const tablaContratos = document.getElementById('tabla-contratos');
            const contadorContratos = document.getElementById('contador-contratos');
            const modalEliminar = document.getElementById('modalEliminar');
            const formEliminarContrato = document.getElementById('formEliminarContrato');
            const contratoEliminarTexto = document.getElementById('contratoEliminarTexto');
            const filtroActual = @json($filtro);

            let timeout = null;

            function obtenerBadgeEstado(estado) {
                const valor = (estado || '').toLowerCase();
                if (valor.includes('activ')) return 'bg-green-100 text-green-700 border-green-200';
                if (valor.includes('pend')) return 'bg-amber-100 text-amber-700 border-amber-200';
                if (valor.includes('cancel')) return 'bg-red-100 text-red-700 border-red-200';
                if (valor.includes('finaliz')) return 'bg-slate-100 text-slate-600 border-slate-200';
                return 'bg-primary/10 text-primary border-primary/20';
            }

            function obtenerBadgeVigencia(vigencia) {
                const valor = (vigencia || '').toLowerCase();
                if (valor.includes('vigente')) return 'bg-green-100 text-green-700 border-green-200';
                if (valor.includes('por vencer')) return 'bg-amber-100 text-amber-700 border-amber-200';
                if (valor.includes('vencido')) return 'bg-red-100 text-red-700 border-red-200';
                return 'bg-slate-100 text-slate-600 border-slate-200';
            }

            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function formatearFecha(fecha) {
                if (!fecha) return '';
                const fechaLimpia = String(fecha).split('T')[0];
                const partes = fechaLimpia.split('-');
                if (partes.length === 3) return `${partes[2]}/${partes[1]}/${partes[0]}`;
                return fecha;
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

            modalEliminar.addEventListener('click', function(e) {
                if (e.target === modalEliminar) cerrarModalEliminar();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') cerrarModalEliminar();
            });

            function renderizarTabla(data) {
                tablaContratos.innerHTML = '';

                if (data.length === 0) {
                    tablaContratos.innerHTML = `
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center">
                                <p class="text-sm text-primary/40 font-medium">No se encontraron contratos.</p>
                            </td>
                        </tr>
                    `;
                    contadorContratos.textContent = 'Sin resultados';
                    return;
                }

                contadorContratos.textContent =
                    `Mostrando ${data.length} ${data.length === 1 ? 'contrato' : 'contratos'}${filtroActual ? ' - filtro: ' + filtroActual : ''}`;

                data.forEach(contrato => {
                    const badgeEstado = obtenerBadgeEstado(contrato.estado);
                    const badgeVigencia = obtenerBadgeVigencia(contrato.estado_vigencia);

                    const puedeGestionar = ['admin', 'gestor'].includes(@json(auth()->user()->rol));
                    const esAdmin = @json(auth()->user()->rol) === 'admin';

                    tablaContratos.innerHTML += `
                        <tr class="hover:bg-primary/[0.02] transition-colors">
                            <td class="px-6 py-4 text-xs text-primary/40 font-mono">${escapeHtml(contrato.id ?? '')}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-primary">
                                <a href="/contratos/${escapeHtml(contrato.id ?? '')}" class="hover:underline">
                                    ${escapeHtml(contrato.numero_contrato ?? '')}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-primary/70">${escapeHtml(formatearFecha(contrato.fecha_contrato ?? ''))}</td>
                            <td class="px-6 py-4 text-sm text-primary/70 font-mono">${escapeHtml(contrato.cedula_contratista ?? '')}</td>
                            <td class="px-6 py-4 text-sm text-primary/70">${escapeHtml(contrato.nombre_contratista ?? '')}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${badgeEstado}">
                                    ${escapeHtml(contrato.estado ?? '')}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${badgeVigencia}">
                                    ${escapeHtml(contrato.estado_vigencia ?? 'Sin definir')}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-primary/60 max-w-xs truncate">${escapeHtml(contrato.descripcion ?? '')}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="/contratos/${escapeHtml(contrato.id ?? '')}"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-slate-800 transition-colors">
                                        Ver
                                    </a>
                                    ${puedeGestionar ? `
                                                        <span class="text-primary/20">|</span>
                                                        <a href="/contratos/${escapeHtml(contrato.id ?? '')}/documentos/create"
                                                            class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">
                                                            Documentos
                                                        </a>
                                                        <span class="text-primary/20">|</span>
                                                        <a href="/contratos/${escapeHtml(contrato.id ?? '')}/edit"
                                                            class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary/70 transition-colors">
                                                            Editar
                                                        </a>
                                                    ` : ''}
                                    ${esAdmin ? `
                                                        <span class="text-primary/20">|</span>
                                                        <button type="button"
                                                            onclick="abrirModalEliminar('/contratos/${escapeHtml(contrato.id ?? '')}', '${escapeHtml(contrato.numero_contrato ?? '')}')"
                                                            class="inline-flex items-center gap-1 text-xs font-bold text-red-500 hover:text-red-700 transition-colors">
                                                            Eliminar
                                                        </button>
                                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            function buscarContratos(valor) {
                const params = new URLSearchParams();
                if (valor) params.append('busqueda', valor);
                if (filtroActual) params.append('filtro', filtroActual);

                fetch(`/contratos/buscar?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => renderizarTabla(data))
                    .catch(error => console.error('Error al buscar contratos:', error));
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

            window.cerrarToast = function(boton) {
                const toast = boton.closest('.toast');
                if (!toast) return;

                toast.classList.add('toast-out');
                setTimeout(() => toast.remove(), 200);
            };

            document.querySelectorAll('.toast').forEach((toast) => {
                setTimeout(() => {
                    toast.classList.add('toast-out');
                    setTimeout(() => toast.remove(), 200);
                }, 5000);
            });
        });
    </script>

</body>

</html>



