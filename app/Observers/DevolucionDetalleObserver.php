<?php

namespace App\Observers;

use App\Models\DevolucionDetalle;
use App\Models\MovimientoInventario;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Log;

class DevolucionDetalleObserver
{
    public function created(DevolucionDetalle $detalle): void
    {
        $devolucion = $detalle->devolucion;
        if (! $devolucion) {
            Log::warning('DevolucionDetalle sin padre al crear movimiento', ['detalle_id' => $detalle->id]);
            return;
        }

        $proyectoId = $devolucion->nombre_proyecto
            ? Proyecto::where('nombre_proyecto', $devolucion->nombre_proyecto)->value('id')
            : null;

        MovimientoInventario::create([
            'inventario_id'        => $detalle->inventario_id,
            'proyecto_id'          => $proyectoId,
            'entrada'              => 0,
            'salida'               => 0,
            'ajuste'               => 0,
            'devolucion'           => $detalle->cantidad,
            'apartado'             => 0,
            'devolucion_proveedor' => 0,
            'modificado_por'       => $devolucion->devuelto_por,
            'comentarios'          => 'Devolución #'.$devolucion->id,
        ]);
    }
}