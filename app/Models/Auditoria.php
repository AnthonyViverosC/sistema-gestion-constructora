<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'user_id',
        'contrato_id',
        'accion',
        'modulo',
        'registro_id',
        'detalle',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public static function registrar(string $accion, string $modulo, ?int $registroId = null, ?string $detalle = null, ?int $contratoId = null): void
    {
        self::create([
            'user_id' => auth()->id(),
            'contrato_id' => $contratoId,
            'accion' => $accion,
            'modulo' => $modulo,
            'registro_id' => $registroId,
            'detalle' => $detalle,
        ]);
    }
}
