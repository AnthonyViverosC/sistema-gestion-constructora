<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Documento;
use App\Models\Tarea;
use Carbon\Carbon;

class NotificacionController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $enDosDias = $hoy->copy()->addDays(2);
        $enQuinceDias = $hoy->copy()->addDays(15);
        $puedeVerTodo = in_array(auth()->user()->rol, ['admin', 'gestor']);

        $baseTareas = Tarea::with(['contrato', 'documento', 'assignedTo'])
            ->where('estado', '!=', 'Completada')
            ->when(! $puedeVerTodo, fn ($query) => $query->where('assigned_to', auth()->id()));

        $tareasVencidas = (clone $baseTareas)
            ->whereDate('fecha_limite', '<', $hoy)
            ->orderBy('fecha_limite')
            ->get();

        $tareasPorVencer = (clone $baseTareas)
            ->whereBetween('fecha_limite', [$hoy, $enDosDias])
            ->orderBy('fecha_limite')
            ->get();

        $documentosConAlerta = Documento::with(['contrato', 'uploadedBy'])
            ->whereIn('estado', ['Observado', 'Rechazado'])
            ->latest()
            ->take(15)
            ->get();

        $contratosPorVencer = Contrato::whereNotNull('fecha_fin')
            ->whereBetween('fecha_fin', [$hoy, $enQuinceDias])
            ->orderBy('fecha_fin')
            ->get();

        $expedientesIncompletos = collect();

        if ($puedeVerTodo) {
            $expedientesIncompletos = Contrato::with(['documentos', 'documentosRequeridos'])
                ->latest()
                ->get()
                ->map(function (Contrato $contrato) {
                    $total = $contrato->documentosRequeridos->count();
                    $cumplidos = $contrato->documentosRequeridos->filter(function ($requisito) use ($contrato) {
                        return $contrato->documentos->contains(function ($documento) use ($requisito) {
                            return $documento->categoria === $requisito->categoria
                                && strtolower($documento->estado) === 'aprobado';
                        });
                    })->count();

                    $contrato->documentos_pendientes = max($total - $cumplidos, 0);
                    $contrato->avance_documental = $total > 0 ? (int) round(($cumplidos / $total) * 100) : 0;

                    return $contrato;
                })
                ->where('documentos_pendientes', '>', 0)
                ->take(10)
                ->values();
        }

        $totalAlertas = $tareasVencidas->count()
            + $tareasPorVencer->count()
            + $documentosConAlerta->count()
            + $contratosPorVencer->count()
            + $expedientesIncompletos->count();

        return view('notificaciones.index', compact(
            'tareasVencidas',
            'tareasPorVencer',
            'documentosConAlerta',
            'contratosPorVencer',
            'expedientesIncompletos',
            'totalAlertas',
            'puedeVerTodo'
        ));
    }
}
