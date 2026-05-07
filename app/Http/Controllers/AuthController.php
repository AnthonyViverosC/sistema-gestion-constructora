<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingrese un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            RateLimiter::clear('login:'.$request->input('email').'|'.$request->ip());
            $request->session()->regenerate();

            return redirect()->route('contratos.index')->with('success', 'Bienvenido al sistema.');
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no son correctas.'])
            ->onlyInput('email');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::warning('Fallo en autenticación con Google', ['error' => $e->getMessage()]);

            return redirect()->route('login')
                ->with('error', 'No se pudo completar el inicio de sesión con Google. Inténtalo de nuevo.');
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('login')
                ->with('error', 'La cuenta de Google no tiene un correo asociado.');
        }

        $emailGoogle = strtolower(trim($googleUser->getEmail()));
        $autorizados = array_map('strtolower', config('services.google.allowed_emails', []));

        if (empty($autorizados) || ! in_array($emailGoogle, $autorizados, true)) {
            Log::warning('Intento de login con Google rechazado (correo no autorizado)', [
                'email' => $emailGoogle,
            ]);

            return redirect()->route('login')
                ->with('error', 'Esta cuenta de Google no está autorizada para acceder al sistema.');
        }

        $usuario = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        $esNuevo = false;

        if ($usuario) {
            $cambios = [];
            if (! $usuario->google_id) {
                $cambios['google_id'] = $googleUser->getId();
            }
            if ($googleUser->getAvatar() && $usuario->avatar !== $googleUser->getAvatar()) {
                $cambios['avatar'] = $googleUser->getAvatar();
            }
            if (! empty($cambios)) {
                $usuario->update($cambios);
            }
        } else {
            $usuario = User::create([
                'name'      => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Usuario Google',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => null,
                'rol'       => 'consulta',
            ]);
            $esNuevo = true;
        }

        Auth::login($usuario, true);
        $request->session()->regenerate();

        if ($esNuevo) {
            Auditoria::registrar(
                'crear',
                'usuarios',
                $usuario->id,
                'Usuario registrado vía Google: '.$usuario->name.' (rol consulta por defecto)'
            );
        }

        $mensaje = $esNuevo
            ? 'Cuenta creada con Google. Tu rol inicial es Consulta. Un administrador puede actualizarlo.'
            : 'Bienvenido al sistema.';

        return redirect()->route('contratos.index')->with('success', $mensaje);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
