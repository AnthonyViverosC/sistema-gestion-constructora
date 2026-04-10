<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contrase&ntilde;a - SALAZAR &amp; D&Iacute;AZ S.A.S</title>
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
</head>

<body class="bg-background-light font-display min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-primary/10 overflow-hidden">
        <div class="px-8 py-8 border-b border-primary/10">
            <h1 class="text-2xl font-bold text-primary">Recuperar contrase&ntilde;a</h1>
            <p class="text-sm text-primary/50 mt-2">
                Ingresa tu correo y te enviaremos un enlace para restablecer el acceso.
            </p>
        </div>

        <form action="{{ route('password.email') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-primary mb-2">Correo electr&oacute;nico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="admin@salazardiaz.com">
                @error('email')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <button type="submit"
                class="w-full bg-primary hover:bg-primary/90 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-sm">
                Enviar enlace
            </button>

            <a href="{{ route('login') }}"
                class="block w-full text-center border border-primary/10 text-primary px-5 py-3 rounded-xl text-sm font-semibold hover:bg-primary/5 transition-colors">
                Volver al login
            </a>
        </form>
    </div>
</body>

</html>
