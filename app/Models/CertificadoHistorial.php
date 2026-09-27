<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificadoHistorial extends Model
{
    protected $table = 'certificado_historial';

    protected $fillable = [
        'recordatorio_id', 'usuario_id',
        'fecha_vencimiento_anterior', 'fecha_vencimiento_nueva', 'notas',
    ];

    protected $casts = [
        'fecha_vencimiento_anterior' => 'date',
        'fecha_vencimiento_nueva'    => 'date',
    ];

    public function recordatorio(): BelongsTo
    {
        return $this->belongsTo(Recordatorio::class, 'recordatorio_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario');
    }
}
