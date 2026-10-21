<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    // Constantes de resultado — usar en Services y SyncController
    public const RESULTADO_OK                 = 'ok';
    public const RESULTADO_CONFLICTO          = 'conflicto';
    public const RESULTADO_HUERFANO           = 'huerfano';
    public const RESULTADO_ERROR              = 'error';
    public const RESULTADO_DUPLICADO_DISTINTO = 'duplicado_distinto';

    // Laravel pluralizaría SyncLog → sync_logs (no existe). Forzar singular.
    protected $table = 'sync_log';

    protected $fillable = [
        'tabla',
        'registro_id',
        'uuid_cliente',
        'operacion',
        'payload_json',
        'resultado',
        'detalle',
        'intentado_at',
        'aplicado_at',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'intentado_at' => 'datetime',
        'aplicado_at'  => 'datetime',
    ];
}