<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class MovimientoInventario extends Model
{
     use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'inventario_id',
        'proyecto_id',
        'entrada',
        'salida',
        'ajuste',
        'devolucion',
        'apartado',
        'devolucion_proveedor',
        'modificado_por',
        'comentarios',
    ];

    protected $casts = [
        'entrada'              => 'integer',
        'salida'               => 'integer',
        'ajuste'               => 'integer',
        'devolucion'           => 'integer',
        'apartado'             => 'integer',
        'devolucion_proveedor' => 'integer',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function modificadoPor()
    {
        return $this->belongsTo(Usuario::class, 'modificado_por', 'usuario');
    }

    public function usuario()
    {
        return $this->modificadoPor();  // alias
    }
}