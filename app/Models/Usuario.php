<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table      = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps    = true;

    protected $fillable = [
        'usuario',
        'nombre',
        'correo',
        'telegram_chat_id',
        'contraseña',
        'rol',
        'traccar_device_id',
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    // ─── Auth (por si migramos a Auth::attempt en el futuro) ──

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function getAuthPasswordName()
    {
        return 'contraseña';
    }

    // ─── Relaciones ───────────────────────────────────────────

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

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionUsuario::class);
    }

    // ─── Permisos ─────────────────────────────────────────────

    public function hasPermiso($slug)
    {
        if ($this->rol === 'Administrador') {
            return true;
        }

        return DB::table('permiso_rol')
            ->join('permisos', 'permiso_rol.permiso_id', '=', 'permisos.id')
            ->where('permiso_rol.rol', $this->rol)
            ->where('permisos.slug', $slug)
            ->where('permiso_rol.permitido', 1)
            ->exists();
    }

    // ─── Traccar ──────────────────────────────────────────────

    public function hasTraccarDevice()
    {
        return !is_null($this->traccar_device_id);
    }

    public function ultimaUbicacion()
    {
        return $this->ubicaciones()->latest('fecha_hora')->first();
    }

    public function obtenerUbicacionTraccar()
    {
        if (!$this->traccar_device_id) {
            return null;
        }
        return null;
    }
}