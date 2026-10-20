<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstalacionEvento extends Model
{
    protected $table = 'instalacion_eventos';

    protected $fillable = [
        'instalacion_id',
        'tipo',
        'latitud',
        'longitud',
        'precision_metros',
        'fuente',
        'payload_json',
        'capturado_at',
        'por',
    ];

    protected $casts = [
        'latitud'          => 'float',
        'longitud'         => 'float',
        'precision_metros' => 'integer',
        'payload_json'     => 'array',
        'capturado_at'     => 'datetime',
    ];

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class, 'instalacion_id');
    }
}