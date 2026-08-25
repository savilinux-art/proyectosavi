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
        'productos',
        'fecha_hora_devolucion',
        'observaciones'
    ];

    protected $casts = [
        'productos' => 'array',
        'fecha_hora_devolucion' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    public function devueltoPor()
    {
        return $this->belongsTo(Usuario::class, 'devuelto_por', 'usuario');
    }

    public function recibidoPor()
    {
        return $this->belongsTo(Usuario::class, 'recibido_por', 'usuario');
    }
}