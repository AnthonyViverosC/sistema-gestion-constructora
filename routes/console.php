<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use App\Models\Auditoria;
use App\Models\Tarea;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tareas:notificar-vencimientos', function () {
    $tareas = Tarea::with(['contrato', 'documento', 'assignedTo', 'createdBy'])
        ->where('estado', '!=', 'Completada')
        ->whereNull('notified_at')
        ->whereDate('fecha_limite', '<=', now()->addDays(2)->toDateString())
        ->get();

    foreach ($tareas as $tarea) {
        $destinatario = $tarea->assignedTo ?? $tarea->createdBy;

        if (! $destinatario?->email) {
            continue;
        }

        $estado = $tarea->fecha_limite->isPast() ? 'vencida' : 'próxima a vencer';
        $contrato = $tarea->contrato?->numero_contrato ?? 'Sin contrato';
        $documento = $tarea->documento
            ? ($tarea->documento->nombre_original ?? $tarea->documento->nombre_documento)
            : 'Sin documento asociado';

        Mail::raw(
            "La tarea \"{$tarea->titulo}\" está {$estado}.\n\n".
            "Contrato: {$contrato}\n".
            "Documento: {$documento}\n".
            "Fecha límite: {$tarea->fecha_limite->format('d/m/Y')}\n\n".
            "Ingrese al sistema para gestionarla.",
            function ($message) use ($destinatario, $tarea, $estado) {
                $message->to($destinatario->email)
                    ->subject('Tarea '.$estado.': '.$tarea->titulo);
            }
        );

        $tarea->update(['notified_at' => now()]);

        Auditoria::registrar('notificar', 'tareas', $tarea->id, 'Notificación enviada a '.$destinatario->email, $tarea->contrato_id);
        $this->info('Notificada tarea '.$tarea->id.' a '.$destinatario->email);
    }

    $this->info('Total de tareas notificadas: '.$tareas->count());
})->purpose('Notificar por correo tareas vencidas o próximas a vencer');
