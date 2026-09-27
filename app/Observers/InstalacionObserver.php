<?php

namespace App\Observers;

use App\Models\Instalacion;
use App\Models\Recordatorio;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class InstalacionObserver
{
    /**
     * Avisos por estatus:
     *   completada → revisar y entregar al cliente
     *   entrega    → pendiente de facturar (cierra los pendientes previos)
     *   cancelada  → instalación cancelada (cierra todos los pendientes previos)
     */
    protected const AVISOS = [
        'completada' => [
            'titulo'      => 'completada — Revisar y entregar',
            'descripcion' => 'El instalador terminó la instalación. Revisar y marcar como entregada al cliente.',
        ],
        'entrega'    => [
            'titulo'      => 'entregada — Pendiente de facturar',
            'descripcion' => 'La instalación fue entregada al cliente. Pendiente de facturar.',
        ],
        'cancelada'  => [
            'titulo'      => 'cancelada',
            'descripcion' => 'La instalación fue cancelada.',
        ],
    ];

    public function updated(Instalacion $instalacion): void
    {
        if (!$instalacion->wasChanged('estatus_instalacion')) {
            return;
        }

        $nuevo    = $instalacion->estatus_instalacion;
        $anterior = $instalacion->getOriginal('estatus_instalacion');

        if (!array_key_exists($nuevo, self::AVISOS)) {
            return;
        }

        // Cerrar pendientes previos al pasar a entrega o cancelada
        if (in_array($nuevo, ['entrega', 'cancelada'], true)) {
            $cerrados = Recordatorio::where('recordable_type', Instalacion::class)
                ->where('recordable_id', $instalacion->id)
                ->where('estatus', 'pendiente')
                ->where('tipo', 'sistema')
                ->update(['estatus' => 'completado']);

            if ($cerrados > 0) {
                Log::info('Recordatorios previos cerrados por cambio de estatus', [
                    'instalacion_id' => $instalacion->id,
                    'nuevo_estatus'  => $nuevo,
                    'cerrados'       => $cerrados,
                ]);
            }
        }

        $admins = Usuario::where('rol', 'Administrador')->get();
        if ($admins->isEmpty()) {
            Log::warning('InstalacionObserver: no hay admins para notificar', [
                'instalacion_id' => $instalacion->id,
            ]);
            return;
        }

        $cfg         = self::AVISOS[$nuevo];
        $titulo      = "Instalación #{$instalacion->id} {$cfg['titulo']}";
        $descripcion = $cfg['descripcion']
                     . " Proyecto: \"{$instalacion->nombre_proyecto}\"."
                     . " Instalación: \"{$instalacion->nombre_instalacion}\".";

        foreach ($admins as $admin) {
            try {
                Recordatorio::create([
                    'tipo'                  => 'sistema',
                    'usuario_id'            => $admin->id,
                    'created_by'            => $admin->id,
                    'titulo'                => mb_substr($titulo, 0, 200),
                    'descripcion'           => $descripcion,
                    'fecha_hora_programada' => now(),
                    'recurrencia'           => 'una_vez',
                    'canal'                 => ['telegram', 'web'],
                    'estatus'               => 'pendiente',
                    'intentos'              => 0,
                    'recordable_type'       => Instalacion::class,
                    'recordable_id'         => $instalacion->id,
                    'metadata'              => [
                        'instalacion_id'  => $instalacion->id,
                        'nombre_proyecto' => $instalacion->nombre_proyecto,
                        'estatus_previo'  => $anterior,
                        'estatus_nuevo'   => $nuevo,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error('InstalacionObserver: error creando Recordatorio', [
                    'instalacion_id' => $instalacion->id,
                    'admin_id'       => $admin->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        Log::info('Recordatorios de sistema creados', [
            'instalacion_id' => $instalacion->id,
            'estatus'        => $nuevo,
            'admins'         => $admins->count(),
        ]);
    }
}
