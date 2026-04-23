<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Documento;
use App\Models\Notificacion;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificacionController extends Controller
{
    public function index()
    {
        $this->procesarAlertasDeTareas();

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

        $notificacionesRecientes = Notificacion::with(['user', 'tarea.contrato'])
            ->latest('sent_at')
            ->take(10)
            ->get();

        return view('notificaciones.index', compact(
            'tareasVencidas',
            'tareasPorVencer',
            'documentosConAlerta',
            'contratosPorVencer',
            'expedientesIncompletos',
            'totalAlertas',
            'puedeVerTodo',
            'notificacionesRecientes'
        ));
    }

    private function procesarAlertasDeTareas(): void
    {
        $hoy = Carbon::today();
        $limite = $hoy->copy()->addDays(2);

        Tarea::with(['assignedTo', 'contrato'])
            ->where('estado', '!=', 'Completada')
            ->whereNull('notified_at')
            ->whereDate('fecha_limite', '<=', $limite)
            ->get()
            ->each(function (Tarea $tarea) use ($hoy) {
                $responsable = $tarea->assignedTo;

                if (! $responsable || empty($responsable->email)) {
                    return;
                }

                $esVencida = $tarea->fecha_limite && $tarea->fecha_limite->lt($hoy);
                $titulo = $esVencida ? 'Tarea vencida' : 'Tarea por vencer';
                $mensaje = sprintf(
                    'La tarea "%s" del contrato %s tiene fecha limite %s.',
                    $tarea->titulo,
                    $tarea->contrato?->numero_contrato ?? 'sin contrato',
                    optional($tarea->fecha_limite)->format('d/m/Y')
                );

                try {
                    Mail::html(
                        '<p>'.$mensaje.'</p><p>Responsable: '.e($responsable->name).'</p>',
                        function ($mail) use ($responsable, $titulo) {
                            $mail->to($responsable->email)->subject($titulo.' - SALAZAR & DIAZ S.A.S');
                        }
                    );

                    Notificacion::create([
                        'user_id' => $responsable->id,
                        'tarea_id' => $tarea->id,
                        'tipo' => $esVencida ? 'tarea_vencida' : 'tarea_por_vencer',
                        'canal' => 'correo',
                        'titulo' => $titulo,
                        'mensaje' => $mensaje,
                        'estado' => 'enviada',
                        'fecha_evento' => $tarea->fecha_limite,
                        'sent_at' => now(),
                    ]);

                    $tarea->update(['notified_at' => now()]);
                } catch (Throwable $exception) {
                    Notificacion::create([
                        'user_id' => $responsable->id,
                        'tarea_id' => $tarea->id,
                        'tipo' => $esVencida ? 'tarea_vencida' : 'tarea_por_vencer',
                        'canal' => 'correo',
                        'titulo' => $titulo,
                        'mensaje' => $mensaje,
                        'estado' => 'fallida',
                        'fecha_evento' => $tarea->fecha_limite,
                    ]);
                }
            });
    }
}
