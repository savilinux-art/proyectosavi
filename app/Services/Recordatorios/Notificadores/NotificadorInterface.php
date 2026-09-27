<?php

namespace App\Services\Recordatorios\Notificadores;

use App\Models\Recordatorio;

interface NotificadorInterface
{
    /**
     * Envía el recordatorio por el canal correspondiente.
     * Devuelve true si el envío fue exitoso, false si falló.
     */
    public function enviar(Recordatorio $recordatorio): bool;

    /**
     * Nombre corto del canal ('telegram', 'email', 'web', ...).
     */
    public function canal(): string;
}