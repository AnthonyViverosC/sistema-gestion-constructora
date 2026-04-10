<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - SALAZAR & DÍAZ S.A.S</title>
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
        <x-sidebar :contrato="$contrato ?? null" :documento="$documento ?? null" />

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                <div>
                    <h2 class="text-2xl font-bold text-primary tracking-tight">Usuarios</h2>
                    <p class="text-sm text-primary/50 mt-1">
                        Crea cuentas y asigna roles de acceso al sistema.
                    </p>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                @if (session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-4">
                        <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">
                        <p class="text-sm font-semibold text-amber-700">Hay errores en el formulario.</p>
                        <p class="text-xs text-amber-600 mt-1">{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-8">
                    <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Usuarios registrados</h3>
                            <p class="text-sm text-primary/50 mt-1">Cuentas activas del sistema.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-primary/5">
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Nombre</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Correo</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Rol</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @forelse ($usuarios as $usuario)
                                        <tr class="hover:bg-primary/[0.02] transition-colors">
                                            <td class="px-6 py-4 text-sm font-semibold text-primary">{{ $usuario->name }}</td>
                                            <td class="px-6 py-4 text-sm text-primary/70">{{ $usuario->email }}</td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $badge = match ($usuario->rol) {
                                                        'admin' => 'bg-red-100 text-red-700 border-red-200',
                                                        'gestor' => 'bg-green-100 text-green-700 border-green-200',
                                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                    {{ ucfirst($usuario->rol) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-sm text-primary/40">
                                                No hay usuarios registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <form action="{{ route('usuarios.store') }}" method="POST"
                        class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden h-fit">
                        @csrf

                        <div class="px-6 py-5 border-b border-primary/10">
                            <h3 class="text-lg font-bold text-primary">Agregar usuario</h3>
                            <p class="text-sm text-primary/50 mt-1">Define sus credenciales y rol.</p>
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Nombre</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Correo</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Rol</label>
                                <select name="rol"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                                    @foreach (['admin' => 'Administrador', 'gestor' => 'Gestor', 'consulta' => 'Consulta'] as $valor => $texto)
                                        <option value="{{ $valor }}" @selected(old('rol', 'consulta') === $valor)>{{ $texto }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Contraseña</label>
                                <input type="password" name="password"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-primary mb-2">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border border-primary/10 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    required>
                            </div>
                        </div>

                        <div class="px-6 py-5 border-t border-primary/10 bg-primary/[0.02]">
                            <button type="submit"
                                class="w-full px-5 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                                Crear usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>



