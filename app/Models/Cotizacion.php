<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $fillable = [
        'folio', 'cliente_id', 'proyecto_id', 'fecha_emision', 'fecha_validez',
        'subtotal', 'iva', 'total', 'moneda', 'condiciones', 'estatus', 'creado_por'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_validez' => 'date',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'usuario');
    }

    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    // Generar folio automático
    public static function generarFolio()
    {
        $ultimo = self::orderBy('id', 'desc')->first();
        $numero = $ultimo ? intval(substr($ultimo->folio, 4)) + 1 : 1;
        return 'COT-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    // Recalcular totales
    public function recalcularTotales()
    {
        $this->subtotal = $this->detalles->sum('importe');
        $this->iva = $this->subtotal * 0.16;
        $this->total = $this->subtotal + $this->iva;
        $this->save();
    }
}