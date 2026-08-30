<?php

namespace App\Services;

use App\Models\Instalacion;
use App\Models\SolicitudUbicacion;
use App\Models\UbicacionUsuario;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;



class TelegramService
{
    protected Api $telegram;

    public function __construct()
    {
        // ✅ Corrección: usar la clave correcta de config
        $token = config('telegram.bot_token');
        if (empty($token)) {
            throw new \Exception('Token de Telegram no configurado. Agrega TELEGRAM_BOT_TOKEN en .env');
        }

        $this->telegram = new Api($token);
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
        'Selecciona una opción para reportar tu ubicación:';

    // ✅ Usar Keyboard::make()->inline() en lugar de InlineKeyboardMarkup
    $inlineKeyboard = Keyboard::make()
        ->inline()
        ->row([
            Keyboard::inlineButton([
                'text' => '▶️ Iniciar Jornada',
                'callback_data' => "inicio|{$instalacion->id}",
            ]),
            Keyboard::inlineButton([
                'text' => '⏹️ Finalizar Jornada',
                'callback_data' => "fin|{$instalacion->id}",
            ]),
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
        $chatId = $callbackQuery['message']['chat']['id'] ?? null;
        $callbackData = $callbackQuery['data'] ?? null;
        $messageId = $callbackQuery['message']['message_id'] ?? null;

        if (empty($chatId) || empty($callbackData)) {
            return;
        }

        // Eliminar el mensaje anterior
        if ($messageId) {
            try {
                $this->telegram->deleteMessage([
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                ]);
            } catch (\Exception $e) {
                Log::warning('No se pudo eliminar el mensaje de Telegram', ['chat_id' => $chatId, 'message_id' => $messageId]);
            }
        }

        [$tipo, $instalacionId] = array_pad(explode('|', $callbackData, 2), 2, null);

        if ($tipo !== 'inicio' && $tipo !== 'fin' || !is_numeric($instalacionId)) {
            $this->sendMessage($chatId, '❌ Acción no válida.');
            return;
        }

        $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
        if (!$usuario) {
            $this->sendMessage($chatId, '❌ No estás registrado.');
            return;
        }

        $instalacion = Instalacion::find($instalacionId);
        if (!$instalacion || !$instalacion->instaladores->contains($usuario)) {
            $this->sendMessage($chatId, '❌ No tienes permiso para esta instalación.');
            return;
        }

        // ✅ Control de jornada corregido (sin whereDoesntHave)
        if ($tipo === 'fin') {
            $inicioExistente = UbicacionUsuario::where('usuario_id', $usuario->id)
                ->where('instalacion_id', $instalacionId)
                ->where('tipo', 'inicio')
                ->exists();

            if (!$inicioExistente) {
                $this->sendMessage($chatId, '❌ No puedes finalizar sin haber iniciado la jornada primero.');
                return;
            }
        }

        if ($tipo === 'inicio') {
            $inicioSinFin = UbicacionUsuario::where('usuario_id', $usuario->id)
                ->where('instalacion_id', $instalacionId)
                ->where('tipo', 'inicio')
                ->whereNotExists(function ($query) use ($usuario, $instalacionId) {
                    $query->from('ubicaciones_usuarios')
                        ->whereColumn('ubicaciones_usuarios.usuario_id', $usuario->id)
                        ->where('instalacion_id', $instalacionId)
                        ->where('tipo', 'fin');
                })
                ->exists();

            if ($inicioSinFin) {
                $this->sendMessage($chatId, '⚠️ Ya tienes una jornada iniciada para esta instalación. Finaliza primero antes de iniciar nuevamente.');
                return;
            }
        }

        // Crear solicitud de ubicación
        SolicitudUbicacion::create([
            'usuario_id' => $usuario->id,
            'chat_id' => $chatId,
            'tipo' => $tipo,
            'instalacion_id' => $instalacionId,
        ]);

        $this->sendLocationRequest($chatId, $tipo);
    }

    /**
     * Envía un mensaje con el botón para compartir ubicación
     */
    private function sendLocationRequest(string $chatId, string $tipo): void
    {
        $texto = $tipo === 'inicio'
            ? "🟢 Para *iniciar* la jornada, comparte tu ubicación actual presionando el botón de abajo."
            : "🔴 Para *finalizar* la jornada, comparte tu ubicación actual presionando el botón de abajo.";

        $keyboard = Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row([
                Keyboard::button([
                    'text' => '📍 Compartir mi ubicación',
                    'request_location' => true,
                ])
            ]);

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
        $chatId = $message['chat']['id'] ?? null;
        $location = $message['location'] ?? null;

        if (empty($chatId) || empty($location)) {
            return;
        }

        $lat = $location['latitude'] ?? null;
        $lng = $location['longitude'] ?? null;

        if ($lat === null || $lng === null) {
            return;
        }

        $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
        if (!$usuario) {
            $this->sendMessage($chatId, '❌ No estás registrado.');
            return;
        }

        $solicitud = SolicitudUbicacion::where('chat_id', $chatId)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();

        if (!$solicitud) {
            $this->sendMessage($chatId, '⚠️ No hay una solicitud activa.');
            return;
        }

        $instalacionId = $solicitud->instalacion_id;
        $usuarioId = $usuario->id;

        // Validación de jornada
        if ($solicitud->tipo === 'fin') {
            $tieneInicio = UbicacionUsuario::where('usuario_id', $usuarioId)
                ->where('instalacion_id', $instalacionId)
                ->where('tipo', 'inicio')
                ->exists();

            if (!$tieneInicio) {
                $this->sendMessage($chatId, '⚠️ No has iniciado la jornada. Debes iniciar antes de finalizar.');
                $solicitud->delete();
                return;
            }
        }

        if ($solicitud->tipo === 'inicio') {
            $tieneInicioSinFin = UbicacionUsuario::where('usuario_id', $usuarioId)
                ->where('instalacion_id', $instalacionId)
                ->where('tipo', 'inicio')
                ->whereNotExists(function ($query) use ($usuarioId, $instalacionId) {
                    $query->from('ubicaciones_usuarios')
                        ->whereColumn('ubicaciones_usuarios.usuario_id', $usuarioId)
                        ->where('instalacion_id', $instalacionId)
                        ->where('tipo', 'fin');
                })
                ->exists();

            if ($tieneInicioSinFin) {
                $this->sendMessage($chatId, '⚠️ Ya tienes una jornada iniciada. Finaliza primero antes de iniciar nuevamente.');
                $solicitud->delete();
                return;
            }
        }

        // Guardar ubicación
        UbicacionUsuario::create([
            'usuario_id' => $usuarioId,
            'instalacion_id' => $instalacionId,
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

    /**
     * Envía un mensaje simple
     */
    public function sendMessage(string $chatId, string $text): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}