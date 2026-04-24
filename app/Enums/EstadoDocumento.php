<?php

namespace App\Enums;

enum EstadoDocumento: string
{
    case Pendiente   = 'Pendiente';
    case EnRevision  = 'En revisión';
    case Observado   = 'Observado';
    case Aprobado    = 'Aprobado';
    case Rechazado   = 'Rechazado';
    case Activo      = 'Activo';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function badge(): string
    {
        return match ($this) {
            self::Aprobado   => 'bg-green-100 text-green-800 border-green-200',
            self::Rechazado  => 'bg-red-100 text-red-800 border-red-200',
            self::Observado  => 'bg-orange-100 text-orange-800 border-orange-200',
            self::EnRevision => 'bg-blue-100 text-blue-800 border-blue-200',
            default          => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
