<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInventario extends Model
{
    use HasFactory;

    protected $table = 'salidas_inventario';

    protected $fillable = [
        'nombre_proyecto',
        'entregado_por',
        'entregado_a',
        'productos',
        'fecha_hora_salida',
        'observaciones'
    ];

    protected $casts = [
        'productos' => 'array',
        'fecha_hora_salida' => 'datetime',
    ];

    public function imprimir($id)
    {
         return $this->downloadPDF($id);
    }

    public function show($id)
    {
    $salida = SalidaInventario::with(['proyecto', 'entregadoPor', 'entregadoA'])->findOrFail($id);
    
    // Decodificar productos (JSON string → array)
    $productos = json_decode($salida->productos, true);
    if (!is_array($productos)) {
        $productos = [];
    }

    return view('salidas.show', compact('salida', 'productos'));
    }



    public function proyecto()
    {
        return $this->belongsTo(Venta::class, 'nombre_proyecto', 'nombre_proyecto');
    }

    public function entregadoPor()
    {
        return $this->belongsTo(Usuario::class, 'entregado_por', 'usuario');
    }

    public function entregadoA()
    {
        return $this->belongsTo(Usuario::class, 'entregado_a', 'usuario');
    }
}