<?php

namespace App\Services\Recordatorios\Notificadores;

use App\Models\Recordatorio;
use App\Models\NotificacionWeb;
use Illuminate\Support\Facades\Log;

class WebNotificador implements NotificadorInterface
{
    public function canal(): string
    {
        return 'web';
    }

    public function enviar(Recordatorio $recordatorio): bool
    {
        try {
            $titulo = $recordatorio->esCertificado()
                ? 'Certificado: ' . $recordatorio->cert_nombre
                : $recordatorio->titulo;

            $cuerpo = $recordatorio->esCertificado()
                ? "Vence el {$recordatorio->cert_fecha_vencimiento?->format('d/m/Y')} ({$recordatorio->diasRestantesCertificado()} días)."
                : ($recordatorio->descripcion ?? '');

            NotificacionWeb::create([
                'usuario_id'      => $recordatorio->usuario_id,
                'recordatorio_id' => $recordatorio->id,
                'titulo'          => $titulo,
                'cuerpo'          => $cuerpo,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('WebNotificador: error', [
                'recordatorio_id' => $recordatorio->id,
                'error'           => $e->getMessage(),
            ]);
            return false;
        }
    }
}