<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillTrazabilidad extends Command
{
    protected $signature   = 'trazabilidad:backfill {--dry-run}';
    protected $description = 'Puebla movimientos_inventario desde salida/devolucion detalle';

    public function handle(): int
    {
        $dry = $this->option('dry-run');

        $rows = DB::table('salida_detalle as sd')
            ->join('salidas_inventario as s', 's.id', '=', 'sd.salida_id')
            ->leftJoin('proyectos as p', 'p.nombre_proyecto', '=', 's.nombre_proyecto')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('movimientos_inventario as m')
                  ->whereColumn('m.inventario_id', 'sd.inventario_id')
                  ->whereRaw("m.comentarios = CONCAT('Salida #', sd.salida_id)");
            })
            ->selectRaw("
                sd.inventario_id,
                p.id AS proyecto_id,
                sd.cantidad AS salida,
                s.entregado_por AS modificado_por,
                CONCAT('Salida #', sd.salida_id) AS comentarios,
                s.fecha_hora_salida AS created_at
            ")
            ->get();

        $this->info("Salidas a insertar: {$rows->count()}");

        $rowsDev = DB::table('devolucion_detalle as dd')
            ->join('devoluciones_inventario as d', 'd.id', '=', 'dd.devolucion_id')
            ->leftJoin('proyectos as p', 'p.nombre_proyecto', '=', 'd.nombre_proyecto')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('movimientos_inventario as m')
                  ->whereColumn('m.inventario_id', 'dd.inventario_id')
                  ->whereRaw("m.comentarios = CONCAT('Devolución #', dd.devolucion_id)");
            })
            ->selectRaw("
                dd.inventario_id,
                p.id AS proyecto_id,
                dd.cantidad AS devolucion,
                d.devuelto_por AS modificado_por,
                CONCAT('Devolución #', dd.devolucion_id) AS comentarios,
                d.fecha_hora_devolucion AS created_at
            ")
            ->get();

        $this->info("Devoluciones a insertar: {$rowsDev->count()}");

        if ($dry) {
            $this->warn('Dry-run: no se insertó nada.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($rows, $rowsDev) {
            $now = now();
            foreach ([['salida', $rows], ['devolucion', $rowsDev]] as [$col, $set]) {
                foreach ($set as $r) {
                    DB::table('movimientos_inventario')->insert([
                        'inventario_id'        => $r->inventario_id,
                        'proyecto_id'          => $r->proyecto_id,
                        'entrada'              => 0,
                        'salida'               => $col === 'salida'     ? $r->salida     : 0,
                        'ajuste'               => 0,
                        'devolucion'           => $col === 'devolucion' ? $r->devolucion : 0,
                        'apartado'             => 0,
                        'devolucion_proveedor' => 0,
                        'modificado_por'       => $r->modificado_por,
                        'comentarios'          => $r->comentarios,
                        'created_at'           => $r->created_at ?? $now,
                        'updated_at'           => $now,
                    ]);
                }
            }
        });

        $this->info('Backfill OK.');
        return self::SUCCESS;
    }
}