<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <form action="{{ route('login.post') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-primary mb-2">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="admin@salazardiaz.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-primary mb-2">Contraseña</label>
                <input type="password" name="password" id="password"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="********">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-primary/20">
                <label for="remember" class="text-sm text-primary/70">Recordarme</label>
            </div>

            <div class="text-right">
                <a href="{{ route('password.request') }}"
                    class="text-sm font-semibold text-primary hover:text-primary/70 transition-colors">
                    Olvid&eacute; mi contrase&ntilde;a
                </a>
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary/90 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-sm">
                Entrar al sistema
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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



