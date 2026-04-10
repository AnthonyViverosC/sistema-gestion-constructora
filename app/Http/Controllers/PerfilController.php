<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = auth()->user();

        $tareasPendientes = Tarea::with('contrato')
            ->where('assigned_to', $usuario->id)
            ->where('estado', '!=', 'Completada')
            ->orderBy('fecha_limite')
            ->take(8)
            ->get();

        $tareasCompletadas = Tarea::where('assigned_to', $usuario->id)
            ->where('estado', 'Completada')
            ->count();

        $auditorias = Auditoria::where('user_id', $usuario->id)
            ->latest()
            ->take(10)
            ->get();

        return view('perfil.show', compact('usuario', 'tareasPendientes', 'tareasCompletadas', 'auditorias'));
    }

    public function update(Request $request)
    {
        $usuario = auth()->user();

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
            'email.unique' => 'Este correo ya esta registrado.',
            'password.confirmed' => 'La confirmacion de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener minimo 8 caracteres.',
        ]);

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $usuario->update($datos);

        Auditoria::registrar('actualizar', 'perfil', $usuario->id, 'Perfil actualizado.');

        return redirect()
            ->route('perfil.show')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
