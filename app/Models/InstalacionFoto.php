<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InstalacionFoto extends Model
{
    protected $table = 'instalacion_fotos';

    protected $fillable = [
        'instalacion_id', 'ruta', 'nombre_original', 'mime',
        'tamano_kb', 'tipo', 'descripcion', 'subida_por_usuario',
    ];

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class, 'instalacion_id');
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->ruta);
    }
}