<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre_proyecto',
        'ubicacion_actual',
        'evidencia_inicio',
        'incidencias',
        'evidencia_fin',
        'check_list',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'estatus_instalacion'
    ];

    protected $casts = [
        'check_list' => 'array',
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
    ];

    // Relación con el proyecto (venta)
    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    // Relación muchos a muchos con instaladores (usuarios)
    public function instaladores()
{
    return $this->belongsToMany(Usuario::class, 'instalacion_instalador', 'instalacion_id', 'instalador_usuario', 'id', 'usuario')
        ->withTimestamps();
}

    // Relación con el estatus
    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'estatus_instalacion', 'estatus');
    }
}