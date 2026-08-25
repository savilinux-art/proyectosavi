<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Ajusta el namespace si tu modelo está en otro lugar
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Keyboard;
use Telegram\Bot\Objects\KeyboardButton;

class TelegramLocationController extends Controller
{
    /**
     * Envía una solicitud de ubicación a un usuario de Telegram
     *
     * @param int $id ID del usuario en la tabla `usuarios`
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendLocationRequest($id)
    {
        // 1. Buscar el usuario por su ID
        $usuario = Usuario::find($id);

        // Validar existencia
        if (!$usuario) {
            return back()->with('error', '❌ Usuario no encontrado en la base de datos.');
        }

        // Validar que tenga un chat_id de Telegram
        if (empty($usuario->telegram_chat_id)) {
            return back()->with('error', '❌ El usuario ' . $usuario->nombre . ' no tiene asociado un chat_id de Telegram.');
        }

        // 2. Construir el botón de ubicación
        $locationButton = KeyboardButton::make([
            'text' => '📍 Compartir mi ubicación',
            'request_location' => true,
        ]);

        $keyboard = Keyboard::make([
            'keyboard' => [[$locationButton]],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        // 3. Inicializar el bot de Telegram
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        try {
            // 4. Enviar el mensaje con el teclado
            $telegram->sendMessage([
                'chat_id' => $usuario->telegram_chat_id,
                'text' => "Hola {$usuario->nombre}, necesito tu ubicación actual para continuar. Por favor, pulsa el botón de abajo. 🌍",
                'reply_markup' => $keyboard,
            ]);

            return back()->with('success', '✅ Solicitud de ubicación enviada correctamente a ' . $usuario->nombre . '.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al enviar: ' . $e->getMessage());
        }
    }
}