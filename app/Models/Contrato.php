<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'created_by',
        'numero_contrato',
        'fecha_contrato',
        'fecha_inicio',
        'fecha_fin',
        'cedula_contratista',
        'nombre_contratista',
        'estado',
        'etiqueta',
        'descripcion',
    ];

    protected $casts = [
        'fecha_contrato' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function documentosRequeridos()
    {
        return $this->hasMany(DocumentoRequerido::class)->orderBy('orden');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
