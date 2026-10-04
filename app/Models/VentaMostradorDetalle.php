<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaMostradorDetalle extends Model
{
    use HasFactory;

    protected $table = 'venta_mostrador_detalles';

    protected $fillable = [
        'venta_mostrador_id',
        'inventario_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function ventaMostrador()
    {
        return $this->belongsTo(VentaMostrador::class, 'venta_mostrador_id');
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function recalcularSubtotal(): void
    {
        $this->subtotal = $this->cantidad * ($this->precio_unitario - $this->descuento);
    }
}