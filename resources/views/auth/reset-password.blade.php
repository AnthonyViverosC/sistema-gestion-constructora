<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contrase&ntilde;a - SALAZAR &amp; D&Iacute;AZ S.A.S</title>
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
            <h1 class="text-2xl font-bold text-primary">Nueva contrase&ntilde;a</h1>
            <p class="text-sm text-primary/50 mt-2">
                Define una nueva contrase&ntilde;a segura para volver a ingresar al sistema.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-semibold text-primary mb-2">Correo electr&oacute;nico</label>
                <input type="email" name="email" id="email" value="{{ old('email', $email) }}"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="admin@salazardiaz.com">
                @error('email')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-primary mb-2">Nueva contrase&ntilde;a</label>
                <input type="password" name="password" id="password"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="Minimo 8 caracteres">
                @error('password')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-primary mb-2">Confirmar contrase&ntilde;a</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full rounded-xl border border-primary/10 bg-white px-4 py-3 text-sm outline-none focus:border-primary/30"
                    placeholder="Repite la contrasena">
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary/90 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-sm">
                Guardar nueva contrase&ntilde;a
            </button>

            <a href="{{ route('login') }}"
                class="block w-full text-center border border-primary/10 text-primary px-5 py-3 rounded-xl text-sm font-semibold hover:bg-primary/5 transition-colors">
                Volver al login
            </a>
        </form>
    </div>
</body>

</html>
