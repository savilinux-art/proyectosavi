<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'nombre_categoria';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nombre_categoria'];

    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'categoria', 'nombre_categoria');
    }
}