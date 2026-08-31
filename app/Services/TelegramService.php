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
        Log::info('📨 NOTIFY - INICIO', [
            'instalador_id' => $instalador->id,
            'chat_id' => $instalador->telegram_chat_id,
            'instalacion_id' => $instalacion->id,
        ]);

        if (empty($instalador->telegram_chat_id)) {
            Log::warning('⚠️ Chat ID vacío', ['instalador_id' => $instalador->id]);
            return;
        }

        // ✅ Variable definida correctamente
        $nombreInstalacion = $instalacion->nombre_instalacion ?? 'Principal';

        $texto = "🔔 *Nueva instalación asignada*\n\n" .
            "📋 Instalación #{$instalacion->id}\n" .
            "📌 Proyecto: {$instalacion->nombre_proyecto}\n" .
            "🔧 Instalación: {$nombreInstalacion}\n" .
            "📍 Dirección: {$instalacion->ubicacion_actual}\n\n" .
            'Presiona el botón para iniciar la jornada y reportar tu ubicación:';

        $inlineKeyboard = Keyboard::make()
            ->inline()
            ->row([
                Keyboard::inlineButton([
                    'text' => '▶️ Iniciar Jornada',
                    'callback_data' => "inicio|{$instalacion->id}",
                ]),
            ]);

        try {
            $this->telegram->sendMessage([
                'chat_id' => $instalador->telegram_chat_id,
                'text' => $texto,
                'reply_markup' => $inlineKeyboard,
                'parse_mode' => 'Markdown',
            ]);
            Log::info('✅ Mensaje enviado correctamente', ['chat_id' => $instalador->telegram_chat_id]);
        } catch (\Exception $e) {
            Log::error('❌ Error al enviar mensaje de Telegram', [
                'chat_id' => $instalador->telegram_chat_id,
                'error' => $e->getMessage(),
            ]);
        }
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

        [$tipo, $instalacionId] = array_pad(explode('|', $callbackData, 2), 2, null);

        if (!in_array($tipo, ['inicio', 'fin']) || !is_numeric($instalacionId)) {
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

        // Validaciones de jornada
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
                        ->where('ubicaciones_usuarios.usuario_id', $usuario->id)
                        ->where('instalacion_id', $instalacionId)
                        ->where('tipo', 'fin');
                })
                ->exists();

            if ($inicioSinFin) {
                $this->sendMessage($chatId, '⚠️ Ya tienes una jornada iniciada. Finaliza primero antes de iniciar nuevamente.');
                return;
            }
        }

        // Crear la solicitud de ubicación
        SolicitudUbicacion::create([
            'usuario_id' => $usuario->id,
            'chat_id' => $chatId,
            'tipo' => $tipo,
            'instalacion_id' => $instalacionId,
        ]);

        // Si es inicio, eliminar mensaje original
        if ($tipo === 'inicio' && $messageId) {
            try {
                $this->telegram->deleteMessage([
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                ]);
                Log::info('🗑️ Mensaje de inicio eliminado', ['chat_id' => $chatId, 'message_id' => $messageId]);
            } catch (\Exception $e) {
                Log::warning('No se pudo eliminar el mensaje de inicio', ['chat_id' => $chatId, 'message_id' => $messageId]);
            }
        }

        // Enviar mensaje con botón de ubicación
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

        // Validaciones de jornada
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
                        ->where('ubicaciones_usuarios.usuario_id', $usuarioId)
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

        // Enviar confirmación según tipo
        if ($solicitud->tipo === 'inicio') {
            $this->sendMessage($chatId, '✅ Ubicación de inicio guardada. ¡Buen trabajo!');
            $this->sendFinButton($chatId, $instalacionId);
        } else {
            $this->sendMessage($chatId, '✅ Ubicación de fin guardada. ¡Hasta luego!');
        }

        $solicitud->delete();
    }

    /**
     * Envía un mensaje con el botón "Finalizar Jornada"
     */
    private function sendFinButton(string $chatId, int $instalacionId): void
    {
        $texto = "✅ Has iniciado la jornada.\n\n" .
            "📋 Instalación #{$instalacionId}\n\n" .
            "Cuando termines, presiona el botón para finalizar:";

        $inlineKeyboard = Keyboard::make()
            ->inline()
            ->row([
                Keyboard::inlineButton([
                    'text' => '⏹️ Finalizar Jornada',
                    'callback_data' => "fin|{$instalacionId}",
                ]),
            ]);

        try {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $texto,
                'reply_markup' => $inlineKeyboard,
                'parse_mode' => 'Markdown',
            ]);
            Log::info('📤 Botón de FIN enviado', ['chat_id' => $chatId, 'instalacion_id' => $instalacionId]);
        } catch (\Exception $e) {
            Log::error('❌ Error enviando botón de FIN', ['chat_id' => $chatId, 'error' => $e->getMessage()]);
        }
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