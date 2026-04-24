<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        'fecha_inicio'   => 'date',
        'fecha_fin'      => 'date',
    ];

    protected $appends = ['estado_vigencia'];

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('dashboard_kpis');
        static::created($flush);
        static::updated($flush);
        static::deleted($flush);
    }

    // ── Accessor ──────────────────────────────────────────────────────────────

    public function getEstadoVigenciaAttribute(): string
    {
        if (! $this->fecha_fin) {
            return 'Sin definir';
        }

        $hoy = Carbon::today();

        if ($this->fecha_fin->lt($hoy)) {
            return 'Vencido';
        }

        if ($this->fecha_fin->lte($hoy->copy()->addDays(15))) {
            return 'Por vencer';
        }

        return 'Vigente';
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function scopeVencidos($query)
    {
        return $query->whereNotNull('fecha_fin')->whereDate('fecha_fin', '<', today());
    }

    public function scopePorVencer($query)
    {
        return $query->whereNotNull('fecha_fin')
            ->whereBetween('fecha_fin', [today(), today()->addDays(15)]);
    }

    public function scopeVigentes($query)
    {
        return $query->whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '>', today()->addDays(15));
    }

    // ── Relations ─────────────────────────────────────────────────────────────

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

    // ── Helpers de dominio ────────────────────────────────────────────────────

    public function resumenDocumental(): array
    {
        $requisitos = $this->documentosRequeridos;
        $documentos = $this->documentos;

        $items = $requisitos->map(function (DocumentoRequerido $requisito) use ($documentos) {
            $documentosCategoria = $documentos->where('categoria', $requisito->categoria);
            $documentoAprobado   = $documentosCategoria->first(fn ($d) => strtolower((string) $d->estado) === 'aprobado');

            return [
                'requisito'          => $requisito,
                'documentos_cargados'=> $documentosCategoria->count(),
                'cumplido'           => (bool) $documentoAprobado,
                'documento_aprobado' => $documentoAprobado,
            ];
        });

        $total      = $items->count();
        $cumplidos  = $items->where('cumplido', true)->count();
        $pendientes = $total - $cumplidos;
        $porcentaje = $total > 0 ? (int) round(($cumplidos / $total) * 100) : 0;

        return compact('items', 'total', 'cumplidos', 'pendientes', 'porcentaje');
    }
}
