<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class DocumentoPolicy
{
    public function view(User $user, Documento $documento): bool
    {
        return in_array($user->rol, ['admin', 'gestor', 'consulta']);
    }

    public function update(User $user, Documento $documento): bool
    {
        return in_array($user->rol, ['admin', 'gestor']);
    }

    public function delete(User $user, Documento $documento): bool
    {
        return in_array($user->rol, ['admin', 'gestor']);
    }
}
