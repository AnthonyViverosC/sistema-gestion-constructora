<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1a2a47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
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
                        "2xl": "1rem",
                        full: "9999px"
                    },
                    boxShadow: {
                        soft: "0 1px 2px 0 rgba(26, 42, 71, 0.04), 0 1px 3px 0 rgba(26, 42, 71, 0.06)",
                        card: "0 1px 3px 0 rgba(26, 42, 71, 0.06), 0 4px 12px -2px rgba(26, 42, 71, 0.05)",
                        elevated: "0 8px 24px -8px rgba(26, 42, 71, 0.15), 0 2px 6px -1px rgba(26, 42, 71, 0.08)"
                    },
                    maxWidth: {
                        "8xl": "88rem"
                    },
                    screens: {
                        xs: "480px"
                    }
                },
            },
        }
    </script>
    <style>
        html, body { margin: 0; padding: 0; scroll-behavior: smooth; }
        html, body { height: 100%; }

        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(26, 42, 71, 0.15); border-radius: 8px; border: 2px solid transparent; background-clip: padding-box; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(26, 42, 71, 0.3); border: 2px solid transparent; background-clip: padding-box; }

        .topbar-mobile { display: flex; height: 56px; }
        @media (min-width: 1024px) {
            .topbar-mobile { display: none !important; }
        }

        .toast { opacity: 0; transform: translateY(-20px) scale(.95); animation: toastIn .3s ease-out forwards; }
        .toast-out { animation: toastOut .2s ease-in forwards; }
        .toast-bar { transform-origin: left; animation: toastTimer 5s linear forwards; }
        @keyframes toastIn { to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes toastOut { to { opacity: 0; transform: translateY(-8px) scale(.98); } }
        @keyframes toastTimer { from { transform: scaleX(1); } to { transform: scaleX(0); } }
    </style>
    @stack('head')
</head>

<body class="bg-background-light font-display text-slate-900 antialiased flex h-screen overflow-hidden">

    <x-sidebar :contrato="$contrato ?? null" :documento="$documento ?? null" />

    <div id="sidebarBackdrop"
        class="fixed inset-0 bg-primary/40 backdrop-blur-[2px] z-30 lg:hidden hidden"
        onclick="window.closeSidebar && window.closeSidebar()"
        aria-hidden="true"></div>

    <main class="flex-1 flex flex-col overflow-hidden min-w-0 min-h-0">
        <div class="topbar-mobile shrink-0 items-center justify-between px-4 bg-white border-b border-primary/10">
            <button type="button" onclick="window.openSidebar && window.openSidebar()"
                aria-label="Abrir menú"
                class="-ml-2 p-2 rounded-lg text-primary hover:bg-primary/5 active:bg-primary/10 transition-colors">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="size-7 bg-primary text-white flex items-center justify-center rounded-lg font-bold text-xs">SD</div>
                <span class="text-sm font-bold text-primary">Salazar &amp; Díaz</span>
            </div>
            <div class="w-10"></div>
        </div>

        <header class="flex items-center justify-between gap-4 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 bg-white border-b border-primary/10 shrink-0">
            @yield('header')
        </header>

        <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain">
            <div class="max-w-8xl mx-auto p-4 sm:p-6 lg:p-8 xl:px-10 2xl:px-12">
                @yield('content')
            </div>
        </div>
    </main>

    <x-toasts />
    <x-confirm-modal />

    @stack('modals')

    <script>
        (function () {
            const sidebar  = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;

            const isMobile = () => window.innerWidth < 1024;

            const reset = () => {
                backdrop.classList.add('hidden');
                sidebar.classList.remove('translate-x-0');
                if (isMobile()) {
                    sidebar.classList.add('-translate-x-full');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                }
                document.body.style.overflow = '';
            };

            window.openSidebar = function () {
                if (!isMobile()) return;
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeSidebar = function () {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            };

            sidebar.querySelectorAll('a[href]').forEach((a) => {
                a.addEventListener('click', () => {
                    if (isMobile()) window.closeSidebar();
                });
            });

            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !backdrop.classList.contains('hidden')) {
                    window.closeSidebar();
                }
            });

            window.addEventListener('resize', () => {
                if (!isMobile()) reset();
            });

            window.addEventListener('pageshow', reset);

            reset();
        })();

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
