<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvidenciaEntrega extends Model
{
    protected $table = 'evidencias_entrega';

    protected $fillable = [
        'salida_id',
        'proyecto_id',
        'archivo_path',
        'archivo_nombre_original',
        'archivo_mime',
        'archivo_tamano',
        'tipo',              // B-03
        'estado',            // B-03
        'notas',
        'subido_por',
        'invalidada_at',     // B-03
        'invalidada_por',    // B-03
        'invalidada_motivo', // B-03
    ];

    protected $casts = [
        'archivo_tamano' => 'integer',
        'invalidada_at'  => 'datetime', // B-03
    ];

    public function salida(): BelongsTo
    {
        return $this->belongsTo(SalidaInventario::class, 'salida_id');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    /**
     * B-03: solo evidencias vigentes (no invalidadas por edición).
     */
    public function scopeVigente($query)
    {
        return $query->where('estado', 'vigente');
    }
}