<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Contrato - SALAZAR & DÍAZ S.A.S</title>
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

    <div class="flex min-h-screen overflow-hidden">
        <aside class="w-64 flex-shrink-0 border-r border-primary/10 bg-white flex flex-col">
            <div class="p-6 border-b border-primary/10">
                <div class="flex items-center gap-3 mb-1">
                    <div
                        class="size-8 bg-primary text-white flex items-center justify-center rounded-lg font-bold text-sm">
                        SD
                    </div>
                    <h1 class="text-primary text-sm font-bold uppercase tracking-wider leading-tight">
                        SALAZAR & DÍAZ S.A.S
                    </h1>
                </div>
                <x-rol-label />
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-primary/70 hover:bg-primary/5 transition-colors">
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="{{ route('contratos.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-white">
                    <span class="text-sm font-medium">Contratos</span>
                </a>

                <a href="{{ route('tareas.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-primary/70 hover:bg-primary/5 transition-colors">
                    <span class="text-sm font-medium">Tareas</span>
                </a>

                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                    <a href="{{ route('documentos.create', $contrato) }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-primary/70 hover:bg-primary/5 transition-colors">
                        <span class="text-sm font-medium">Documentos</span>
                    </a>

                    <a href="{{ route('usuarios.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-primary/70 hover:bg-primary/5 transition-colors">
                        <span class="text-sm font-medium">Usuarios</span>
                    </a>
                @endif
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="p-4 border-t border-primary/10">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                    <span class="text-sm font-medium">Cerrar sesión</span>
                </button>
            </form>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <div>
                    <div class="flex items-center gap-2 text-xs text-primary/40 mb-1">
                        <a href="{{ route('contratos.index') }}"
                            class="hover:text-primary transition-colors">Contratos</a>
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
            </header>

            <div class="flex-1 overflow-y-auto p-8 space-y-8">

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
                @endphp

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                    <div class="xl:col-span-2 space-y-8">
                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Información general</h3>
                                <p class="text-sm text-primary/50 mt-1">
                                    Datos principales del contrato registrado.
                                </p>
                            </div>

                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
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

                                <div class="md:col-span-2">
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

                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
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

                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
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

                            <div class="overflow-x-auto">
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
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
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

                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Resumen</h3>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">
                                        Contratista
                                    </p>
                                    <p class="text-sm text-primary font-semibold">
                                        {{ $contrato->nombre_contratista }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">
                                        Estado actual
                                    </p>
                                    <p class="text-sm text-primary font-semibold">
                                        {{ $contrato->estado }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">
                                        Vigencia
                                    </p>
                                    <p class="text-sm text-primary font-semibold">
                                        {{ $estadoVigencia ?? 'Sin definir' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 border border-primary/10 px-4 py-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-1">
                                        Documentos cargados
                                    </p>
                                    <p class="text-sm text-primary font-semibold">
                                        {{ $contrato->documentos->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Tareas</h3>
                                <p class="text-sm text-primary/50 mt-1">Pendientes y fechas límite.</p>
                            </div>

                            <div class="p-6 space-y-4">
                                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                    <form action="{{ route('tareas.store', $contrato) }}" method="POST"
                                        class="space-y-3 rounded-xl border border-primary/10 bg-slate-50 p-4">
                                        @csrf
                                        <input type="text" name="titulo" placeholder="Título de la tarea"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30" required>

                                        <select name="documento_id"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30">
                                            <option value="">Sin documento asociado</option>
                                            @foreach ($contrato->documentos as $documento)
                                                <option value="{{ $documento->id }}">{{ $documento->nombre_original ?? $documento->nombre_documento }}</option>
                                            @endforeach
                                        </select>

                                        <select name="assigned_to"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30">
                                            <option value="">Sin responsable asignado</option>
                                            @foreach ($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }} - {{ ucfirst($usuario->rol) }}</option>
                                            @endforeach
                                        </select>

                                        <input type="date" name="fecha_limite"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30" required>

                                        <textarea name="descripcion" rows="2" placeholder="Descripción"
                                            class="w-full rounded-lg border border-primary/10 px-3 py-2 text-sm outline-none focus:border-primary/30"></textarea>

                                        <button type="submit"
                                            class="w-full rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">
                                            Crear tarea
                                        </button>
                                    </form>
                                @endif

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

                                        @if ((in_array(auth()->user()->rol, ['admin', 'gestor']) || $tarea->assigned_to === auth()->id()) && $tarea->estado !== 'Completada')
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

                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-primary/10">
                                <h3 class="text-lg font-bold text-primary">Historial</h3>
                                <p class="text-sm text-primary/50 mt-1">Últimas acciones sobre este contrato.</p>
                            </div>

                            <div class="p-6 space-y-4">
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
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.cerrarToast = function(boton) {
                const toast = boton.closest('.toast');
                if (!toast) return;

                toast.classList.add('toast-out');

                setTimeout(() => {
                    toast.remove();
                }, 200);
            };

            document.querySelectorAll('.toast').forEach((toast) => {
                setTimeout(() => {
                    toast.classList.add('toast-out');

                    setTimeout(() => {
                        toast.remove();
                    }, 200);
                }, 5000);
            });
        });
    </script>

</body>

</html>



