<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SALAZAR & DÍAZ S.A.S</title>
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
</head>

<body class="bg-background-light font-display text-slate-900 antialiased min-h-screen">

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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-primary/70 hover:bg-primary/5 transition-colors' }}">
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="{{ route('contratos.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('contratos.*') ? 'bg-primary text-white' : 'text-primary/70 hover:bg-primary/5 transition-colors' }}">
                    <span class="text-sm font-medium">Contratos</span>
                </a>

                <a href="{{ route('tareas.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('tareas.*') ? 'bg-primary text-white' : 'text-primary/70 hover:bg-primary/5 transition-colors' }}">
                    <span class="text-sm font-medium">Tareas</span>
                </a>

                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                    <a href="{{ route('usuarios.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('usuarios.*') ? 'bg-primary text-white' : 'text-primary/70 hover:bg-primary/5 transition-colors' }}">
                        <span class="text-sm font-medium">Usuarios</span>
                    </a>
                    <a href="{{ route('auditoria.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('auditoria.*') ? 'bg-primary text-white' : 'text-primary/70 hover:bg-primary/5 transition-colors' }}">
                        <span class="text-sm font-medium">Auditoría</span>
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
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <div>
                    <h2 class="text-2xl font-bold text-primary tracking-tight">Dashboard</h2>
                    <p class="text-sm text-primary/50 mt-1">
                        Resumen general del sistema de contratos y documentos.
                    </p>
                </div>

                @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                    <a href="{{ route('contratos.create') }}"
                        class="px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary/90 transition-colors">
                        Nuevo contrato
                    </a>
                @endif
            </header>

            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                <div class="space-y-3 mb-6">
                    @if ($contratosVencidos > 0)
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-4">
                            <p class="text-sm font-semibold text-red-700">
                                Hay {{ $contratosVencidos }}
                                {{ $contratosVencidos == 1 ? 'contrato vencido' : 'contratos vencidos' }}.
                            </p>
                            <p class="text-xs text-red-600 mt-1">
                                Revise los contratos cuya fecha de finalización ya pasó.
                            </p>
                        </div>
                    @endif

                    @if ($contratosPorVencer > 0)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">
                            <p class="text-sm font-semibold text-amber-700">
                                {{ $contratosPorVencer }}
                                {{ $contratosPorVencer == 1 ? 'contrato está' : 'contratos están' }} por vencer en los
                                próximos 15 días.
                            </p>
                            <p class="text-xs text-amber-600 mt-1">
                                Es recomendable revisar su vigencia y documentación.
                            </p>
                        </div>
                    @endif

                    @if ($contratosVencidos == 0 && $contratosPorVencer == 0)
                        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-4">
                            <p class="text-sm font-semibold text-green-700">
                                No hay contratos vencidos ni próximos a vencer.
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                La vigencia contractual está al día.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Tarjetas resumen --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Total contratos</p>
                        <h3 class="text-3xl font-extrabold text-primary">{{ $totalContratos }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-green-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-green-600 mb-2">Activos</p>
                        <h3 class="text-3xl font-extrabold text-green-700">{{ $contratosActivos }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-2">Pendientes</p>
                        <h3 class="text-3xl font-extrabold text-amber-700">{{ $contratosPendientes }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Finalizados</p>
                        <h3 class="text-3xl font-extrabold text-slate-700">{{ $contratosFinalizados }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-red-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-red-500 mb-2">Cancelados</p>
                        <h3 class="text-3xl font-extrabold text-red-600">{{ $contratosCancelados }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Total documentos</p>
                        <h3 class="text-3xl font-extrabold text-primary">{{ $totalDocumentos }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-2">Documentos pendientes</p>
                        <h3 class="text-3xl font-extrabold text-amber-700">{{ $documentosPendientes }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-green-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-green-600 mb-2">Documentos aprobados</p>
                        <h3 class="text-3xl font-extrabold text-green-700">{{ $documentosAprobados }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-red-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-red-500 mb-2">Tareas vencidas</p>
                        <h3 class="text-3xl font-extrabold text-red-600">{{ $tareasVencidas }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-2">Tareas por vencer</p>
                        <h3 class="text-3xl font-extrabold text-amber-700">{{ $tareasPorVencer }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Tareas pendientes</p>
                        <h3 class="text-3xl font-extrabold text-primary">{{ $tareasPendientes }}</h3>
                    </div>

                    <div class="bg-white rounded-xl border border-green-200 shadow-sm p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-green-600 mb-2">Doc. completa</p>
                        <h3 class="text-3xl font-extrabold text-green-700">{{ $contratosDocumentacionCompleta }}</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-xl border border-green-200 p-6">
                            <p class="text-sm text-green-700 font-semibold">Contratos vigentes</p>
                            <h3 class="text-3xl font-bold text-green-800 mt-2">{{ $contratosVigentes }}</h3>
                        </div>

                        <div class="bg-white rounded-xl border border-amber-200 p-6">
                            <p class="text-sm text-amber-700 font-semibold">Por vencer (15 días)</p>
                            <h3 class="text-3xl font-bold text-amber-800 mt-2">{{ $contratosPorVencer }}</h3>
                        </div>

                        <div class="bg-white rounded-xl border border-red-200 p-6">
                            <p class="text-sm text-red-700 font-semibold">Vencidos</p>
                            <h3 class="text-3xl font-bold text-red-800 mt-2">{{ $contratosVencidos }}</h3>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-primary">Tareas prioritarias</h3>
                                <p class="text-sm text-primary/50 mt-1">Pendientes ordenadas por fecha límite.</p>
                            </div>

                            @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                                <a href="{{ route('tareas.index') }}"
                                    class="text-sm font-semibold text-primary hover:text-primary/70 transition-colors">
                                    Ver tareas
                                </a>
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Tarea</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Contrato</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Límite</th>
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
                                                {{ $tarea->fecha_limite?->format('d/m/Y') }}
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

                    {{-- Últimos contratos --}}
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-primary">Últimos contratos</h3>
                                <p class="text-sm text-primary/50 mt-1">Registros más recientes del sistema.</p>
                            </div>

                            <a href="{{ route('contratos.index') }}"
                                class="text-sm font-semibold text-primary hover:text-primary/70 transition-colors">
                                Ver todos
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th
                                            class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                            Contrato
                                        </th>
                                        <th
                                            class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                            Contratista
                                        </th>
                                        <th
                                            class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse($ultimosContratos as $contrato)
                                        <tr class="hover:bg-primary/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <a href="{{ route('contratos.show', $contrato) }}"
                                                    class="text-sm font-semibold text-primary hover:underline">
                                                    {{ $contrato->numero_contrato }}
                                                </a>
                                                <p class="text-xs text-primary/50 mt-1">{{ $contrato->fecha_contrato }}
                                                </p>
                                            </td>

                                            <td class="px-6 py-4 text-sm text-primary/70">
                                                {{ $contrato->nombre_contratista }}
                                            </td>

                                            <td class="px-6 py-4">
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-sm text-primary/40">
                                                No hay contratos registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Últimos documentos --}}
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Últimos documentos</h3>
                            <p class="text-sm text-primary/50 mt-1">Archivos cargados recientemente.</p>
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
                                            Contrato
                                        </th>
                                        <th
                                            class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse($ultimosDocumentos as $documento)
                                        <tr class="hover:bg-primary/[0.02] transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-semibold text-primary">
                                                    {{ $documento->nombre_original ?? $documento->nombre_documento }}
                                                </p>
                                                <p class="text-xs text-primary/50 mt-1 uppercase">
                                                    {{ pathinfo($documento->archivo, PATHINFO_EXTENSION) }}
                                                </p>
                                            </td>

                                            <td class="px-6 py-4">
                                                @if ($documento->contrato)
                                                    <a href="{{ route('contratos.show', $documento->contrato) }}"
                                                        class="text-sm text-primary/70 hover:text-primary hover:underline">
                                                        {{ $documento->contrato->numero_contrato }}
                                                    </a>
                                                @else
                                                    <span class="text-sm text-primary/40">Sin contrato</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4">
                                                @php
                                                    $estadoDoc = strtolower($documento->estado);
                                                    $badgeDoc = match (true) {
                                                        str_contains($estadoDoc, 'aprob')
                                                            => 'bg-green-100 text-green-700 border-green-200',
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-sm text-primary/40">
                                                No hay documentos registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Accesos rápidos --}}
                <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-primary/10">
                        <h3 class="text-lg font-bold text-primary">Accesos rápidos</h3>
                    </div>

                    <div class="p-6 flex flex-wrap gap-4">
                        @if (in_array(auth()->user()->rol, ['admin', 'gestor']))
                            <a href="{{ route('contratos.create') }}"
                                class="px-5 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                                Crear contrato
                            </a>
                        @endif

                        <a href="{{ route('contratos.index') }}"
                            class="px-5 py-3 rounded-xl border border-primary/10 bg-white text-primary/70 text-sm font-semibold hover:bg-primary/5 transition-colors">
                            Ver contratos
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>

</html>



