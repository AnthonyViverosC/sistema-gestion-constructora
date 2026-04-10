<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoRequerido extends Model
{
    protected $table = 'documento_requeridos';

    protected $fillable = [
        'contrato_id',
        'nombre',
        'categoria',
        'obligatorio',
        'orden',
        'descripcion',
    ];

    protected $casts = [
        'obligatorio' => 'boolean',
    ];

    public static function plantillaBase(): array
    {
        return [
            ['nombre' => 'Contrato firmado', 'categoria' => 'Contrato', 'orden' => 1],
            ['nombre' => 'Acta de inicio', 'categoria' => 'Actos Administrativos', 'orden' => 2],
            ['nombre' => 'Seguridad social', 'categoria' => 'Seguridad Social', 'orden' => 3],
            ['nombre' => 'Soporte de pago', 'categoria' => 'Pagos', 'orden' => 4],
        ];
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}
