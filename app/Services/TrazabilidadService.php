<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Models\Inventario;
use App\Models\EvidenciaEntrega;
use App\Models\TrazabilidadDiscrepancia;
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

    // ── FASE 2 TRAZABILIDAD ──────────────────────────────────────

    /**
     * Resultado de paraProyecto() + categoría + estado + discrepancias + evidencias.
     * Es el "paraProyecto enriquecido" que consume la vista.
     *
     * Solo LEE la tabla trazabilidad_discrepancias. La detección/creación
     * automática de discrepancias NO es parte de 2.2 (ver nota al final).
     */
    public function trazabilidadCompleta(Proyecto $proyecto): array
    {
        $base = $this->paraProyecto($proyecto);

        // Discrepancias del proyecto, agrupadas por inventario_id.
        $discrepanciasPorInv = TrazabilidadDiscrepancia::query()
            ->where('proyecto_id', $proyecto->id)
            ->orderByDesc('detectado_en')
            ->get()
            ->groupBy('inventario_id');

        // Evidencias del proyecto, agrupadas por salida_id.
        // (Aplican a todas las filas por igual: son evidencia de la ENTREGA,
        //  no del artículo individual.)
        $evidenciasPorSalida = EvidenciaEntrega::query()
            ->where('proyecto_id', $proyecto->id)
            ->orderBy('id')
            ->get()
            ->groupBy('salida_id');

        return array_map(function (array $item) use ($discrepanciasPorInv, $evidenciasPorSalida) {
            $faltante = $item['faltante'];

            if ($faltante > 0) {
                $estado = 'faltante';
            } elseif ($faltante < 0) {
                $estado = 'sobrante';
            } else {
                $estado = 'completo';
            }

            $invId = $item['inventario']->id ?? null;

            $item['estado']               = $estado;
            $item['discrepancias']        = $invId !== null
                ? ($discrepanciasPorInv->get($invId)?->all() ?? [])
                : [];
            $item['evidencias_por_salida'] = $evidenciasPorSalida;

            return $item;
        }, $base);
    }

    /**
     * Responsables por salida: quién autorizó, quién recibió, quién entregó,
     * y cuántas evidencias digitales hay subidas.
     *
     * @return array<int, array{
     *   salida_id:int,
     *   fecha_hora_salida:mixed,
     *   entregado_por:string,
     *   entregado_a:string,
     *   observaciones:?string,
     *   evidencias_count:int
     * }>
     */
    public function responsablesPorProyecto(Proyecto $proyecto): array
    {
        $rows = DB::table('salidas_inventario as s')
            ->leftJoin('evidencias_entrega as e', 'e.salida_id', '=', 's.id')
            ->where('s.nombre_proyecto', $proyecto->nombre_proyecto)
            ->groupBy(
                's.id',
                's.fecha_hora_salida',
                's.entregado_por',
                's.entregado_a',
                's.observaciones'
            )
            ->orderByDesc('s.fecha_hora_salida')
            ->select([
                's.id as salida_id',
                's.fecha_hora_salida',
                's.entregado_por',
                's.entregado_a',
                's.observaciones',
                DB::raw('COUNT(e.id) as evidencias_count'),
            ])
            ->get();

        return $rows->map(fn ($r) => [
            'salida_id'         => (int) $r->salida_id,
            'fecha_hora_salida' => $r->fecha_hora_salida,
            'entregado_por'     => $r->entregado_por,
            'entregado_a'       => $r->entregado_a,
            'observaciones'     => $r->observaciones,
            'evidencias_count'  => (int) $r->evidencias_count,
        ])->all();
    }

    // ── Wrappers del motor (Fase 4) ──────────────────────────────
    public function porArticulo(int $inventarioId): array
    {
        return [];
    }

    public function porCliente(int $clienteId): array
    {
        return [];
    }

    public function porResponsable(string $usuario, ?\Carbon\Carbon $desde = null, ?\Carbon\Carbon $hasta = null): array
    {
        return [];
    }

    public function porPeriodo(\Carbon\Carbon $desde, \Carbon\Carbon $hasta, ?string $nombreProyecto = null): array
    {
        return [];
    }

    // ── Discrepancias (Fase 2) ────────────────────────────────────

    /**
     * Marca una discrepancia como resuelta (opción B, Q-107).
     * NO guía la corrección: solo audita quién la resolvió, cuándo,
     * y opcionalmente a qué salida/cotización apunta la corrección.
     *
     * @param  array{
     *   notas?:?string,
     *   corregido_por_salida_id?:?int,
     *   corregido_por_cotizacion_id?:?int
     * } $datos
     */
    public function registrarResolucion(int $discrepanciaId, string $usuario, array $datos): TrazabilidadDiscrepancia
    {
        $discrepancia = TrazabilidadDiscrepancia::findOrFail($discrepanciaId);

        $discrepancia->estado            = 'resuelta';
        $discrepancia->resuelto_en       = now();
        $discrepancia->resuelto_por      = $usuario;
        $discrepancia->notas_resolucion  = $datos['notas'] ?? null;

        $discrepancia->corregido_por_salida_id     = $datos['corregido_por_salida_id']     ?? null;
        $discrepancia->corregido_por_cotizacion_id = $datos['corregido_por_cotizacion_id'] ?? null;

        $discrepancia->save();

        return $discrepancia;
    }

    // ── Exportación (Fase 5) ─────────────────────────────────────
    public function exportarPdfProyecto(Proyecto $proyecto)
    {
        return null;
    }
}
