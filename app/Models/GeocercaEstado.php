<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeocercaEstado extends Model
{
    protected $fillable = [
        'geocerca_id', 'usuario_id', 'estado',
        'ultima_latitud', 'ultima_longitud', 'ultima_actualizacion'
    ];

    protected $casts = [
        'ultima_actualizacion' => 'datetime',
    ];

    public function geocerca()
    {
        return $this->belongsTo(Geocerca::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}