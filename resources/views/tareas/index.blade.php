@extends('layouts.app')
@section('title', 'Tareas')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Tareas</h2>
        <p class="text-sm text-primary/50 mt-1">Seguimiento general de pendientes, vencimientos y responsables.</p>
    </div>
@endsection

@section('content')
    @php
        $puedeEditar = in_array(auth()->user()->rol, ['admin', 'gestor']);
    @endphp
    <div class="space-y-6">
        <x-card>
            <form method="GET" action="{{ route('tareas.index') }}"
                class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Estado</label>
                    <select name="estado" class="rounded-lg border border-primary/10 px-4 py-2 text-sm bg-white outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        <option value="">Todos</option>
                        @foreach (['Pendiente', 'Por vencer', 'Vencida', 'Completada'] as $opcion)
                            <option value="{{ $opcion }}" @selected($estado === $opcion)>{{ $opcion }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($puedeVerTodas)
                    <div>
                        <label
                            class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Responsable</label>
                        <select name="responsable" class="rounded-lg border border-primary/10 px-4 py-2 text-sm bg-white outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            <option value="">Todos</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" @selected((string) $responsable === (string) $usuario->id)>{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <x-button type="submit">Filtrar</x-button>
                <x-button variant="secondary" :href="route('tareas.index')">Limpiar</x-button>
            </form>
        </x-card>

        <x-card :padded="false">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary/5">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Tarea</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Documento</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Responsable
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha límite
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Estado</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/5">
                        @forelse ($tareas as $tarea)
                            @php
                                $vencida = $tarea->estado !== 'Completada' && $tarea->fecha_limite?->isPast();
                                $porVencer =
                                    $tarea->estado !== 'Completada' &&
                                    !$vencida &&
                                    $tarea->fecha_limite?->lte(now()->addDays(2));
                                $badge = match (true) {
                                    $tarea->estado === 'Completada' => 'bg-green-100 text-green-700 border-green-200',
                                    $vencida => 'bg-red-100 text-red-700 border-red-200',
                                    $porVencer => 'bg-amber-100 text-amber-700 border-amber-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                                $estadoVisible = $vencida ? 'Vencida' : ($porVencer ? 'Por vencer' : $tarea->estado);
                                $descripcionCorta = $tarea->descripcion
                                    ? \Illuminate\Support\Str::limit($tarea->descripcion, 60, '...')
                                    : 'Sin descripción';
                                $tareaPayload = [
                                    'titulo' => $tarea->titulo,
                                    'descripcion' => $tarea->descripcion ?? '',
                                    'estado' => $estadoVisible,
                                    'fecha_limite' => $tarea->fecha_limite?->format('Y-m-d') ?? '',
                                    'fecha_limite_humana' => $tarea->fecha_limite?->format('d/m/Y') ?? 'Sin fecha',
                                    'responsable' => $tarea->assignedTo?->name ?? 'No asignado',
                                    'assigned_to' => $tarea->assigned_to ?? '',
                                    'contrato' => $tarea->contrato?->numero_contrato ?? 'Sin contrato',
                                    'documento' => $tarea->documento
                                        ? ($tarea->documento->nombre_original ?? $tarea->documento->nombre_documento)
                                        : 'Sin documento',
                                    'update_url' => $puedeEditar ? route('tareas.update', $tarea) : '',
                                ];
                            @endphp
                            <tr class="hover:bg-primary/[0.02]">
                                <td class="px-6 py-4 max-w-sm">
                                    <p class="text-sm font-semibold text-primary">{{ $tarea->titulo }}</p>
                                    <p class="text-xs text-primary/50 mt-1 break-words">{{ $descripcionCorta }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-primary/70">
                                    @if ($tarea->contrato)
                                        <a href="{{ route('contratos.show', $tarea->contrato) }}"
                                            class="font-semibold text-primary hover:underline">
                                            {{ $tarea->contrato->numero_contrato }}
                                        </a>
                                    @else
                                        Sin contrato
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-primary/70">
                                    {{ $tarea->documento ? $tarea->documento->nombre_original ?? $tarea->documento->nombre_documento : 'Sin documento' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-primary/70">
                                    {{ $tarea->assignedTo?->name ?? 'No asignado' }}</td>
                                <td class="px-6 py-4 text-sm text-primary/70">{{ $tarea->fecha_limite?->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                        {{ $estadoVisible }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        @if ($tarea->estado !== 'Completada')
                                            <form action="{{ route('tareas.complete', $tarea) }}" method="POST"
                                                class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs font-semibold hover:bg-green-100 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.5 7.5a1 1 0 01-1.42 0l-3.5-3.5a1 1 0 011.42-1.42L8.5 12.08l6.79-6.79a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Completar
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" data-confirmar
                                                data-confirmar-action="{{ route('tareas.reabrir', $tarea) }}"
                                                data-confirmar-method="PATCH" data-confirmar-titulo="Revertir tarea"
                                                data-confirmar-subtitulo="La tarea volverá al estado Pendiente."
                                                data-confirmar-mensaje="Estás a punto de cancelar la finalización de la tarea:"
                                                data-confirmar-detalle="{{ $tarea->titulo }}"
                                                data-confirmar-confirm-texto="Sí, revertir" data-confirmar-color="blue"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold hover:bg-amber-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.793 2.232a.75.75 0 01-.025 1.06L3.622 7.25h10.003a5.375 5.375 0 010 10.75H10.75a.75.75 0 010-1.5h2.875a3.875 3.875 0 000-7.75H3.622l4.146 3.957a.75.75 0 01-1.036 1.085l-5.5-5.25a.75.75 0 010-1.085l5.5-5.25a.75.75 0 011.06.025z" clip-rule="evenodd" />
                                                </svg>
                                                Reabrir
                                            </button>
                                        @endif

                                        <span class="h-5 w-px bg-primary/10"></span>

                                        <button type="button" data-ver-tarea
                                            data-tarea='@json($tareaPayload)' title="Ver detalle"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-primary/10 bg-white text-primary/70 hover:bg-primary/5 hover:text-primary transition-colors">
                                            <span class="sr-only">Ver</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        @if ($puedeEditar)
                                            <button type="button" data-editar-tarea
                                                data-tarea='@json($tareaPayload)' title="Editar tarea"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                                <span class="sr-only">Editar</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <button type="button" data-confirmar
                                                data-confirmar-action="{{ route('tareas.destroy', $tarea) }}"
                                                data-confirmar-method="DELETE"
                                                data-confirmar-titulo="Eliminar tarea"
                                                data-confirmar-subtitulo="Esta acción no se puede deshacer."
                                                data-confirmar-mensaje="Estás a punto de eliminar la tarea:"
                                                data-confirmar-detalle="{{ $tarea->titulo }}"
                                                data-confirmar-confirm-texto="Sí, eliminar"
                                                data-confirmar-color="red" title="Eliminar tarea"
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
                                <td colspan="7" class="px-0 py-0">
                                    <x-empty-state icon="task" title="Sin tareas registradas"
                                        description="Cuando se asignen tareas aparecerán acá." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
@endsection

@push('modals')
    <div id="modalVerTarea"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 transition-opacity duration-200">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-primary/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-primary">Detalle de la tarea</h3>
                    <p class="text-sm text-primary/50 mt-1">Información completa registrada.</p>
                </div>
                <button type="button" onclick="cerrarModalVerTarea()"
                    class="text-primary/40 hover:text-primary">&times;</button>
            </div>
            <div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Título</p>
                    <p id="verTareaTitulo" class="text-base font-semibold text-primary mt-1"></p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Descripción</p>
                    <p id="verTareaDescripcion" class="text-sm text-primary/80 mt-1 whitespace-pre-wrap break-words"></p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Estado</p>
                        <p id="verTareaEstado" class="text-sm font-semibold text-primary mt-1"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Fecha límite</p>
                        <p id="verTareaFecha" class="text-sm text-primary mt-1"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Responsable</p>
                        <p id="verTareaResponsable" class="text-sm text-primary mt-1"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Contrato</p>
                        <p id="verTareaContrato" class="text-sm text-primary mt-1"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Documento</p>
                        <p id="verTareaDocumento" class="text-sm text-primary mt-1 break-words"></p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-primary/10 flex justify-end">
                <button type="button" onclick="cerrarModalVerTarea()"
                    class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary/70 hover:bg-primary/5">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    @if ($puedeEditar)
        <div id="modalEditarTarea"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 transition-opacity duration-200">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-primary/10 overflow-hidden">
                <form id="formEditarTarea" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 border-b border-primary/10 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-primary">Editar tarea</h3>
                            <p class="text-sm text-primary/50 mt-1">Actualiza los datos y guarda los cambios.</p>
                        </div>
                        <button type="button" onclick="cerrarModalEditarTarea()"
                            class="text-primary/40 hover:text-primary">&times;</button>
                    </div>
                    <div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Título</label>
                            <input type="text" name="titulo" id="editarTareaTitulo"
                                class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Descripción</label>
                            <textarea name="descripcion" id="editarTareaDescripcion" rows="4"
                                class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30 resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Fecha límite</label>
                                <input type="date" name="fecha_limite" id="editarTareaFecha"
                                    class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30"
                                    required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Responsable</label>
                                <select name="assigned_to" id="editarTareaResponsable"
                                    class="w-full rounded-lg border border-primary/10 px-3 py-2.5 text-sm outline-none focus:border-primary/30">
                                    <option value="">Sin responsable asignado</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-primary/10 flex justify-end gap-3">
                        <button type="button" onclick="cerrarModalEditarTarea()"
                            class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary/70 hover:bg-primary/5">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary/90">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endpush

@push('scripts')
    <script>
        (function () {
            const modalVer = document.getElementById('modalVerTarea');
            const modalEditar = document.getElementById('modalEditarTarea');
            const formEditar = document.getElementById('formEditarTarea');

            window.cerrarModalVerTarea = function () {
                modalVer.classList.add('hidden');
                modalVer.classList.remove('flex');
            };

            window.cerrarModalEditarTarea = function () {
                if (!modalEditar) return;
                modalEditar.classList.add('hidden');
                modalEditar.classList.remove('flex');
            };

            function abrirModalVer(data) {
                document.getElementById('verTareaTitulo').textContent = data.titulo || '';
                document.getElementById('verTareaDescripcion').textContent = data.descripcion && data.descripcion.length
                    ? data.descripcion
                    : 'Sin descripción';
                document.getElementById('verTareaEstado').textContent = data.estado || '';
                document.getElementById('verTareaFecha').textContent = data.fecha_limite_humana || '';
                document.getElementById('verTareaResponsable').textContent = data.responsable || '';
                document.getElementById('verTareaContrato').textContent = data.contrato || '';
                document.getElementById('verTareaDocumento').textContent = data.documento || '';
                modalVer.classList.remove('hidden');
                modalVer.classList.add('flex');
            }

            function abrirModalEditar(data) {
                if (!modalEditar || !formEditar) return;
                formEditar.action = data.update_url || '#';
                document.getElementById('editarTareaTitulo').value = data.titulo || '';
                document.getElementById('editarTareaDescripcion').value = data.descripcion || '';
                document.getElementById('editarTareaFecha').value = data.fecha_limite || '';
                document.getElementById('editarTareaResponsable').value = data.assigned_to || '';
                modalEditar.classList.remove('hidden');
                modalEditar.classList.add('flex');
            }

            document.addEventListener('click', function (e) {
                const verBtn = e.target.closest('[data-ver-tarea]');
                if (verBtn) {
                    e.preventDefault();
                    try { abrirModalVer(JSON.parse(verBtn.dataset.tarea)); } catch (err) {}
                    return;
                }
                const editarBtn = e.target.closest('[data-editar-tarea]');
                if (editarBtn) {
                    e.preventDefault();
                    try { abrirModalEditar(JSON.parse(editarBtn.dataset.tarea)); } catch (err) {}
                }
            });

            modalVer.addEventListener('click', e => { if (e.target === modalVer) cerrarModalVerTarea(); });
            if (modalEditar) {
                modalEditar.addEventListener('click', e => { if (e.target === modalEditar) cerrarModalEditarTarea(); });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape') return;
                if (!modalVer.classList.contains('hidden')) cerrarModalVerTarea();
                if (modalEditar && !modalEditar.classList.contains('hidden')) cerrarModalEditarTarea();
            });
        })();
    </script>
@endpush
