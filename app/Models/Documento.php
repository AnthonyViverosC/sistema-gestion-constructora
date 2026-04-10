<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'contrato_id',
        'uploaded_by',
        'nombre_documento',
        'nombre_original',
        'archivo',
        'categoria',
        'fecha_carga',
        'estado',
        'etiqueta',
        'descripcion',
    ];

    protected $casts = [
        'fecha_carga' => 'date',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function versiones()
    {
        return $this->hasMany(DocumentoVersion::class);
    }
}
