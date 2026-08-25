<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'inventario_id',
        'entrada',
        'salida',
        'ajuste',
        'devolucion',
        'apartado',
        'instalacion',
        'devolucion_proveedor',
        'modificado_por'
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function proyectoInstalacion()
    {
        return $this->belongsTo(Venta::class, 'instalacion', 'nombre_proyecto');
    }

    public function modificadoPor()
    {
        return $this->belongsTo(Usuario::class, 'modificado_por', 'usuario');
    }
}