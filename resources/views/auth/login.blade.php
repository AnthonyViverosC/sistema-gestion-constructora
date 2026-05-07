<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a2a47">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <title>Iniciar sesión - SALAZAR & DÍAZ S.A.S</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                }
            }
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

<body class="bg-background-light font-display min-h-screen flex items-center justify-center p-6">

    @if (session('success') || session('error') || $errors->any())
        <div id="toastContainer" class="fixed top-5 right-5 z-[9999] space-y-3 w-full max-w-sm pointer-events-none">
            @if (session('success'))
                <div
                    class="toast pointer-events-auto rounded-xl border border-green-200 bg-white shadow-lg overflow-hidden">
                    <div class="flex items-start gap-3 p-4">
                        <div
                            class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 font-bold">
                            ✓</div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Operación exitosa</p>
                            <p class="text-sm text-slate-600 mt-1">{{ session('success') }}</p>
                        </div>
                        <button type="button" onclick="cerrarToast(this)"
                            class="text-slate-400 hover:text-slate-700 text-lg leading-none">×</button>
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
                            !</div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Ocurrió un problema</p>
                            <p class="text-sm text-slate-600 mt-1">{{ session('error') }}</p>
                        </div>
                        <button type="button" onclick="cerrarToast(this)"
                            class="text-slate-400 hover:text-slate-700 text-lg leading-none">×</button>
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
                            !</div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Hay errores en el formulario</p>
                            <p class="text-sm text-slate-600 mt-1">{{ $errors->first() }}</p>
                        </div>
                        <button type="button" onclick="cerrarToast(this)"
                            class="text-slate-400 hover:text-slate-700 text-lg leading-none">×</button>
                    </div>
                    <div class="h-1 bg-amber-500 toast-bar"></div>
                </div>
            @endif
        </div>
    @endif

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-primary/10 overflow-hidden">
        <div class="px-8 py-8 border-b border-primary/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl font-bold">
                    SD
                </div>
                <div>
                    <h1 class="text-lg font-bold text-primary">SALAZAR & DÍAZ S.A.S</h1>
                    <p class="text-sm text-primary/50">Sistema de gestión contractual</p>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-primary mt-4">Iniciar sesión</h2>
            <p class="text-sm text-primary/50 mt-1">Ingresa tus credenciales para acceder al sistema.</p>
        </div>

        <form action="/login" method="POST" enctype="application/x-www-form-urlencoded" class="px-8 py-8 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-primary mb-2">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    autocomplete="username"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="admin@salazardiaz.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-primary mb-2">Contraseña</label>
                <input type="password" name="password" id="password"
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="********">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-primary/20">
                <label for="remember" class="text-sm text-primary/70">Recordarme</label>
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary/90 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-sm">
                Entrar al sistema
            </button>

            <div class="relative flex items-center py-1">
                <div class="flex-grow border-t border-primary/10"></div>
                <span class="flex-shrink mx-3 text-xs uppercase tracking-wide text-primary/40">o</span>
                <div class="flex-grow border-t border-primary/10"></div>
            </div>

            <a href="{{ route('auth.google.redirect') }}"
                class="w-full flex items-center justify-center gap-3 bg-white hover:bg-slate-50 text-slate-700 px-5 py-3 rounded-xl text-sm font-semibold border border-slate-200 shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5">
                    <path fill="#FFC107"
                        d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" />
                    <path fill="#FF3D00"
                        d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" />
                    <path fill="#4CAF50"
                        d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" />
                    <path fill="#1976D2"
                        d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" />
                </svg>
                Continuar con Google
            </a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[action="/login"]');
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    this.querySelector('button[type="submit"]')?.setAttribute('disabled', 'disabled');
                });
            }

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



