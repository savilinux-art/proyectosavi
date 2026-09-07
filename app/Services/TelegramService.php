<?php

namespace App\Services;

use App\Models\Instalacion;
use App\Models\SolicitudUbicacion;
use App\Models\UbicacionUsuario;
use App\Models\Usuario;
use App\Events\UbicacionActualizada;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use App\Services\GeocercaService;

class TelegramService
{
    protected Api $telegram;

    public function __construct()
    {
        $token = config('telegram.bot_token');
        if (empty($token)) {
            throw new \Exception('Token de Telegram no configurado. Agrega TELEGRAM_BOT_TOKEN en .env');
        }use App\Services\GeocercaService;
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
     * Maneja la ubicación recibida y emite evento en tiempo real
     */
   public function handleLocation(array $message): void
{
    $chatId = $message['chat']['id'] ?? null;
    $location = $message['location'] ?? null;

    if (empty($chatId) || empty($location)) {
        Log::warning('⚠️ Ubicación incompleta', ['chatId' => $chatId, 'location' => $location]);
        return;
    }

    $lat = $location['latitude'] ?? null;
    $lng = $location['longitude'] ?? null;

    if ($lat === null || $lng === null) {
        Log::warning('⚠️ Coordenadas inválidas', ['lat' => $lat, 'lng' => $lng]);
        return;
    }

    $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
    if (!$usuario) {
        $this->sendMessage($chatId, '❌ No estás registrado.');
        Log::warning('⚠️ Usuario no encontrado', ['chatId' => $chatId]);
        return;
    }

    // 🔍 LOG: Ver todas las solicitudes para este usuario (activas y expiradas)
    $todasSolicitudes = SolicitudUbicacion::where('usuario_id', $usuario->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    Log::info('📋 Solicitudes encontradas para usuario', [
        'usuario_id' => $usuario->id,
        'chat_id' => $chatId,
        'total' => $todasSolicitudes->count(),
        'solicitudes' => $todasSolicitudes->map(function($s) {
            return [
                'id' => $s->id,
                'tipo' => $s->tipo,
                'instalacion_id' => $s->instalacion_id,
                'created_at' => $s->created_at->toDateTimeString(),
                'edad_minutos' => $s->created_at->diffInMinutes(now()),
            ];
        })->toArray()
    ]);

    // Buscar solicitud activa (últimos 30 minutos - aumentado de 10 a 30)
    $solicitud = SolicitudUbicacion::where('usuario_id', $usuario->id)  // Cambié a usuario_id
        ->where('created_at', '>=', now()->subMinutes(30))  // ⬅️ Aumentado a 30 minutos
        ->latest()
        ->first();

    if (!$solicitud) {
        // Verificar si hay solicitudes expiradas
        $expiradas = SolicitudUbicacion::where('usuario_id', $usuario->id)
            ->where('created_at', '<', now()->subMinutes(30))
            ->orderBy('created_at', 'desc')
            ->get();

        Log::warning('⚠️ No hay solicitud activa', [
            'usuario_id' => $usuario->id,
            'chat_id' => $chatId,
            'expiradas_total' => $expiradas->count(),
            'ultima_expirada' => $expiradas->first() ? [
                'id' => $expiradas->first()->id,
                'created_at' => $expiradas->first()->created_at->toDateTimeString(),
                'edad_minutos' => $expiradas->first()->created_at->diffInMinutes(now()),
            ] : null,
        ]);

        // Si hay solicitudes expiradas, limpiarlas automáticamente
        if ($expiradas->isNotEmpty()) {
            $deleted = SolicitudUbicacion::where('usuario_id', $usuario->id)
                ->where('created_at', '<', now()->subMinutes(30))
                ->delete();
            Log::info('🧹 Solicitudes expiradas eliminadas', ['eliminadas' => $deleted]);
        }

        // Mensaje más amigable
        $this->sendMessage($chatId, '⏳ No hay una solicitud activa. Por favor, presiona nuevamente el botón "Iniciar Jornada".');
        return;
    }

    $instalacionId = $solicitud->instalacion_id;
    $usuarioId = $usuario->id;
    $tipo = $solicitud->tipo;

    Log::info('✅ Solicitud activa encontrada', [
        'solicitud_id' => $solicitud->id,
        'tipo' => $tipo,
        'instalacion_id' => $instalacionId,
        'usuario_id' => $usuarioId,
    ]);

    // Validaciones de jornada
    if ($tipo === 'fin') {
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

    if ($tipo === 'inicio') {
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
    try {
        $ubicacion = UbicacionUsuario::create([
            'usuario_id' => $usuarioId,
            'instalacion_id' => $instalacionId,
            'latitud' => $lat,
            'longitud' => $lng,
            'fecha_hora' => now(),
            'fuente' => 'telegram',
            'tipo' => $tipo,
            'detalles' => json_encode($message ?? []),
        ]);

        Log::info('✅ Ubicación guardada', [
            'ubicacion_id' => $ubicacion->id,
            'usuario' => $usuario->nombre,
            'lat' => $lat,
            'lng' => $lng,
            'tipo' => $tipo,
        ]);

        // 🔍 Procesar geocercas
try {
    $geocercaService = app(GeocercaService::class);
    $geocercaService->procesarUbicacion($ubicacion);
} catch (\Exception $e) {
    Log::error('❌ Error al procesar geocercas: ' . $e->getMessage());
}

        // 🚀 EMITIR EVENTO EN TIEMPO REAL
        try {
            broadcast(new UbicacionActualizada($ubicacion))->toOthers();
            Log::info('📡 Evento UbicacionActualizada emitido desde Telegram', [
                'ubicacion_id' => $ubicacion->id,
                'usuario_id' => $usuario->id,
                'lat' => $lat,
                'lng' => $lng,
                'tipo' => $tipo,
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error al emitir evento UbicacionActualizada', [
                'error' => $e->getMessage(),
                'ubicacion_id' => $ubicacion->id,
            ]);
        }

        // Enviar confirmación según tipo
        if ($tipo === 'inicio') {
            $this->sendMessage($chatId, '✅ Ubicación de inicio guardada. ¡Buen trabajo!');
            $this->sendFinButton($chatId, $instalacionId);
        } else {
            $this->sendMessage($chatId, '✅ Ubicación de fin guardada. ¡Hasta luego!');
        }

        $solicitud->delete();
        Log::info('🗑️ Solicitud de ubicación eliminada', ['solicitud_id' => $solicitud->id]);

    } catch (\Exception $e) {
        Log::error('❌ Error al guardar ubicación', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'usuario_id' => $usuarioId,
            'chat_id' => $chatId,
        ]);
        $this->sendMessage($chatId, '❌ Error al guardar la ubicación. Intenta de nuevo.');
    }



            // 🚀 EMITIR EVENTO EN TIEMPO REAL (WebSocket)
            try {
                broadcast(new UbicacionActualizada($ubicacion))->toOthers();
                Log::info('📡 Evento UbicacionActualizada emitido desde Telegram', [
                    'ubicacion_id' => $ubicacion->id,
                    'usuario_id' => $usuario->id,
                    'lat' => $lat,
                    'lng' => $lng,
                    'tipo' => $tipo,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Error al emitir evento UbicacionActualizada', [
                    'error' => $e->getMessage(),
                    'ubicacion_id' => $ubicacion->id,
                ]);
            }

            // Enviar confirmación según tipo
            if ($tipo === 'inicio') {
                $this->sendMessage($chatId, '✅ Ubicación de inicio guardada. ¡Buen trabajo!');
                $this->sendFinButton($chatId, $instalacionId);
            } else {
                $this->sendMessage($chatId, '✅ Ubicación de fin guardada. ¡Hasta luego!');
            }

            $solicitud->delete();
            Log::info('🗑️ Solicitud de ubicación eliminada', ['solicitud_id' => $solicitud->id]);

        } catch (\Exception $e) {
            Log::error('❌ Error al guardar ubicación', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => $usuarioId,
                'chat_id' => $chatId,
            ]);
            $this->sendMessage($chatId, '❌ Error al guardar la ubicación. Intenta de nuevo.');
        }
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
        try {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
            ]);
            Log::info('📤 Mensaje enviado', ['chat_id' => $chatId, 'text' => $text]);
        } catch (\Exception $e) {
            Log::error('❌ Error enviando mensaje', ['chat_id' => $chatId, 'error' => $e->getMessage()]);
        }
    }
}