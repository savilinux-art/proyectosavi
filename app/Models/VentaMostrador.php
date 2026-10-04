<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaMostrador extends Model
{
    use HasFactory;

    protected $table = 'ventas_mostrador';

    protected $fillable = [
        'proyecto_id',
        'estado',
        'total',
        'observaciones',
        'creado_por',
        'modificado_por',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public const ESTADOS = ['pendiente', 'completada', 'cancelada'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function detalles()
    {
        return $this->hasMany(VentaMostradorDetalle::class, 'venta_mostrador_id');
    }

    public function salidas()
    {
        return $this->belongsToMany(
            SalidaInventario::class,
            'venta_mostrador_salida',
            'venta_mostrador_id',
            'salida_inventario_id'
        )->using(VentaMostradorSalida::class)
         ->withTimestamps();
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'usuario');
    }

    public function modificadoPor()
    {
        return $this->belongsTo(Usuario::class, 'modificado_por', 'usuario');
    }

    public function scopePendientes($q)
    {
        return $q->where('estado', 'pendiente');
    }

    public function scopeCompletadas($q)
    {
        return $q->where('estado', 'completada');
    }

    public function recalcularTotal(): void
    {
        $this->total = $this->detalles()->sum('subtotal');
        $this->save();
    }

    public function puedeEditarse(): bool
    {
        if ($this->estado === 'cancelada') {
            return false;
        }
        if ($this->salidas()->count() === 0) {
            return true;
        }
        // TODO E.3: condición de devolución registrada
        return false;
    }
}