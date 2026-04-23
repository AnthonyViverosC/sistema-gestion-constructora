<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Contrato;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->estado;
        $responsable = $request->responsable;
        $puedeVerTodas = in_array(auth()->user()->rol, ['admin', 'gestor']);

        $tareas = Tarea::with(['contrato', 'documento', 'assignedTo', 'createdBy'])
            ->when(! $puedeVerTodas, fn ($query) => $query->where('assigned_to', auth()->id()))
            ->when($estado, function ($query) use ($estado) {
                if ($estado === 'Vencida') {
                    $query->where('estado', '!=', 'Completada')
                        ->whereDate('fecha_limite', '<', now()->toDateString());

                    return;
                }

                if ($estado === 'Por vencer') {
                    $query->where('estado', '!=', 'Completada')
                        ->whereBetween('fecha_limite', [now()->toDateString(), now()->addDays(2)->toDateString()]);

                    return;
                }

                $query->where('estado', $estado);
            })
            ->when($puedeVerTodas && $responsable, fn ($query) => $query->where('assigned_to', $responsable))
            ->orderByRaw("CASE WHEN estado = 'Completada' THEN 1 ELSE 0 END")
            ->orderBy('fecha_limite')
            ->get();

        $usuarios = User::orderBy('name')->get();

        return view('tareas.index', compact('tareas', 'usuarios', 'estado', 'responsable', 'puedeVerTodas'));
    }

    public function store(Request $request, Contrato $contrato)
    {
        $datos = $request->validate([
            'documento_id' => ['nullable', 'exists:documentos,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_limite' => ['required', 'date'],
        ], [
            'titulo.required' => 'El título de la tarea es obligatorio.',
            'fecha_limite.required' => 'La fecha límite es obligatoria.',
            'fecha_limite.date' => 'La fecha límite no es válida.',
        ]);

        $datos['contrato_id'] = $contrato->id;
        $datos['created_by'] = auth()->id();
        $datos['estado'] = 'Pendiente';

        if (! empty($datos['documento_id']) && ! $contrato->documentos()->whereKey($datos['documento_id'])->exists()) {
            return back()
                ->withErrors(['documento_id' => 'El documento seleccionado no pertenece a este contrato.'])
                ->withInput();
        }

        $tarea = Tarea::create($datos);

        Auditoria::registrar('crear', 'tareas', $tarea->id, 'Tarea creada: '.$tarea->titulo, $tarea->contrato_id);

        return back()->with('success', 'Tarea creada correctamente.');
    }

    public function complete(Tarea $tarea)
    {
        if (! in_array(auth()->user()->rol, ['admin', 'gestor']) && $tarea->assigned_to !== auth()->id()) {
            return back()->with('error', 'No tienes permisos para completar esta tarea.');
        }

        $tarea->update([
            'estado' => 'Completada',
            'completed_at' => now(),
        ]);

        Auditoria::registrar('completar', 'tareas', $tarea->id, 'Tarea completada: '.$tarea->titulo, $tarea->contrato_id);

        return back()->with('success', 'Tarea marcada como completada.');
    }

    public function destroy(Tarea $tarea)
    {
        Auditoria::registrar('eliminar', 'tareas', $tarea->id, 'Tarea eliminada: '.$tarea->titulo, $tarea->contrato_id);

        $tarea->delete();

        return back()->with('success', 'Tarea eliminada correctamente.');
    }
}
