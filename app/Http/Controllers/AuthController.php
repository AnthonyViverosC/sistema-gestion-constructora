<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
        ]);

        $usuario = User::where('email', $request->email)->first();

        if ($usuario) {
            Auditoria::create([
                'user_id' => $usuario->id,
                'accion' => 'solicitar',
                'modulo' => 'seguridad',
                'registro_id' => $usuario->id,
                'detalle' => 'Solicitud de enlace de recuperacion de contrasena.',
            ]);
        }

        $status = Password::sendResetLink($request->only('email'));

        return back()->with(
            $status === Password::RESET_LINK_SENT ? 'success' : 'error',
            $status === Password::RESET_LINK_SENT
                ? 'Si el correo existe en el sistema, enviamos un enlace para restablecer la contrasena.'
                : 'No fue posible procesar la solicitud en este momento.'
        );
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.confirmed' => 'La confirmacion de la contrasena no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                Auditoria::create([
                    'user_id' => $user->id,
                    'accion' => 'actualizar',
                    'modulo' => 'seguridad',
                    'registro_id' => $user->id,
                    'detalle' => 'Contrasena restablecida mediante enlace de recuperacion.',
                ]);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Contrasena actualizada correctamente. Ya puedes iniciar sesion.');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Bienvenido al sistema.');
        }

        return back()
            ->withErrors([
                'email' => 'Las credenciales no son correctas.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
