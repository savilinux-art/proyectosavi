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
        $this->notificadores = [
            app(\App\Services\Recordatorios\Notificadores\TelegramNotificador::class),
            app(\App\Services\Recordatorios\Notificadores\EmailNotificador::class),
            app(\App\Services\Recordatorios\Notificadores\WebNotificador::class),
        ];
    }

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
            // ----- CERTIFICADOS: re-agendar o cerrar ciclo -----
            if ($recordatorio->esCertificado()) {
                $reagendado = $this->reagendarCertificado($recordatorio);

                $recordatorio->update([
                    'enviado_at'   => now(),
                    'intentos'     => $reagendado ? 0 : $recordatorio->intentos + 1,
                    'ultimo_error' => null,
                    'estatus'      => $reagendado ? 'pendiente' : 'completado',
                ]);

                return true;
            }

            // ----- RECORDATORIOS NORMALES -----
            $recordatorio->update([
                'estatus'      => 'enviado',
                'enviado_at'   => now(),
                'intentos'     => $recordatorio->intentos + 1,
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

    /**
     * Re-agenda el certificado al siguiente aviso de cert_avisos_dias.
     * Devuelve true si se re-agendó, false si ya era el último aviso (cerrar ciclo).
     */
    protected function reagendarCertificado(Recordatorio $recordatorio): bool
    {
        $avisos = $recordatorio->cert_avisos_dias;
        if (empty($avisos) || !$recordatorio->cert_fecha_vencimiento) {
            return false;
        }

        sort($avisos); // [0,1,3,7,15]

        $venc           = $recordatorio->cert_fecha_vencimiento->copy()->startOfDay();
        $diasRestantes  = (int) now()->startOfDay()->diffInDays($venc, false);

        // Avisos estrictamente menores que los días restantes actuales
        $avisosRestantes = array_values(array_filter($avisos, fn ($a) => $a < $diasRestantes));

        if (empty($avisosRestantes)) {
            // Ya se envió el último aviso (el más cercano a 0) → cerrar ciclo
            return false;
        }

        // El siguiente aviso es el mayor de los que quedan por debajo del actual
        $siguienteAviso = max($avisosRestantes);

        $recordatorio->update([
            'fecha_hora_programada' => $venc->copy()->subDays($siguienteAviso)->setTime(9, 0, 0),
        ]);

        return true;
    }

    protected function programarSiguienteSiRecurrente(Recordatorio $recordatorio): void
    {
        // Defensivo: certificados nunca se clonan por recurrencia
        if ($recordatorio->esCertificado()) {
            return;
        }

        $siguiente = $this->recurrence->siguiente($recordatorio);
        if (!$siguiente) {
            return;
        }

        $nuevo = $recordatorio->replicate(['enviado_at', 'intentos', 'ultimo_error']);
        $nuevo->fecha_hora_programada = $siguiente;
        $nuevo->estatus               = 'pendiente';
        $nuevo->intentos              = 0;
        $nuevo->enviado_at            = null;
        $nuevo->save();
    }
}