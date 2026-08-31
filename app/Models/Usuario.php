<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use Illuminate\Support\Facades\DB;

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
    
    /**
 * Verifica si el usuario tiene un permiso específico (por slug)
 * 
 * @param string $slug
 * @return bool
 */
    public function hasPermiso($slug)
    {
        if ($this->rol === 'Administrador') {
            return true;
        }

        $exists = DB::table('permiso_rol')
            ->join('permisos', 'permiso_rol.permiso_id', '=', 'permisos.id')
            ->where('permiso_rol.rol', $this->rol)
            ->where('permisos.slug', $slug)
            ->where('permiso_rol.permitido', 1)
            ->exists();

        return $exists;
    }


}