<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudUbicacion extends Model
{
    protected $table = 'solicitudes_ubicacion';
    protected $fillable = [
        'usuario_id', 'chat_id', 'tipo', 'instalacion_id'
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