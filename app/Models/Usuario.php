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

    // ✅ Agregar 'traccar_device_id' a fillable
    protected $fillable = [
        'usuario', 'nombre', 'correo', 'telegram_chat_id',
        'contraseña', 'rol', 'traccar_device_id'  // ← NUEVO
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

    /**
     * Accessor: Obtiene el dispositivo Traccar asociado al usuario
     *
     * @return string|null
     */
    public function getTraccarDeviceIdAttribute($value)
    {
        return $value;
    }

    /**
     * Verifica si el usuario tiene un dispositivo Traccar asignado
     *
     * @return bool
     */
    public function hasTraccarDevice()
    {
        return !is_null($this->traccar_device_id);
    }

    /**
     * Obtiene la última ubicación del usuario (desde la tabla ubicaciones_usuarios)
     *
     * @return \App\Models\UbicacionUsuario|null
     */
    public function ultimaUbicacion()
    {
        return $this->ubicaciones()
            ->latest('fecha_hora')
            ->first();
    }

    /**
     * Obtiene la ubicación en tiempo real desde Traccar (si tiene dispositivo)
     * Requiere inyectar TraccarService en el controlador, pero aquí solo definimos el método
     *
     * @return array|null
     */
    public function obtenerUbicacionTraccar()
    {
        if (!$this->traccar_device_id) {
            return null;
        }

        // Este método será usado desde el controlador
        // app(\App\Services\TraccarService::class)->getLatestPosition($this->traccar_device_id)
        return null; // Placeholder
    }
}