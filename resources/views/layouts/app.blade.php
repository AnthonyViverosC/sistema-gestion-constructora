<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestión Documental') — SALAZAR &amp; DÍAZ S.A.S</title>
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
    <style>
        .toast { opacity: 0; transform: translateY(-20px) scale(.95); animation: toastIn .3s ease-out forwards; }
        .toast-out { animation: toastOut .2s ease-in forwards; }
        .toast-bar { transform-origin: left; animation: toastTimer 5s linear forwards; }
        @keyframes toastIn { to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes toastOut { to { opacity: 0; transform: translateY(-8px) scale(.98); } }
        @keyframes toastTimer { from { transform: scaleX(1); } to { transform: scaleX(0); } }
    </style>
    @stack('head')
</head>

<body class="bg-background-light font-display text-slate-900 antialiased min-h-screen">

    <x-toasts />

    <div class="flex h-screen overflow-hidden">
        <x-sidebar :contrato="$contrato ?? null" :documento="$documento ?? null" />

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-8 py-6 bg-white border-b border-primary/10">
                @yield('header')
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('modals')

    <script>
        window.cerrarToast = function(boton) {
            const toast = boton.closest('.toast');
            if (!toast) return;
            toast.classList.add('toast-out');
            setTimeout(() => toast.remove(), 200);
        };
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toast').forEach(function(toast) {
                setTimeout(function() {
                    toast.classList.add('toast-out');
                    setTimeout(() => toast.remove(), 200);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')

</body>

</html>
