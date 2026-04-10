<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoVersion extends Model
{
    protected $table = 'documento_versiones';

    protected $fillable = [
        'documento_id',
        'uploaded_by',
        'numero_version',
        'archivo',
        'nombre_original',
        'extension',
        'tamano',
        'observacion',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
