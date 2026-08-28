<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instalacion extends Model
{
    // Especificar el nombre correcto de la tabla
    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre_proyecto', 'ubicacion_actual', 'evidencia_inicio',
        'incidencias', 'evidencia_fin', 'check_list',
        'fecha_hora_inicio', 'fecha_hora_fin', 'estatus_instalacion'
    ];

    // Relación con instaladores (tabla pivote)
    public function instaladores()
    {
        return $this->belongsToMany(
            Usuario::class,
            'instalacion_instalador',
            'instalacion_id',
            'instalador_usuario',
            'id',
            'usuario' // 'usuario' es el campo en tabla usuarios (nombre de usuario)
        );
    }

    // Ubicaciones de esta instalación
    public function ubicaciones()
    {
        return $this->hasMany(UbicacionUsuario::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }
}