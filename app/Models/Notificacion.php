<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tarea_id',
        'tipo',
        'canal',
        'titulo',
        'mensaje',
        'estado',
        'fecha_evento',
        'sent_at',
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}
