<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instalacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre_proyecto',
        'nombre_instalacion',
        'latitud',
        'longitud',
        'direccion',
        'ubicacion_actualizada_en',
        'evidencia_inicio',
        'incidencias',
        'evidencia_fin',
        'check_list',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'estatus_instalacion',
    ];

    protected $casts = [
        'fecha_hora_inicio'        => 'datetime',
        'fecha_hora_fin'           => 'datetime',
        'ubicacion_actualizada_en' => 'datetime',
        'check_list'               => 'array',
        'latitud'                  => 'float',
        'longitud'                 => 'float',
    ];

    /* ==================== RELACIONES ==================== */
    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    public function instaladores()
    {
        return $this->belongsToMany(
            Usuario::class,
            'instalacion_instalador',
            'instalacion_id',
            'instalador_usuario',
            'id',
            'usuario'
        )->withPivot('es_principal');
    }

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionUsuario::class, 'instalacion_id');
    }

    public function solicitudesUbicacion()
    {
        return $this->hasMany(SolicitudUbicacion::class, 'instalacion_id');
    }

    public function geocercas()
    {
        return $this->hasMany(Geocerca::class, 'instalacion_id');
    }

    public function fotos()
    {
        return $this->hasMany(InstalacionFoto::class, 'instalacion_id');
    }

    public function fotosInicio()     { return $this->fotos()->where('tipo','inicio'); }
    public function fotosProceso()    { return $this->fotos()->where('tipo','proceso'); }
    public function fotosFin()        { return $this->fotos()->where('tipo','fin'); }
    public function fotosIncidencia() { return $this->fotos()->where('tipo','incidencia'); }

    public function ultimaUbicacion()
    {
        return $this->hasOne(UbicacionUsuario::class, 'instalacion_id')
                    ->latestOfMany('fecha_hora');
    }

    /* ==================== SCOPES ==================== */
    public function scopePorInstalador($query, string $usuario)
    {
        return $query->whereHas('instaladores', fn($q) => $q->where('instalador_usuario', $usuario));
    }

    public function scopeEstatus($query, $estatus)
    {
        return $query->where('estatus_instalacion', $estatus);
    }

    /* ==================== HELPERS ==================== */
    public function tieneUbicacion(): bool
    {
        return !is_null($this->latitud) && !is_null($this->longitud);
    }

    public function estaActiva(): bool
    {
        return !in_array($this->estatus_instalacion, ['completada', 'cancelada']);
    }

    public function esFinal(): bool
    {
        return in_array($this->estatus_instalacion, ['completada', 'cancelada', 'entrega']);
    }
}