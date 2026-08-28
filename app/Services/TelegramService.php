<?php

namespace App\Services;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Keyboard;
use Telegram\Bot\Objects\KeyboardButton;
use Telegram\Bot\Objects\InlineKeyboardButton;
use Telegram\Bot\Objects\InlineKeyboardMarkup;
use App\Models\Usuario;
use App\Models\Instalacion;
use App\Models\UbicacionUsuario;
use App\Models\SolicitudUbicacion;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected Api $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    /**
     * Notifica a un instalador sobre una instalación asignada
     */
    public function notifyInstalacionAsignada(Usuario $instalador, Instalacion $instalacion): void
    {
        if (empty($instalador->telegram_chat_id)) {
            return;
        }

        $texto = "🔔 *Nueva instalación asignada*\n\n" .
                 "📋 Instalación #{$instalacion->id}\n" .
                 "📌 Proyecto: {$instalacion->nombre_proyecto}\n" .
                 "📍 Dirección: {$instalacion->ubicacion_actual}\n\n" .
                 "Selecciona una opción para reportar tu ubicación:";

        $inlineKeyboard = new InlineKeyboardMarkup([
            [
                InlineKeyboardButton::make([
                    'text' => '▶️ Iniciar Jornada',
                    'callback_data' => "inicio|{$instalacion->id}"
                ]),
                InlineKeyboardButton::make([
                    'text' => '⏹️ Finalizar Jornada',
                    'callback_data' => "fin|{$instalacion->id}"
                ]),
            ]
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $instalador->telegram_chat_id,
            'text' => $texto,
            'reply_markup' => $inlineKeyboard,
            'parse_mode' => 'Markdown',
        ]);
    }

    /**
     * Maneja el callback de los botones (Iniciar/Finalizar)
     */
    public function handleCallbackQuery($callbackQuery): void
    {
        $data = $callbackQuery['data'] ?? '';
        $chatId = $callbackQuery['message']['chat']['id'];
        $messageId = $callbackQuery['message']['message_id'];

        // Eliminar mensaje anterior con botones
        $this->telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);

        list($tipo, $instalacionId) = explode('|', $data);

        // Buscar usuario por chat_id
        $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
        if (!$usuario) {
            $this->sendMessage($chatId, '❌ No estás registrado.');
            return;
        }

        // Verificar que sea instalador de esa instalación (usando relación de la pivote)
        $instalacion = Instalacion::find($instalacionId);
        if (!$instalacion || !$instalacion->instaladores->contains($usuario)) {
            $this->sendMessage($chatId, '❌ No tienes permiso para esta instalación.');
            return;
        }

        // Guardar solicitud pendiente
        SolicitudUbicacion::create([
            'usuario_id' => $usuario->id,
            'chat_id' => $chatId,
            'tipo' => $tipo,
            'instalacion_id' => $instalacionId,
        ]);

        // Pedir ubicación
        $locationButton = KeyboardButton::make([
            'text' => '📍 Compartir mi ubicación',
            'request_location' => true,
        ]);

        $keyboard = Keyboard::make([
            'keyboard' => [[$locationButton]],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $texto = $tipo === 'inicio'
            ? "🟢 Para *iniciar* la jornada, comparte tu ubicación actual."
            : "🔴 Para *finalizar* la jornada, comparte tu ubicación actual.";

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $texto,
            'reply_markup' => $keyboard,
            'parse_mode' => 'Markdown',
        ]);
    }

    /**
     * Maneja la ubicación recibida
     */
    public function handleLocation(array $message): void
    {
        $chatId = $message['chat']['id'];
        $location = $message['location'];
        $lat = $location['latitude'];
        $lng = $location['longitude'];

        $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
        if (!$usuario) {
            $this->sendMessage($chatId, '❌ No estás registrado.');
            return;
        }

        // Buscar solicitud pendiente (últimos 10 minutos)
        $solicitud = SolicitudUbicacion::where('chat_id', $chatId)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();

        if (!$solicitud) {
            $this->sendMessage($chatId, '⚠️ No hay una solicitud activa.');
            return;
        }

        // Guardar ubicación
        UbicacionUsuario::create([
            'usuario_id' => $usuario->id,
            'instalacion_id' => $solicitud->instalacion_id,
            'latitud' => $lat,
            'longitud' => $lng,
            'fecha_hora' => now(),
            'fuente' => 'telegram',
            'tipo' => $solicitud->tipo,
            'detalles' => json_encode($message ?? []),
        ]);

        $solicitud->delete();

        $mensaje = $solicitud->tipo === 'inicio'
            ? '✅ Ubicación de inicio guardada. ¡Buen trabajo!'
            : '✅ Ubicación de fin guardada. ¡Hasta luego!';
        $this->sendMessage($chatId, $mensaje);
    }

    public function sendMessage(string $chatId, string $text): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}