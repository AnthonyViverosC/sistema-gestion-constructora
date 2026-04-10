<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoObservacion extends Model
{
    protected $table = 'documento_observaciones';

    protected $fillable = [
        'documento_id',
        'user_id',
        'observacion',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
