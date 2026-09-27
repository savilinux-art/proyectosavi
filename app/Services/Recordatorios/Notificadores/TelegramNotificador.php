<?php

namespace App\Services\Recordatorios\Notificadores;

use App\Models\Recordatorio;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramNotificador implements NotificadorInterface
{
    public function __construct(
        protected TelegramService $telegram
    ) {}

    public function canal(): string
    {
        return 'telegram';
    }

    public function enviar(Recordatorio $recordatorio): bool
    {
        $chatId = $recordatorio->usuario?->telegram_chat_id;

        if (!$chatId) {
            Log::warning('TelegramNotificador: usuario sin telegram_chat_id', [
                'recordatorio_id' => $recordatorio->id,
                'usuario_id'      => $recordatorio->usuario_id,
            ]);
            return false;
        }

        $texto = $this->formatearMensaje($recordatorio);

        try {
            $this->telegram->sendMessage($chatId, $texto);
            return true;
        } catch (\Throwable $e) {
            Log::error('TelegramNotificador: error enviando', [
                'recordatorio_id' => $recordatorio->id,
                'error'           => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function formatearMensaje(Recordatorio $recordatorio): string
    {
        $lineas = [];

        if ($recordatorio->esCertificado()) {
            $dias = $recordatorio->diasRestantesCertificado();
            $lineas[] = $this->emojiUrgencia($dias) . ' *Certificado próximo a vencer*';
            $lineas[] = '';
            $lineas[] = '📜 ' . $recordatorio->cert_nombre;
            if ($recordatorio->cert_emisor) {
                $lineas[] = '🏢 Emisor: ' . $recordatorio->cert_emisor;
            }
            $lineas[] = '📅 Vence: ' . $recordatorio->cert_fecha_vencimiento?->format('d/m/Y');
            $lineas[] = '⏳ Días restantes: ' . $dias;
            if ($recordatorio->cert_link_renovacion) {
                $lineas[] = '';
                $lineas[] = '🔗 Renovar: ' . $recordatorio->cert_link_renovacion;
            }
        } else {
            $lineas[] = '🔔 *' . $recordatorio->titulo . '*';
            if ($recordatorio->descripcion) {
                $lineas[] = '';
                $lineas[] = $recordatorio->descripcion;
            }
        }

        return implode("\n", $lineas);
    }

    protected function emojiUrgencia(?int $dias): string
    {
        if ($dias === null) return '⚪';
        if ($dias < 0)     return '❌';
        if ($dias <= 7)    return '🔴';
        if ($dias <= 15)   return '🟠';
        if ($dias <= 30)   return '🟡';
        return '⚪';
    }
}