<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionDetalle extends Model
{
    protected $table = 'devolucion_detalle';

    protected $fillable = [
        'devolucion_id',
        'inventario_id',
        'cantidad',
        'precio_unitario',
        'observaciones',
    ];

    public function devolucion()
    {
        return $this->belongsTo(DevolucionInventario::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}