<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionUsuario extends Model
{
    protected $table = 'ubicaciones_usuarios';
    protected $fillable = [
        'usuario_id', 'instalacion_id', 'latitud', 'longitud',
        'fecha_hora', 'fuente', 'tipo', 'detalles'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }

    /**
 * Verifica si existe un inicio de jornada sin finalizar para un instalador en una instalación
 */
public static function tieneInicioSinFin($usuarioId, $instalacionId)
{
    return self::where('usuario_id', $usuarioId)
        ->where('instalacion_id', $instalacionId)
        ->where('tipo', 'inicio')
        ->exists();
}

/**
 * Verifica si existe un fin de jornada para un instalador en una instalación
 */
public static function tieneFin($usuarioId, $instalacionId)
{
    return self::where('usuario_id', $usuarioId)
        ->where('instalacion_id', $instalacionId)
        ->where('tipo', 'fin')
        ->exists();
}
}