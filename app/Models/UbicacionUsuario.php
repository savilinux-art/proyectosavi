<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionUsuario extends Model
{
    protected $table = 'ubicaciones_usuarios';
    protected $fillable = [
        'usuario_id', 'instalacion_id', 'latitud', 'longitud',
        'fecha_hora', 'fuente', 'tipo', 'detalles'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }
}