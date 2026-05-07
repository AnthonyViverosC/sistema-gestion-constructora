<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $rol = $request->input('rol', '');

        $usuarios = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when(in_array($rol, ['admin', 'gestor', 'consulta'], true), function ($query) use ($rol) {
                $query->where('rol', $rol);
            })
            ->orderBy('name')
            ->get();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'filtros'  => ['q' => $q, 'rol' => $rol],
        ]);
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

    public function edit(Request $request, User $usuario)
    {
        $q = trim((string) $request->input('q', ''));
        $rol = $request->input('rol', '');

        $usuarios = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when(in_array($rol, ['admin', 'gestor', 'consulta'], true), function ($query) use ($rol) {
                $query->where('rol', $rol);
            })
            ->orderBy('name')
            ->get();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'usuario'  => $usuario,
            'filtros'  => ['q' => $q, 'rol' => $rol],
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', Rule::in(['admin', 'gestor', 'consulta'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'Seleccione un rol válido.',
        ]);

        if ($usuario->id === auth()->id() && $datos['rol'] !== $usuario->rol) {
            return back()
                ->withErrors(['rol' => 'No puedes cambiar tu propio rol.'])
                ->withInput();
        }

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $usuario->update($datos);

        Auditoria::registrar('actualizar', 'usuarios', $usuario->id, 'Usuario actualizado: '.$usuario->name.' ('.$usuario->rol.')');

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($usuario->rol === 'admin' && User::where('rol', 'admin')->count() <= 1) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar al último administrador del sistema.');
        }

        $bloqueos = [];
        if ($usuario->contratosCreados()->exists()) {
            $bloqueos[] = 'contratos creados';
        }
        if ($usuario->documentosSubidos()->exists()) {
            $bloqueos[] = 'documentos subidos';
        }
        if ($usuario->tareas()->exists()) {
            $bloqueos[] = 'tareas asignadas';
        }

        if (! empty($bloqueos)) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No se puede eliminar a '.$usuario->name.' porque tiene '.implode(', ', $bloqueos).'. Reasigna esos registros antes de eliminar.');
        }

        $nombre = $usuario->name;
        $rol = $usuario->rol;
        $id = $usuario->id;

        $usuario->delete();

        Auditoria::registrar('eliminar', 'usuarios', $id, 'Usuario eliminado: '.$nombre.' ('.$rol.')');

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
