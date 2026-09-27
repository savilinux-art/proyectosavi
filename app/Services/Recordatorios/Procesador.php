<?php

namespace App\Services\Recordatorios;

use App\Models\Recordatorio;
use App\Services\Recordatorios\Notificadores\NotificadorInterface;
use Illuminate\Support\Facades\Log;

class Procesador
{
    /** @var NotificadorInterface[] */
    protected array $notificadores = [];

        public function __construct(
        protected RecurrenceCalculator $recurrence
    ) {
        // Se inyectan todos los notificadores disponibles
        $this->notificadores = [
            app(\App\Services\Recordatorios\Notificadores\TelegramNotificador::class),
            app(\App\Services\Recordatorios\Notificadores\EmailNotificador::class),
            app(\App\Services\Recordatorios\Notificadores\WebNotificador::class),
        ];
    }

    /**
     * Procesa todos los recordatorios pendientes cuya hora ya pasó.
     * Devuelve [enviados, fallidos].
     */
    public function procesarPendientes(): array
    {
        $pendientes = Recordatorio::pendientesDeEnviar()
            ->with(['usuario'])
            ->get();

        $enviados = 0;
        $fallidos = 0;

        foreach ($pendientes as $recordatorio) {
            if ($this->procesarUno($recordatorio)) {
                $enviados++;
            } else {
                $fallidos++;
            }
        }

        return ['enviados' => $enviados, 'fallidos' => $fallidos];
    }

    protected function procesarUno(Recordatorio $recordatorio): bool
    {
        $canales = $recordatorio->canal ?? ['telegram'];
        $exitos = 0;
        $errores = [];

        foreach ($this->notificadores as $notificador) {
            if (!in_array($notificador->canal(), $canales, true)) {
                continue;
            }

            if ($notificador->enviar($recordatorio)) {
                $exitos++;
            } else {
                $errores[] = $notificador->canal();
            }
        }

        if ($exitos > 0) {
            $recordatorio->update([
                'estatus'    => 'enviado',
                'enviado_at' => now(),
                'intentos'   => $recordatorio->intentos + 1,
                'ultimo_error' => empty($errores) ? null : 'Falló en: ' . implode(',', $errores),
            ]);

            $this->programarSiguienteSiRecurrente($recordatorio);
            return true;
        }

        // Ningún canal funcionó
        $recordatorio->update([
            'intentos'     => $recordatorio->intentos + 1,
            'ultimo_error' => 'Ningún canal tuvo éxito: ' . implode(',', $errores),
            'estatus'      => $recordatorio->intentos + 1 >= 3 ? 'error' : 'pendiente',
        ]);

        Log::warning('Recordatorio no enviado', [
            'recordatorio_id' => $recordatorio->id,
            'intentos'        => $recordatorio->intentos + 1,
        ]);

        return false;
    }

    protected function programarSiguienteSiRecurrente(Recordatorio $recordatorio): void
    {
        $siguiente = $this->recurrence->siguiente($recordatorio);
        if (!$siguiente) return;

        // Clonamos como nuevo pendiente
        $nuevo = $recordatorio->replicate(['enviado_at', 'intentos', 'ultimo_error']);
        $nuevo->fecha_hora_programada = $siguiente;
        $nuevo->estatus = 'pendiente';
        $nuevo->intentos = 0;
        $nuevo->enviado_at = null;
        $nuevo->save();
    }
}