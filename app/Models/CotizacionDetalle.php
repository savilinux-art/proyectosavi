<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionDetalle extends Model
{
    protected $table = 'cotizacion_detalles';

    protected $fillable = [
        'cotizacion_id',
        'inventario_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'importe'
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}