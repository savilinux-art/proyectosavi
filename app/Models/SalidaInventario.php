<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInventario extends Model
{
    use HasFactory;

    protected $table = 'salidas_inventario';

    protected $fillable = [
        'nombre_proyecto',
        'entregado_por',
        'entregado_a',
        'fecha_hora_salida',
        'observaciones'
        // 'productos' eliminado
    ];

    // Relación con los detalles
    public function detalles()
    {
        return $this->hasMany(SalidaDetalle::class, 'salida_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    public function entregadoPor()
    {
        return $this->belongsTo(Usuario::class, 'entregado_por', 'usuario');
    }

    public function entregadoA()
    {
        return $this->belongsTo(Usuario::class, 'entregado_a', 'usuario');
    }
}