<?php

namespace App\Services\Recordatorios\Notificadores;

use App\Models\Recordatorio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificador implements NotificadorInterface
{
    public function canal(): string
    {
        return 'email';
    }

    public function enviar(Recordatorio $recordatorio): bool
    {
        $email = $recordatorio->usuario?->correo;

        if (!$email) {
            Log::warning('EmailNotificador: usuario sin correo', [
                'recordatorio_id' => $recordatorio->id,
            ]);
            return false;
        }

        try {
            $titulo = $recordatorio->esCertificado()
                ? 'Certificado próximo a vencer: ' . $recordatorio->cert_nombre
                : $recordatorio->titulo;

            $cuerpo = $recordatorio->esCertificado()
                ? "Vence el {$recordatorio->cert_fecha_vencimiento?->format('d/m/Y')} ({$recordatorio->diasRestantesCertificado()} días)."
                : ($recordatorio->descripcion ?? '');

            Mail::raw($cuerpo, function ($message) use ($email, $titulo) {
                $message->to($email)->subject($titulo);
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('EmailNotificador: error', [
                'recordatorio_id' => $recordatorio->id,
                'error'           => $e->getMessage(),
            ]);
            return false;
        }
    }
}