<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre_proyecto',
        'correo_electronico',
        'ubicacion',
        'propuesta_economica',
        'archivo_as_built',
        'credenciales',
        'salida_inventario',
        'devolucion_inventario',
        'modificado_por'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    public function modificadoPor()
    {
        return $this->belongsTo(Usuario::class, 'modificado_por', 'usuario');
    }
}