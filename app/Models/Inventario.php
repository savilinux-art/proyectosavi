<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $fillable = [
        'existencia',
        'modelo',
        'descripcion',
        'marca',
        'categoria',
        'almacen',
        'apea',
        'imagen',
        'fecha_modificacion',
        'comentarios',
        'apartados',
        'cantidad_apartados',
        'modificado_por'
    ];

    public function categoriaRelacion()
    {
        return $this->belongsTo(Categoria::class, 'categoria', 'nombre_categoria');
    }

    public function modificadoPor()
    {
        return $this->belongsTo(Usuario::class, 'modificado_por', 'usuario');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'inventario_id');
    }
}