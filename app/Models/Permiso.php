<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'nombre', 
        'slug', 
        'descripcion', 
        'modulo'
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol', 'permiso_id', 'rol', 'id', 'rol')
            ->withPivot('permitido')
            ->withTimestamps();
    }

    public function rolesPermitidos()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol', 'permiso_id', 'rol', 'id', 'rol')
            ->wherePivot('permitido', true)
            ->withTimestamps();
    }
}