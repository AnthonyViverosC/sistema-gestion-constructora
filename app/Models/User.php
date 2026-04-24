<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'assigned_to');
    }

    public function documentosSubidos()
    {
        return $this->hasMany(Documento::class, 'uploaded_by');
    }

    public function contratosCreados()
    {
        return $this->hasMany(Contrato::class, 'created_by');
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function puedeGestionar(): bool
    {
        return in_array($this->rol, ['admin', 'gestor'], true);
    }

    public function tieneAccesoLectura(): bool
    {
        return in_array($this->rol, ['admin', 'gestor', 'consulta'], true);
    }
}
