<?php

namespace App\Observers;

use App\Models\MovimientoInventario;
use App\Models\Proyecto;
use App\Models\SalidaDetalle;
use Illuminate\Support\Facades\Log;

class SalidaDetalleObserver
{
    public function created(SalidaDetalle $detalle): void
    {
        $salida = $detalle->salida;
        if (! $salida) {
            Log::warning('SalidaDetalle sin padre al crear movimiento', ['detalle_id' => $detalle->id]);
            return;
        }

        $proyectoId = Proyecto::where('nombre_proyecto', $salida->nombre_proyecto)->value('id');

        MovimientoInventario::create([
            'inventario_id'        => $detalle->inventario_id,
            'proyecto_id'          => $proyectoId,          // NULL si no matchea
            'entrada'              => 0,
            'salida'               => $detalle->cantidad,
            'ajuste'               => 0,
            'devolucion'           => 0,
            'apartado'             => 0,
            'devolucion_proveedor' => 0,
            'modificado_por'       => $salida->entregado_por,
            'comentarios'          => 'Salida #'.$salida->id,
        ]);
    }
}