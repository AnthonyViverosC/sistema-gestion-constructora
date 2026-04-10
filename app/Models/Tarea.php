<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'contrato_id',
        'documento_id',
        'created_by',
        'assigned_to',
        'titulo',
        'descripcion',
        'fecha_limite',
        'estado',
        'completed_at',
        'notified_at',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'completed_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
