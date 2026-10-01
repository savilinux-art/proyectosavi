<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class TrazabilidadService
{
    /**
     * Devuelve el cotejo vendido/entregado/devuelto para un proyecto.
     * Cada fila: inventario, vendido, entregado, devuelto, neto, faltante.
     */
    public function paraProyecto(Proyecto $proyecto): array
    {
        $vendido   = $this->vendidoPorProyecto($proyecto);
        $entregado = $this->entregadoPorProyecto($proyecto);
        $devuelto  = $this->devueltoPorProyecto($proyecto);

        return $this->merge($vendido, $entregado, $devuelto);
    }

    private function vendidoPorProyecto(Proyecto $proyecto)
    {
        return DB::table('cotizacion_detalles as cd')
            ->join('cotizaciones as c', 'c.id', '=', 'cd.cotizacion_id')
            ->where('c.proyecto_id', $proyecto->id)
            ->where('c.estatus', 'aprobada')
            ->whereNotNull('cd.inventario_id')
            ->groupBy('cd.inventario_id')
            ->selectRaw('cd.inventario_id, SUM(cd.cantidad) as total')
            ->pluck('total', 'inventario_id');
    }

    private function entregadoPorProyecto(Proyecto $proyecto)
    {
        return DB::table('salida_detalle as sd')
            ->join('salidas_inventario as s', 's.id', '=', 'sd.salida_id')
            ->where('s.nombre_proyecto', $proyecto->nombre_proyecto)
            ->groupBy('sd.inventario_id')
            ->selectRaw('sd.inventario_id, SUM(sd.cantidad) as total')
            ->pluck('total', 'inventario_id');
    }

    private function devueltoPorProyecto(Proyecto $proyecto)
    {
        return DB::table('devolucion_detalle as dd')
            ->join('devoluciones_inventario as d', 'd.id', '=', 'dd.devolucion_id')
            ->where('d.nombre_proyecto', $proyecto->nombre_proyecto)
            ->groupBy('dd.inventario_id')
            ->selectRaw('dd.inventario_id, SUM(dd.cantidad) as total')
            ->pluck('total', 'inventario_id');
    }

    private function merge($vendido, $entregado, $devuelto): array
    {
        $ids = $vendido->keys()
            ->merge($entregado->keys())
            ->merge($devuelto->keys())
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $inventarios = Inventario::whereIn('id', $ids)->get()->keyBy('id');

        $resultado = [];
        foreach ($ids as $id) {
            $v = (int) ($vendido[$id]   ?? 0);
            $e = (int) ($entregado[$id] ?? 0);
            $d = (int) ($devuelto[$id]  ?? 0);

            $resultado[] = [
                'inventario' => $inventarios[$id] ?? null,
                'vendido'    => $v,
                'entregado'  => $e,
                'devuelto'   => $d,
                'neto'       => $e - $d,
                'faltante'   => $v - ($e - $d),
            ];
        }

        return $resultado;
    }
}