<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'usuario';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'usuario', 
        'nombre', 
        'correo', 
        'contraseña', 
        'rol'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    // Relación con el rol
    public function role()
    {
        return $this->belongsTo(Rol::class, 'rol', 'rol');
    }

    // Relación muchos a muchos con instalaciones (como instalador)
    public function instalacionesAsignadas()
    {
        return $this->belongsToMany(
            Instalacion::class,
            'instalacion_instalador',
            'instalador_usuario',
            'instalacion_id',
            'usuario',
            'id'
        )->withTimestamps();
    }

    // Relación con ventas (como vendedor)
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'vendedor', 'usuario');
    }

    // Relación con proyectos (como modificador)
    public function proyectosModificados()
    {
        return $this->hasMany(Proyecto::class, 'modificado_por', 'usuario');
    }

    // Relación con inventario (como modificador)
    public function inventarioModificado()
    {
        return $this->hasMany(Inventario::class, 'modificado_por', 'usuario');
    }

    // Relación con notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id', 'usuario');
    }

    // Método para verificar permisos
    public function hasPermiso($slug)
{
    if ($this->rol === 'Administrador') {
        return true;
    }
    return $this->role && $this->role->permisos()->where('slug', $slug)->exists();
}
}