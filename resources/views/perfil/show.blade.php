<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - SALAZAR &amp; D&Iacute;AZ S.A.S</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
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
        <x-sidebar />

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <div>
                    <h2 class="text-2xl font-bold text-primary tracking-tight">Mi perfil</h2>
                    <p class="text-sm text-primary/50 mt-1">
                        Datos de acceso, rol y resumen de actividad dentro del sistema.
                    </p>
                </div>

                <span class="rounded-full border border-primary/10 bg-primary/5 px-4 py-2 text-sm font-bold text-primary">
                    {{ ucfirst($usuario->rol) }}
                </span>
            </header>

            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                @if (session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-4">
                        <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">
                        <p class="text-sm font-semibold text-amber-700">{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <section class="xl:col-span-2 bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Informaci&oacute;n de usuario</h3>
                            <p class="text-sm text-primary/50 mt-1">Actualiza tus datos b&aacute;sicos y tu contrase&ntilde;a.</p>
                        </div>

                        <form action="{{ route('perfil.update') }}" method="POST" class="p-6 space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Nombre</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}"
                                        class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                                </div>

                                <div>
                                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Correo</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}"
                                        class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Nueva contrase&ntilde;a</label>
                                    <input type="password" name="password" id="password"
                                        class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                                        placeholder="Dejar vac&iacute;o para no cambiar">
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest text-primary/50 mb-2">Confirmar contrase&ntilde;a</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30">
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-primary/5 pt-5">
                                <button type="submit"
                                    class="rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-primary/90">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </section>

                    <aside class="space-y-6">
                        <div class="bg-white rounded-xl border border-primary/10 shadow-sm p-6">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/50">Rol actual</p>
                            <p class="mt-2 text-2xl font-black text-primary">{{ ucfirst($usuario->rol) }}</p>
                        </div>

                        <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                            <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Tareas pendientes</p>
                            <p class="mt-2 text-2xl font-black text-amber-700">{{ $tareasPendientes->count() }}</p>
                        </div>

                        <div class="bg-white rounded-xl border border-green-200 shadow-sm p-6">
                            <p class="text-xs font-bold uppercase tracking-widest text-green-600">Tareas completadas</p>
                            <p class="mt-2 text-2xl font-black text-green-700">{{ $tareasCompletadas }}</p>
                        </div>
                    </aside>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Mis tareas pendientes</h3>
                            <p class="text-sm text-primary/50 mt-1">Tareas asignadas ordenadas por fecha l&iacute;mite.</p>
                        </div>

                        <div class="divide-y divide-primary/5">
                            @forelse ($tareasPendientes as $tarea)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-primary">{{ $tarea->titulo }}</p>
                                            <p class="text-xs text-primary/50 mt-1">
                                                {{ $tarea->contrato?->numero_contrato ?? 'Sin contrato' }}
                                            </p>
                                        </div>
                                        <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                            {{ $tarea->fecha_limite?->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="px-6 py-10 text-center text-sm text-primary/40">No tienes tareas pendientes.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Actividad reciente</h3>
                            <p class="text-sm text-primary/50 mt-1">Acciones registradas con tu usuario.</p>
                        </div>

                        <div class="divide-y divide-primary/5">
                            @forelse ($auditorias as $auditoria)
                                <div class="px-6 py-4">
                                    <p class="text-sm font-bold text-primary">
                                        {{ ucfirst($auditoria->accion) }} · {{ ucfirst($auditoria->modulo) }}
                                    </p>
                                    <p class="text-xs text-primary/60 mt-1">{{ $auditoria->detalle }}</p>
                                    <p class="text-xs text-primary/40 mt-2">{{ $auditoria->created_at?->format('d/m/Y H:i') }}</p>
                                </div>
                            @empty
                                <p class="px-6 py-10 text-center text-sm text-primary/40">No hay actividad registrada.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
