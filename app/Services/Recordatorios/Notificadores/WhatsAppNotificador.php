<?php

namespace App\Services\Recordatorios\Notificadores;

use App\Models\Recordatorio;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificador implements NotificadorInterface
{
    public function enviar(Recordatorio $recordatorio): bool
    {
        Log::info('WhatsAppNotificador: canal no implementado (FASE 2)', [
            'recordatorio_id' => $recordatorio->id,
        ]);

        return false;
    }
}
