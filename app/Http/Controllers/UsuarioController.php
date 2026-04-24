<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', Rule::in(['admin', 'gestor', 'consulta'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'Seleccione un rol válido.',
        ]);

        $usuario = User::create($datos);

        Auditoria::registrar('crear', 'usuarios', $usuario->id, 'Usuario creado: '.$usuario->name.' ('.$usuario->rol.')');

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }
}
