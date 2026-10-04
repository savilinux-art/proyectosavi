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
        'notas',
        'subido_por',
    ];

    protected $casts = [
        'archivo_tamano' => 'integer',
    ];

    public function salida(): BelongsTo
    {
        return $this->belongsTo(SalidaInventario::class, 'salida_id');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}