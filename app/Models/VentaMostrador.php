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
    'subtotal',
    'iva',
    'total',
    'moneda',
    'observaciones',
    'creado_por',
    'modificado_por',
];

protected $casts = [
    'subtotal' => 'decimal:2',
    'iva'      => 'decimal:2',
    'total'    => 'decimal:2',
    'moneda'   => 'string',
];

    public const ESTADO_PENDIENTE  = 'pendiente';
    public const ESTADO_COMPLETADA = 'completada';
    public const ESTADO_CANCELADA  = 'cancelada';

    public const IVA_RATE      = 0.16;
    public const MONEDAS       = ['MXN', 'USD'];
    public const MONEDA_DEFAULT = 'MXN';
    public const ESTADOS = [
    self::ESTADO_PENDIENTE,
    self::ESTADO_COMPLETADA,
    self::ESTADO_CANCELADA,
    ];

    /**
     * Estados a los que se puede transicionar desde el estado actual.
     * Regla (v22): pendiente → completada | cancelada.
     *              completada y cancelada son terminales.
     */
    public function estadosPermitidos(): array
    {
        if ($this->estado !== self::ESTADO_PENDIENTE) {
            return [];
        }
        return [self::ESTADO_COMPLETADA, self::ESTADO_CANCELADA];
    }

    public function puedeCambiarEstadoA(string $nuevo): bool
    {
        return in_array($nuevo, $this->estadosPermitidos(), true);
    }

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

   public function recalcularTotales(): void
    {
        $this->subtotal = (float) $this->detalles()->sum('subtotal');
        $this->iva      = round($this->subtotal * self::IVA_RATE, 2);
        $this->total    = $this->subtotal + $this->iva;
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