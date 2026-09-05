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
        'almacen_url',
        'apea',
        'imagen_url',
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

    public function getImagenUrlAttribute($value)
{
    // Si no hay valor, retorna null o una imagen por defecto
    if (empty($value)) {
        return null; // o asset('img/default-product.png')
    }

    // Si ya es una URL completa (http o https), la devolvemos tal cual
    if (filter_var($value, FILTER_VALIDATE_URL)) {
        return $value;
    }

    // Si comienza con /img/... la convertimos a URL usando asset()
    // asset() genera la URL completa de tu aplicación
    return asset($value);
}

}