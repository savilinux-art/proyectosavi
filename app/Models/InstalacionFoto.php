<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstalacionFoto extends Model
{
    protected $table = 'instalacion_fotos';

    protected $fillable = [
        'instalacion_id', 'ruta', 'nombre_original', 'mime',
        'tamano_kb', 'tipo', 'descripcion', 'subida_por_usuario',
        // geo (migración +4 cols)
        'latitud', 'longitud', 'precision_metros', 'fuente',
    ];

    protected $casts = [
        'latitud'          => 'float',
        'longitud'         => 'float',
        'precision_metros' => 'integer',
        'tamano_kb'        => 'integer',
    ];

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class, 'instalacion_id');
    }

    /**
     * Devuelve la ruta relativa en el disco 'local'.
     * TODO F1: servir vía Storage::response() en controlador
     * (regla v31: nunca response() plano).
     */
    public function path(): string
    {
        return $this->ruta;
    }

    /** @deprecated usar path() + Storage::response() en controlador */
    public function url(): string
    {
        return $this->ruta;
    }
}
