<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'usuario', 'nombre', 'correo', 'telegram_chat_id',
        'contraseña', 'rol'
    ];

    // Relación inversa con instalaciones (a través de pivote)
    public function instalaciones()
    {
        return $this->belongsToMany(
            Instalacion::class,
            'instalacion_instalador',
            'instalador_usuario',
            'instalacion_id',
            'usuario',
            'id'
        );
    }

    // Ubicaciones del usuario
    public function ubicaciones()
    {
        return $this->hasMany(UbicacionUsuario::class);
    }
  
}