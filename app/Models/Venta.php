<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
   

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
        'estatus',
        'cotizacion_path',
        'levantamiento_path',
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

  
public function tieneDocumento(string $tipo): bool
{
    if (!in_array($tipo, ['cotizacion', 'levantamiento'], true)) {
        return false;
    }

    $path = $this->{"{$tipo}_path"};
    if (!empty($path) && Storage::disk('local')->exists($path)) {
        return true;
    }

    $blob = $this->{$tipo} ?? '';
    return strlen($blob) > 200 && (
        str_starts_with($blob, '%PDF-')
        || str_starts_with($blob, "\xD0\xCF\x11\xE0")
        || str_starts_with($blob, "PK\x03\x04")
    );
}


}