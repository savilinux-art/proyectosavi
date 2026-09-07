<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geocerca extends Model
{
    protected $fillable = [
        'nombre', 'latitud', 'longitud', 'radio',
        'proyecto_id', 'instalacion_id', 'color', 'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function alertas()
    {
        return $this->hasMany(GeocercaAlerta::class);
    }
}