<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos del Contrato - SALAZAR & DÍAZ S.A.S</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
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
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .toast {
            opacity: 0;
            transform: translateY(-20px) scale(.95);
            animation: toastIn .3s ease-out forwards;
        }

        .toast-out {
            animation: toastOut .2s ease-in forwards;
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
                transform: translateY(-8px) scale(.98);
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

    <div class="flex min-h-screen overflow-hidden">
        <x-sidebar :contrato="$contrato ?? null" :documento="$documento ?? null" />

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <div>
                    <h2 class="text-2xl font-bold text-primary tracking-tight">Documentos del Contrato</h2>
                    <p class="text-sm text-primary/60 mt-1">
                        Contrato: <span class="font-semibold">{{ $contrato->numero_contrato }}</span> |
                        Contratista: <span class="font-semibold">{{ $contrato->nombre_contratista }}</span>
                    </p>
                </div>

                <a href="{{ route('contratos.show', $contrato) }}"
                    class="px-4 py-2.5 border border-primary/10 bg-white text-sm font-medium text-primary/70 rounded-xl hover:bg-primary/5 transition-colors">
                    Volver
                </a>
            </header>

            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Contrato</p>
                        <p class="mt-2 text-lg font-bold text-primary">{{ $contrato->numero_contrato }}</p>
                        <p class="text-sm text-primary/50 mt-1">{{ $contrato->nombre_contratista }}</p>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Documentos visibles</p>
                        <p class="mt-2 text-lg font-bold text-primary">{{ $documentos->count() }}</p>
                        <p class="text-sm text-primary/50 mt-1">Segun los filtros activos</p>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Categorias activas</p>
                        <p class="mt-2 text-lg font-bold text-primary">{{ $categoriasDisponibles->count() }}</p>
                        <p class="text-sm text-primary/50 mt-1">Secciones disponibles</p>
                    </div>
                    <div class="rounded-xl border border-primary/10 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/40">Siguiente accion</p>
                        <p class="mt-2 text-sm font-bold text-primary">{{ in_array(auth()->user()->rol, ['admin', 'gestor']) ? 'Subir soporte o filtrar pendientes' : 'Revisar soportes cargados' }}</p>
                        <p class="text-sm text-primary/50 mt-1">Usa las secciones y filtros para no perder contexto.</p>
                    </div>
                </section>

                <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-primary/10">
                        <h3 class="text-lg font-bold text-primary">Secciones del expediente</h3>
                        <p class="text-sm text-primary/50 mt-1">
                            Organiza los documentos por categoria y controla el avance por seccion.
                        </p>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        @forelse ($seccionesDocumentales as $seccion)
                            <div class="rounded-xl border border-primary/10 bg-slate-50 p-4">
                                <p class="text-sm font-bold text-primary">{{ $seccion['categoria'] }}</p>
                                <p class="text-xs text-primary/50 mt-1">
                                    {{ $seccion['cumplidos'] }}/{{ $seccion['total_requisitos'] }} requisitos aprobados
                                </p>
                                <p class="text-xs text-primary/50 mt-2">
                                    {{ $seccion['documentos_cargados'] }} documento(s) cargado(s)
                                </p>
                            </div>
                        @empty
                            <div class="md:col-span-2 xl:col-span-4 rounded-xl border border-primary/10 bg-slate-50 p-4 text-sm text-primary/50">
                                No hay secciones configuradas para este contrato.
                            </div>
                        @endforelse
                    </div>
                </div>

                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Subir nuevo documento</h3>
                            <p class="text-sm text-primary/50 mt-1">
                                Adjunta cualquier tipo de archivo como soporte del contrato.
                            </p>
                        </div>

                        <form action="{{ route('documentos.store', $contrato) }}" method="POST"
                            enctype="multipart/form-data" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Nombre del
                                    documento</label>
                                <input type="text" name="nombre_documento" value="{{ old('nombre_documento') }}"
                                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                                    placeholder="Ej: Acta de inicio">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Categoría</label>
                                <select name="categoria"
                                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                                    <option value="">Seleccione</option>
                                    <option value="Contrato" {{ old('categoria') == 'Contrato' ? 'selected' : '' }}>
                                        Contrato</option>
                                    <option value="Actos Administrativos"
                                        {{ old('categoria') == 'Actos Administrativos' ? 'selected' : '' }}>
                                        Actos Administrativos</option>
                                    <option value="Seguridad Social"
                                        {{ old('categoria') == 'Seguridad Social' ? 'selected' : '' }}>
                                        Seguridad Social</option>
                                    <option value="Pagos" {{ old('categoria') == 'Pagos' ? 'selected' : '' }}>Pagos
                                    </option>
                                    <option value="Otros" {{ old('categoria') == 'Otros' ? 'selected' : '' }}>Otros
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Fecha de carga</label>
                                <input type="date" name="fecha_carga" value="{{ old('fecha_carga') }}"
                                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Estado</label>
                                <select name="estado"
                                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                                    @foreach (['Pendiente', 'En revisión', 'Observado', 'Aprobado', 'Rechazado'] as $estadoDocumento)
                                        <option value="{{ $estadoDocumento }}" @selected(old('estado', 'Pendiente') === $estadoDocumento)>
                                            {{ $estadoDocumento }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-primary mb-2">Etiqueta</label>
                                <select name="etiqueta"
                                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                                    <option value="">Sin etiqueta</option>
                                    @foreach (['Pendiente', 'Falta firma', 'Falta revisar', 'Completo'] as $etiqueta)
                                        <option value="{{ $etiqueta }}" @selected(old('etiqueta') === $etiqueta)>{{ $etiqueta }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-primary mb-2">Archivo</label>
                                <div id="zonaArchivo"
                                    class="rounded-xl border-2 border-dashed border-primary/20 bg-primary/[0.02] px-6 py-8 text-center transition-colors">
                                    <input type="file" name="archivo" id="archivoDocumento"
                                        class="block w-full text-sm text-primary/70 file:mr-4 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary/90">
                                    <p id="nombreArchivoSeleccionado" class="mt-3 text-sm font-semibold text-primary hidden"></p>
                                    <p class="mt-3 text-xs text-primary/40">
                                        Selecciona un archivo o arrástralo aquí. Se acepta cualquier tipo de archivo. Tamaño máximo: 20 MB.
                                    </p>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-primary mb-2">Descripción</label>
                                <textarea name="descripcion" rows="4"
                                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none resize-none focus:border-primary/30">{{ old('descripcion') }}</textarea>
                            </div>

                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit"
                                    class="bg-primary hover:bg-primary/90 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-sm">
                                    Guardar documento
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-primary/10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-primary">Documentos registrados</h3>
                                <p class="text-sm text-primary/50 mt-1">
                                    {{ in_array(auth()->user()->rol, ['admin', 'gestor']) ? 'Consulta, descarga y administra los soportes del contrato.' : 'Consulta y descarga los soportes del contrato.' }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <input type="text" id="buscadorDocumentos"
                                    placeholder="Buscar por nombre, categoría o estado..."
                                    class="border border-gray-300 px-4 py-2 rounded-lg w-full md:w-96 focus:ring-2 focus:ring-gray-800 outline-none">
                                <button type="button" id="limpiarBuscadorDocumentos"
                                    class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 whitespace-nowrap">
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('documentos.create', $contrato) }}"
                        class="px-6 py-4 border-b border-primary/10 bg-slate-50 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
                        <select name="categoria" class="rounded-lg border border-primary/10 px-3 py-2 text-sm">
                            <option value="">Todas las categorias</option>
                            @foreach ($categoriasDisponibles as $categoriaDisponible)
                                <option value="{{ $categoriaDisponible }}" @selected($categoria === $categoriaDisponible)>{{ $categoriaDisponible }}</option>
                            @endforeach
                        </select>

                        <select name="etiqueta" class="rounded-lg border border-primary/10 px-3 py-2 text-sm">
                            <option value="">Todas las etiquetas</option>
                            @foreach ($etiquetasDisponibles as $etiquetaDisponible)
                                <option value="{{ $etiquetaDisponible }}" @selected($etiqueta === $etiquetaDisponible)>{{ $etiquetaDisponible }}</option>
                            @endforeach
                        </select>

                        <input type="date" name="fecha_desde" value="{{ $fechaDesde }}"
                            class="rounded-lg border border-primary/10 px-3 py-2 text-sm" placeholder="Desde">

                        <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}"
                            class="rounded-lg border border-primary/10 px-3 py-2 text-sm" placeholder="Hasta">

                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">
                                Filtrar
                            </button>
                            <a href="{{ route('documentos.create', $contrato) }}"
                                class="rounded-lg border border-primary/10 bg-white px-4 py-2 text-sm font-semibold text-primary/70 hover:bg-primary/5">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    <div class="px-6 py-4 border-b border-primary/10 bg-slate-50">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if ($categoria !== '')
                                <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                    Categoria: {{ $categoria }}
                                </span>
                            @endif
                            @if ($etiqueta !== '')
                                <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                    Etiqueta: {{ $etiqueta }}
                                </span>
                            @endif
                            @if ($fechaDesde !== '' || $fechaHasta !== '')
                                <span class="rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                                    Fecha: {{ $fechaDesde ?: 'inicio' }} - {{ $fechaHasta ?: 'hoy' }}
                                </span>
                            @endif
                            @if ($categoria === '' && $etiqueta === '' && $fechaDesde === '' && $fechaHasta === '')
                                <span class="rounded-full border border-primary/10 bg-white px-3 py-1 text-xs font-bold text-primary/60">
                                    Sin filtros avanzados
                                </span>
                            @endif
                        </div>
                        <p id="contadorDocumentos" class="text-xs font-medium text-primary/50">
                            Mostrando {{ $documentos->count() }}
                            {{ $documentos->count() === 1 ? 'documento' : 'documentos' }}
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-primary/5">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        N.°
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Documento</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Tipo</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Categoría</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Fecha</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Responsable</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                        Estado</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70 text-right">
                                        Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaDocumentos" class="divide-y divide-primary/5">
                                @forelse($documentos as $documento)
                                    @php
                                        $estado = strtolower($documento->estado);
                                        $extension = strtolower(pathinfo($documento->archivo, PATHINFO_EXTENSION));

                                        $badge = match (true) {
                                            str_contains($estado, 'aprob')
                                                => 'bg-green-100 text-green-700 border-green-200',
                                            str_contains($estado, 'activ')
                                                => 'bg-green-100 text-green-700 border-green-200',
                                            str_contains($estado, 'rechaz')
                                                => 'bg-red-100 text-red-700 border-red-200',
                                            str_contains($estado, 'observ')
                                                => 'bg-orange-100 text-orange-700 border-orange-200',
                                            str_contains($estado, 'revisi')
                                                => 'bg-blue-100 text-blue-700 border-blue-200',
                                            str_contains($estado, 'pend')
                                                => 'bg-amber-100 text-amber-700 border-amber-200',
                                            default => 'bg-primary/10 text-primary border-primary/20',
                                        };

                                        $badgeTipo = match ($extension) {
                                            'pdf' => 'bg-red-100 text-red-700 border-red-200',
                                            'doc', 'docx' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'xls', 'xlsx' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'jpg',
                                            'jpeg',
                                            'png',
                                            'webp'
                                                => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'zip', 'rar' => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'txt' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp

                                    <tr class="hover:bg-primary/[0.02] transition-colors documento-row"
                                    data-search="{{ strtolower(($documento->nombre_original ?? $documento->nombre_documento) . ' ' . $documento->categoria . ' ' . $documento->estado . ' ' . $documento->etiqueta . ' ' . $extension) }}">
                                        <td class="px-6 py-4 text-xs text-primary/40 font-mono">{{ $documento->id }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-primary">
                                                {{ $documento->nombre_original ?? $documento->nombre_documento }}
                                            </p>
                                            <p class="text-xs text-primary/50 mt-1">
                                                {{ $documento->nombre_documento }}
                                            </p>
                                            <p class="text-xs text-primary/40 mt-1">
                                                {{ $documento->versiones_count ?: 1 }}
                                                {{ ($documento->versiones_count ?: 1) === 1 ? 'versión' : 'versiones' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border uppercase {{ $badgeTipo }}">
                                                {{ $extension ?: 'N/A' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/70">{{ $documento->categoria }}</td>

                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            {{ $documento->fecha_carga ? \Carbon\Carbon::parse($documento->fecha_carga)->format('d/m/Y') : 'No registrada' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-primary/70">
                                            {{ $documento->uploadedBy?->name ?? 'No registrado' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                {{ $documento->estado }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('documentos.view', $documento) }}" target="_blank"
                                                    class="text-xs font-bold text-primary hover:text-primary/70 transition-colors">
                                                    Ver
                                                </a>

                                                <span class="text-primary/20">|</span>

                                                <a href="{{ route('documentos.download', $documento) }}"
                                                    class="text-xs font-bold text-green-600 hover:text-green-800">
                                                    Descargar
                                                </a>

                                                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                                    <span class="text-primary/20">|</span>

                                                    <a href="{{ route('documentos.edit', $documento) }}"
                                                        class="text-xs font-bold text-blue-500 hover:text-blue-700">
                                                        Editar
                                                    </a>
                                                @endif

                                                @if (auth()->user()->rol === 'admin')
                                                    <span class="text-primary/20">|</span>

                                                    <button type="button"
                                                        onclick="abrirModalEliminarDocumento('{{ route('documentos.destroy', $documento) }}', '{{ $documento->nombre_original ?? $documento->nombre_documento }}')"
                                                        class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 border border-red-200 hover:bg-red-100 transition-colors">
                                                        Eliminar
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="filaVaciaInicial">
                                            <td colspan="8"
                                            class="px-6 py-16 text-center text-sm text-primary/40 font-medium">
                                            No hay documentos registrados para este contrato.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="filaSinResultados" class="hidden">
                                    <td colspan="7"
                                        class="px-6 py-16 text-center text-sm text-primary/40 font-medium">
                                        No se encontraron documentos con esa búsqueda.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="modalEliminarDocumento"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 transition-opacity duration-200">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-primary/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-primary/10">
                <h3 class="text-lg font-bold text-primary">Confirmar eliminación</h3>
                <p class="text-sm text-primary/50 mt-1">
                    Esta acción eliminará el documento seleccionado.
                </p>
            </div>

            <div class="px-6 py-5">
                <p class="text-sm text-primary/70">Está a punto de eliminar el documento:</p>
                <p id="documentoEliminarTexto" class="mt-2 text-base font-bold text-red-600 break-words"></p>
                <p class="mt-4 text-xs text-primary/40">Esta acción no se puede deshacer.</p>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-primary/10 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEliminarDocumento()"
                    class="px-4 py-2.5 rounded-lg border border-primary/10 bg-white text-sm font-semibold text-primary/70 hover:bg-primary/5 transition-colors">
                    Cancelar
                </button>

                <form id="formEliminarDocumento" method="POST">
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
            const buscador = document.getElementById('buscadorDocumentos');
            const limpiarBuscador = document.getElementById('limpiarBuscadorDocumentos');
            const filas = Array.from(document.querySelectorAll('.documento-row'));
            const contador = document.getElementById('contadorDocumentos');
            const filaSinResultados = document.getElementById('filaSinResultados');
            const modalEliminarDocumento = document.getElementById('modalEliminarDocumento');
            const formEliminarDocumento = document.getElementById('formEliminarDocumento');
            const documentoEliminarTexto = document.getElementById('documentoEliminarTexto');
            const zonaArchivo = document.getElementById('zonaArchivo');
            const inputArchivo = document.getElementById('archivoDocumento');
            const nombreArchivoSeleccionado = document.getElementById('nombreArchivoSeleccionado');

            function actualizarContador(visibles) {
                contador.textContent = `Mostrando ${visibles} ${visibles === 1 ? 'documento' : 'documentos'}`;
            }

            function filtrarDocumentos() {
                const valor = buscador.value.toLowerCase().trim();
                let visibles = 0;

                filas.forEach((fila) => {
                    const texto = fila.dataset.search || '';
                    const coincide = texto.includes(valor);
                    fila.classList.toggle('hidden', !coincide);
                    if (coincide) visibles++;
                });

                actualizarContador(visibles);

                if (filaSinResultados) {
                    filaSinResultados.classList.toggle('hidden', visibles !== 0 || filas.length === 0);
                }
            }

            if (buscador) {
                buscador.addEventListener('input', filtrarDocumentos);
            }

            if (limpiarBuscador) {
                limpiarBuscador.addEventListener('click', function() {
                    buscador.value = '';
                    filtrarDocumentos();
                    buscador.focus();
                });
            }

            function mostrarArchivoSeleccionado() {
                if (!inputArchivo?.files?.length || !nombreArchivoSeleccionado) return;

                nombreArchivoSeleccionado.textContent = `Archivo seleccionado: ${inputArchivo.files[0].name}`;
                nombreArchivoSeleccionado.classList.remove('hidden');
            }

            if (inputArchivo) {
                inputArchivo.addEventListener('change', mostrarArchivoSeleccionado);
            }

            if (zonaArchivo && inputArchivo) {
                ['dragenter', 'dragover'].forEach((evento) => {
                    zonaArchivo.addEventListener(evento, function(e) {
                        e.preventDefault();
                        zonaArchivo.classList.add('border-primary', 'bg-primary/5');
                    });
                });

                ['dragleave', 'drop'].forEach((evento) => {
                    zonaArchivo.addEventListener(evento, function(e) {
                        e.preventDefault();
                        zonaArchivo.classList.remove('border-primary', 'bg-primary/5');
                    });
                });

                zonaArchivo.addEventListener('drop', function(e) {
                    const archivos = e.dataTransfer.files;
                    if (!archivos.length) return;

                    inputArchivo.files = archivos;
                    mostrarArchivoSeleccionado();
                });
            }

            window.abrirModalEliminarDocumento = function(action, nombreDocumento) {
                formEliminarDocumento.action = action;
                documentoEliminarTexto.textContent = nombreDocumento;
                modalEliminarDocumento.classList.remove('hidden');
                modalEliminarDocumento.classList.add('flex');
            };

            window.cerrarModalEliminarDocumento = function() {
                modalEliminarDocumento.classList.add('hidden');
                modalEliminarDocumento.classList.remove('flex');
            };

            modalEliminarDocumento.addEventListener('click', function(e) {
                if (e.target === modalEliminarDocumento) cerrarModalEliminarDocumento();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    cerrarModalEliminarDocumento();
                }
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

