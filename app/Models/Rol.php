<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'rol';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['rol'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol', 'rol');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol', 'rol', 'permiso_id', 'rol', 'id')
            ->withPivot('permitido')
            ->withTimestamps();
    }

    public function permisosPermitidos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol', 'rol', 'permiso_id', 'rol', 'id')
            ->wherePivot('permitido', true)
            ->withTimestamps();
    }

    public function hasPermiso($permisoSlug)
    {
        return $this->permisosPermitidos()->where('slug', $permisoSlug)->exists();
    }
}