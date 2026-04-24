@extends('layouts.app')
@section('title', 'Editar Documento')

@php
    use App\Enums\EstadoDocumento;
@endphp

@section('header')
    <div>
        <div class="flex items-center gap-2 text-xs text-primary/40 mb-1">
            <a href="{{ route('contratos.index') }}" class="hover:text-primary transition-colors">Contratos</a>
            <span>/</span>
            <a href="{{ route('documentos.create', $documento->contrato_id) }}" class="hover:text-primary transition-colors">Documentos</a>
            <span>/</span>
            <span class="text-primary/70 font-medium">Editar documento</span>
        </div>
        <h2 class="text-2xl font-bold text-primary tracking-tight">Editar Documento</h2>
    </div>

    <span class="text-xs font-mono text-primary/30 border border-primary/10 rounded px-2 py-1">
        Registro N.° {{ $documento->id }}
    </span>
@endsection

@section('content')
@php
    $extension = strtolower(pathinfo($documento->archivo, PATHINFO_EXTENSION));

    $badgeTipo = match ($extension) {
        'pdf' => 'bg-red-100 text-red-700 border-red-200',
        'doc', 'docx' => 'bg-blue-100 text-blue-700 border-blue-200',
        'xls', 'xlsx' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'jpg', 'jpeg', 'png', 'webp' => 'bg-purple-100 text-purple-700 border-purple-200',
        'zip', 'rar' => 'bg-orange-100 text-orange-700 border-orange-200',
        'txt' => 'bg-slate-100 text-slate-700 border-slate-200',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
    };
@endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <div class="xl:col-span-2">
        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-primary/5">
                <p class="text-sm text-primary/50">Modifique los datos del documento y guarde los cambios.</p>
            </div>

            <form action="{{ route('documentos.update', $documento) }}" method="POST"
                enctype="multipart/form-data" class="px-8 py-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre_documento"
                            class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                            Nombre del documento
                        </label>
                        <input type="text" name="nombre_documento" id="nombre_documento"
                            value="{{ old('nombre_documento', $documento->nombre_documento) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-lg text-sm outline-none focus:border-primary/30">
                    </div>

                    <div>
                        <label for="categoria"
                            class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                            Categoría
                        </label>
                        <select name="categoria" id="categoria"
                            class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-lg text-sm outline-none focus:border-primary/30">
                            <option value="Contrato" @selected(old('categoria', $documento->categoria) == 'Contrato')>Contrato</option>
                            <option value="Actos Administrativos" @selected(old('categoria', $documento->categoria) == 'Actos Administrativos')>Actos Administrativos</option>
                            <option value="Seguridad Social" @selected(old('categoria', $documento->categoria) == 'Seguridad Social')>Seguridad Social</option>
                            <option value="Pagos" @selected(old('categoria', $documento->categoria) == 'Pagos')>Pagos</option>
                            <option value="Otros" @selected(old('categoria', $documento->categoria) == 'Otros')>Otros</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_carga"
                            class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                            Fecha de carga
                        </label>
                        <input type="date" name="fecha_carga" id="fecha_carga"
                            value="{{ old('fecha_carga', $documento->fecha_carga?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-lg text-sm outline-none focus:border-primary/30">
                    </div>

                    <div>
                        <label for="estado"
                            class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                            Estado
                        </label>
                        <select name="estado" id="estado"
                            class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-lg text-sm outline-none focus:border-primary/30">
                            @foreach (EstadoDocumento::values() as $estadoDocumento)
                                @continue($estadoDocumento === EstadoDocumento::Activo->value && old('estado', $documento->estado) !== EstadoDocumento::Activo->value)
                                <option value="{{ $estadoDocumento }}" @selected(old('estado', $documento->estado) === $estadoDocumento)>
                                    {{ $estadoDocumento }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="etiqueta"
                            class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                            Etiqueta
                        </label>
                        <select name="etiqueta" id="etiqueta"
                            class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-lg text-sm outline-none focus:border-primary/30">
                            <option value="">Sin etiqueta</option>
                            @foreach (['Pendiente', 'Falta firma', 'Falta revisar', 'Completo'] as $etiqueta)
                                <option value="{{ $etiqueta }}" @selected(old('etiqueta', $documento->etiqueta) === $etiqueta)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="descripcion"
                        class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                        Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                        class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-lg text-sm resize-none outline-none focus:border-primary/30">{{ old('descripcion', $documento->descripcion) }}</textarea>
                </div>

                <div>
                    <label for="archivo"
                        class="block text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">
                        Reemplazar archivo
                    </label>

                    <div class="rounded-xl border-2 border-dashed border-primary/20 bg-primary/[0.02] px-6 py-6">
                        <input type="file" name="archivo" id="archivo"
                            class="block w-full text-sm text-primary/70 file:mr-4 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary/90">
                        <p class="mt-3 text-xs text-primary/40">
                            Déjalo vacío si no quieres cambiar el archivo actual. Se acepta cualquier tipo de archivo, máximo 20 MB.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-primary/5">
                    @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                        <button type="submit"
                            class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm">
                            Guardar cambios
                        </button>
                    @endif

                    <a href="{{ route('documentos.create', $documento->contrato_id) }}"
                        class="border border-primary/10 bg-white text-primary/70 px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary/5 transition-colors">
                        Volver
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-8">
        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Archivo actual</h3>
            </div>

            <div class="p-6 space-y-4">
                <div class="rounded-xl bg-slate-50 border border-primary/10 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Nombre visible</p>
                    <p class="text-sm font-semibold text-primary break-words">
                        {{ $documento->nombre_original ?? $documento->nombre_documento }}
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 border border-primary/10 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Tipo de archivo</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border uppercase {{ $badgeTipo }}">
                        {{ $extension ?: 'N/A' }}
                    </span>
                </div>

                <div class="rounded-xl bg-slate-50 border border-primary/10 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Ruta almacenada</p>
                    <p class="text-xs text-primary/70 break-all">{{ $documento->archivo }}</p>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('documentos.view', $documento) }}" target="_blank"
                        class="block w-full text-center px-4 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                        Ver archivo actual
                    </a>

                    <a href="{{ route('documentos.download', $documento) }}"
                        class="block w-full text-center px-4 py-3 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm font-semibold hover:bg-green-100 transition-colors">
                        Descargar archivo
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Versiones</h3>
                <p class="text-sm text-primary/50 mt-1">Historial de archivos cargados para este documento.</p>
            </div>

            <div class="p-6 space-y-3">
                @forelse ($documento->versiones->sortByDesc('numero_version') as $version)
                    <div class="rounded-xl border border-primary/10 bg-slate-50 px-4 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-bold text-primary">Versión {{ $version->numero_version }}</p>
                                <p class="text-xs text-primary/60 mt-1 break-words">
                                    {{ $version->nombre_original ?? $documento->nombre_documento }}
                                </p>
                                <p class="text-xs text-primary/40 mt-2">
                                    {{ $version->uploadedBy?->name ?? 'No registrado' }} ·
                                    {{ $version->created_at?->format('d/m/Y H:i') }}
                                </p>
                                @if ($version->observacion)
                                    <p class="text-xs text-primary/50 mt-2">{{ $version->observacion }}</p>
                                @endif
                            </div>

                            <a href="{{ route('documentos.versiones.download', $version) }}"
                                class="shrink-0 text-xs font-bold text-green-600 hover:text-green-800">
                                Descargar
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-primary/40">Este documento todavía no tiene historial de versiones.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Observaciones</h3>
                <p class="text-sm text-primary/50 mt-1">Notas internas del proceso de revisión del documento.</p>
            </div>

            <div class="p-6 space-y-5">
                <form action="{{ route('documentos.observaciones.store', $documento) }}" method="POST" class="space-y-3">
                    @csrf
                    <textarea name="observacion" rows="3" maxlength="1000"
                        placeholder="Ej: Falta firma del contratista o se debe cargar soporte actualizado."
                        class="w-full rounded-xl border border-primary/10 bg-slate-50 px-4 py-3 text-sm outline-none resize-none focus:border-primary/30">{{ old('observacion') }}</textarea>
                    <button type="submit"
                        class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary/90">
                        Agregar observación
                    </button>
                </form>

                <div class="space-y-3">
                    @forelse ($documento->observaciones->sortByDesc('created_at') as $observacion)
                        <div class="rounded-xl border border-primary/10 bg-slate-50 px-4 py-4">
                            <p class="text-sm text-primary/70">{{ $observacion->observacion }}</p>
                            <p class="text-xs text-primary/40 mt-3">
                                {{ $observacion->user?->name ?? 'Sistema' }} ·
                                {{ $observacion->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-primary/40">No hay observaciones registradas para este documento.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Resumen</h3>
            </div>

            <div class="p-6 space-y-4">
                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">Categoría</p>
                    <p class="text-sm text-primary font-semibold">{{ $documento->categoria }}</p>
                </div>

                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">Estado</p>
                    <p class="text-sm text-primary font-semibold">{{ $documento->estado }}</p>
                </div>

                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">Fecha de carga</p>
                    <p class="text-sm text-primary font-semibold">{{ $documento->fecha_carga ?: 'No registrada' }}</p>
                </div>

                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">Contrato</p>
                    <p class="text-sm text-primary font-semibold">
                        {{ optional($documento->contrato)->numero_contrato ?? $documento->contrato_id }}
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">Responsable</p>
                    <p class="text-sm text-primary font-semibold">
                        {{ $documento->uploadedBy?->name ?? 'No registrado' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
