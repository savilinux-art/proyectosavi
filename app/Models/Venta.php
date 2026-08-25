<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $primaryKey = 'nombre_proyecto';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'titulo_venta',
        'nombre_proyecto',
        'moneda',
        'monto_venta',
        'requerimiento_venta',
        'cotizacion',
        'ubicacion',
        'vendedor',
        'fecha_hora_levantamiento',
        'levantamiento',
        'venta_ganada',
        'razon_perdida_venta',
        'estatus'
    ];

    protected $casts = [
        'venta_ganada' => 'boolean',
        'fecha_hora_levantamiento' => 'datetime',
    ];

    // Relación con el vendedor (usuario)
    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor', 'usuario');
    }

    // Relación con el proyecto
    public function proyecto()
    {
        return $this->hasOne(Proyecto::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    // Relación con instalaciones
    public function instalaciones()
    {
        return $this->hasMany(Instalacion::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    // Relación con movimientos de inventario
    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class, 'instalacion', 'nombre_proyecto');
    }

    // Relación con cliente
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    // Scopes
    public function scopeGanadas($query)
    {
        return $query->where('venta_ganada', true);
    }

    public function scopePerdidas($query)
    {
        return $query->where('venta_ganada', false);
    }
}