<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contrato - SALAZAR & DÍAZ S.A.S</title>
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
        <x-sidebar :contrato="$contrato ?? null" :documento="$documento ?? null" />

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <div>
                    <div class="flex items-center gap-2 text-xs text-primary/40 mb-1">
                        <a href="{{ route('contratos.index') }}" class="hover:text-primary transition-colors">Contratos</a>
                        <span>/</span>
                        <a href="{{ route('contratos.show', $contrato) }}" class="hover:text-primary transition-colors">{{ $contrato->numero_contrato }}</a>
                        <span>/</span>
                        <span class="text-primary/70 font-medium">Editar</span>
                    </div>
                    <h2 class="text-2xl font-bold text-primary tracking-tight">Editar Contrato</h2>
                    <p class="text-sm text-primary/50 mt-1">Actualiza la información general del contrato.</p>
                </div>

                <a href="{{ route('contratos.show', $contrato) }}" class="px-4 py-2.5 border border-primary/10 bg-white text-sm font-medium text-primary/70 rounded-xl hover:bg-primary/5 transition-colors">Volver</a>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl">
                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">
                            <p class="text-sm font-semibold text-amber-700">Hay errores en el formulario.</p>
                            <p class="text-xs text-amber-600 mt-1">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form action="{{ route('contratos.update', $contrato) }}" method="POST" class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        @csrf
                        @method('PUT')

                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Datos del contrato</h3>
                            <p class="text-sm text-primary/50 mt-1">Revise los cambios antes de actualizar.</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Número de contrato</label>
                                <input type="text" name="numero_contrato" value="{{ old('numero_contrato', $contrato->numero_contrato) }}" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Fecha del contrato</label>
                                <input type="date" name="fecha_contrato" value="{{ old('fecha_contrato', $contrato->fecha_contrato) }}" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Cédula del contratista</label>
                                <input type="text" name="cedula_contratista" value="{{ old('cedula_contratista', $contrato->cedula_contratista) }}" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Nombre del contratista</label>
                                <input type="text" name="nombre_contratista" value="{{ old('nombre_contratista', $contrato->nombre_contratista) }}" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $contrato->fecha_inicio) }}" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Fecha fin</label>
                                <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $contrato->fecha_fin) }}" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Estado</label>
                                <select name="estado" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                                    @foreach (['Activo', 'Pendiente', 'Finalizado', 'Cancelado', 'Documentación completa'] as $estado)
                                        <option value="{{ $estado }}" @selected(old('estado', $contrato->estado) === $estado)>{{ $estado }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Etiqueta</label>
                                <select name="etiqueta" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">Sin etiqueta</option>
                                    @foreach (['Pendiente', 'Falta firma', 'Falta revisar', 'Completo'] as $etiqueta)
                                        <option value="{{ $etiqueta }}" @selected(old('etiqueta', $contrato->etiqueta) === $etiqueta)>{{ $etiqueta }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-primary mb-2">Descripción</label>
                                <textarea name="descripcion" rows="4" class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">{{ old('descripcion', $contrato->descripcion) }}</textarea>
                            </div>
                        </div>

                        <div class="px-6 py-5 border-t border-primary/10 bg-primary/[0.02] flex justify-end gap-3">
                            <a href="{{ route('contratos.show', $contrato) }}" class="px-5 py-3 rounded-xl border border-primary/10 bg-white text-primary/70 text-sm font-semibold hover:bg-primary/5 transition-colors">Cancelar</a>
                            <button type="submit" class="px-5 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">Actualizar contrato</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>



