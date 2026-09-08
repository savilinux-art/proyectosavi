<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalidaDetalle; 

class SalidaDetalle extends Model
{
    use HasFactory;

    protected $table = 'salida_detalle';

    protected $fillable = [
        'salida_id',
        'inventario_id',
        'cantidad',
        'precio_unitario',
        'observaciones'
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaInventario::class, 'salida_id');
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }
}