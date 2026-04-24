<?php

namespace App\Enums;

enum EstadoContrato: string
{
    case Activo                  = 'Activo';
    case Pendiente               = 'Pendiente';
    case Finalizado              = 'Finalizado';
    case Cancelado               = 'Cancelado';
    case DocumentacionCompleta   = 'Documentación completa';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
