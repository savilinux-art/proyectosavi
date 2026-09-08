<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionInventario extends Model
{
    use HasFactory;

    protected $table = 'devoluciones_inventario';

    protected $fillable = [
        'nombre_proyecto',
        'devuelto_por',
        'recibido_por',
        'fecha_hora_devolucion',
        'observaciones'
        // 👈 ELIMINADO: 'productos'
    ];

    // 👈 ELIMINADO: protected $casts = [ 'productos' => 'array' ]

    // Relación con los detalles de la devolución
    public function detalles()
    {
        return $this->hasMany(DevolucionDetalle::class, 'devolucion_id');
    }

    // Relación con el proyecto (venta)
    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    // Relación con el usuario que devuelve
    public function devueltoPor()
    {
        return $this->belongsTo(Usuario::class, 'devuelto_por', 'usuario');
    }

    // Relación con el usuario que recibe
    public function recibidoPor()
    {
        return $this->belongsTo(Usuario::class, 'recibido_por', 'usuario');
    }
}