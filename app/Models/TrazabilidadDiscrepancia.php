<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrazabilidadDiscrepancia extends Model
{
    protected $table = 'trazabilidad_discrepancias';

    protected $fillable = [
        'proyecto_id',
        'inventario_id',
        'tipo',
        'cantidad_discrepancia',
        'estado',
        'detectado_en',
        'resuelto_en',
        'resuelto_por',
        'notas_resolucion',
        'corregido_por_salida_id',
        'corregido_por_cotizacion_id',
    ];

    protected $casts = [
        'cantidad_discrepancia' => 'integer',
        'detectado_en'          => 'datetime',
        'resuelto_en'           => 'datetime',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }

    public function scopeAbiertas($q)
    {
        return $q->where('estado', 'abierta');
    }

    public function scopeDelProyecto($q, int $proyectoId)
    {
        return $q->where('proyecto_id', $proyectoId);
    }
}
