<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría - SALAZAR & DÍAZ S.A.S</title>
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
                    }
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
                    <h2 class="text-2xl font-bold text-primary tracking-tight">Auditoría</h2>
                    <p class="text-sm text-primary/50 mt-1">Últimas acciones registradas en el sistema.</p>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="bg-white rounded-xl border border-primary/10 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-primary/5">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Fecha</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Usuario</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Acción</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Módulo</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-primary/70">Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5">
                            @forelse ($auditorias as $auditoria)
                                <tr class="hover:bg-primary/[0.02]">
                                    <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->user?->name ?? 'Sistema' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-primary">{{ ucfirst($auditoria->accion) }}</td>
                                    <td class="px-6 py-4 text-sm text-primary/70">{{ ucfirst($auditoria->modulo) }}</td>
                                    <td class="px-6 py-4 text-sm text-primary/70">{{ $auditoria->detalle }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-primary/40">No hay acciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
