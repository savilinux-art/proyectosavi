<?php

namespace App\Services;

use App\Models\Instalacion;
use App\Models\InstalacionFoto;
use App\Models\SolicitudUbicacion;
use App\Models\UbicacionUsuario;
use App\Models\Usuario;
use App\Events\UbicacionActualizada;
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

    /* ============================================================
     *  NOTIFICACIONES EXISTENTES (sin cambios)
     * ============================================================ */

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

        // NUEVO: usar latitud/longitud o direccion si existen, con fallback a ubicacion_actual
        $ubicacionTexto = $instalacion->direccion
            ?? ($instalacion->tieneUbicacion()
                ? "{$instalacion->latitud}, {$instalacion->longitud}"
                : ($instalacion->ubicacion_actual ?? 'No especificada'));

        $texto = "🔔 *Nueva instalación asignada*\n\n" .
            "📋 Instalación #{$instalacion->id}\n" .
            "📌 Proyecto: {$instalacion->nombre_proyecto}\n" .
            "🔧 Instalación: {$nombreInstalacion}\n" .
            "📍 Dirección: {$ubicacionTexto}\n\n" .
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

        SolicitudUbicacion::create([
            'usuario_id' => $usuario->id,
            'chat_id' => $chatId,
            'tipo' => $tipo,
            'instalacion_id' => $instalacionId,
        ]);

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

        $this->sendLocationRequest($chatId, $tipo);
    }

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

        $solicitud = SolicitudUbicacion::where('usuario_id', $usuario->id)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest()
            ->first();

        if (!$solicitud) {
            Log::warning('⚠️ No hay solicitud activa', [
                'usuario_id' => $usuario->id,
                'chat_id' => $chatId,
            ]);
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

            // NUEVO: sincronizar lat/lng en la instalación si es tipo inicio
            if ($tipo === 'inicio') {
                try {
                    $instalacion = Instalacion::find($instalacionId);
                    if ($instalacion) {
                        $instalacion->update([
                            'latitud' => $lat,
                            'longitud' => $lng,
                            'ubicacion_actualizada_en' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('No se pudo sincronizar coordenadas en instalación: ' . $e->getMessage());
                }
            }

            try {
                $geocercaService = app(\App\Services\GeocercaService::class);
                $geocercaService->procesarUbicacion($ubicacion);
            } catch (\Exception $e) {
                Log::error('❌ Error al procesar geocercas: ' . $e->getMessage());
            }

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

            if ($tipo === 'inicio') {
                $this->sendMessage($chatId, '✅ Ubicación de inicio guardada. ¡Buen trabajo!');
                $this->sendFinButton($chatId, $instalacionId);
            } else {
                $this->sendMessage($chatId, '✅ Ubicación de fin guardada. ¡Hasta luego!');

                // NUEVO: si al finalizar, sugerir subir fotos de fin
                $this->sendFotoRequest($chatId, $instalacionId, 'fin');
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

    /* ============================================================
     *  NUEVOS MÉTODOS (agregados sin romper lo existente)
     * ============================================================ */

    /**
     * Notifica un cambio de estatus de instalación (Opción C).
     * - Notifica a instaladores asignados
     * - Notifica al admin
     * - Envía botones contextuales según el nuevo estatus
     */
    public function notifyCambioEstatus(Instalacion $instalacion, string $anterior, string $nuevo): void
    {
        $emoji = match ($nuevo) {
            'programacion' => '📅',
            'preparacion'  => '🛠',
            'asignada'     => '👷',
            'en_proceso'   => '🚧',
            'pruebas'      => '🧪',
            'entrega'      => '📦',
            'completada'   => '✅',
            'cancelada'    => '❌',
            'pendiente'    => '⏳',
            default        => '📌',
        };

        $texto = "{$emoji} *Cambio de estatus*\n\n" .
            "📋 Instalación #{$instalacion->id}\n" .
            "📌 Proyecto: {$instalacion->nombre_proyecto}\n" .
            "🔧 {$instalacion->nombre_instalacion}\n\n" .
            "De: `{$anterior}`\n" .
            "A: `{$nuevo}`";

        // Keyboard contextual según nuevo estatus
        $keyboard = $this->buildEstatusKeyboard($instalacion, $nuevo);

        // Notificar a instaladores asignados
        foreach ($instalacion->instaladores as $inst) {
            if (empty($inst->telegram_chat_id)) continue;

            try {
                $payload = [
                    'chat_id' => $inst->telegram_chat_id,
                    'text' => $texto,
                    'parse_mode' => 'Markdown',
                ];
                if ($keyboard) {
                    $payload['reply_markup'] = $keyboard;
                }
                $this->telegram->sendMessage($payload);
            } catch (\Exception $e) {
                Log::error('❌ Error notificando cambio estatus a instalador', [
                    'instalador' => $inst->usuario,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Notificar al admin
        $adminChat = config('telegram.admin_chat_id');
        if (!empty($adminChat)) {
            try {
                $this->telegram->sendMessage([
                    'chat_id' => $adminChat,
                    'text' => $texto,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Error notificando cambio estatus a admin', ['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Notifica que se subió una foto a una instalación.
     * Envía la foto real al admin si es posible.
     */
    public function notifyFotoSubida(Instalacion $instalacion, string $tipo, ?InstalacionFoto $foto = null): void
    {
        $emojiTipo = match ($tipo) {
            'inicio'     => '🟢',
            'proceso'    => '🔵',
            'fin'        => '🔴',
            'incidencia' => '⚠️',
            default      => '📷',
        };

        $texto = "{$emojiTipo} *Nueva evidencia* ({$tipo})\n\n" .
            "📋 Instalación #{$instalacion->id}\n" .
            "📌 Proyecto: {$instalacion->nombre_proyecto}\n" .
            "🔧 {$instalacion->nombre_instalacion}";

        $adminChat = config('telegram.admin_chat_id');
        if (empty($adminChat)) return;

        try {
            // Si tenemos el archivo físico, enviar como foto
            if ($foto && $foto->ruta) {
                $rutaCompleta = storage_path('app/public/' . $foto->ruta);

                if (file_exists($rutaCompleta)) {
                    $this->telegram->sendPhoto([
                        'chat_id' => $adminChat,
                        'photo'   => \Telegram\Bot\FileUpload\InputFile::create($rutaCompleta),
                        'caption' => $texto,
                        'parse_mode' => 'Markdown',
                    ]);
                    Log::info('📸 Foto enviada al admin', [
                        'instalacion_id' => $instalacion->id,
                        'ruta' => $foto->ruta,
                    ]);
                    return;
                }
            }

            // Fallback: solo texto
            $this->telegram->sendMessage([
                'chat_id' => $adminChat,
                'text' => $texto,
                'parse_mode' => 'Markdown',
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error notificando foto al admin', [
                'error' => $e->getMessage(),
                'instalacion_id' => $instalacion->id,
            ]);
        }
    }

    /**
     * Notifica alerta de geocerca (entrada/salida).
     * Se llama desde GeocercaService.
     */
    public function notifyGeocercaAlerta(
        Usuario $usuario,
        string $nombreGeocerca,
        string $tipo,       // 'entrada' | 'salida'
        ?float $lat = null,
        ?float $lng = null
    ): void {
        $emoji = $tipo === 'entrada' ? '🟢' : '🔴';
        $accion = $tipo === 'entrada' ? 'entró a' : 'salió de';

        $texto = "{$emoji} *Alerta de geocerca*\n\n" .
            "👷 {$usuario->nombre}\n" .
            "{$accion}: *{$nombreGeocerca}*\n" .
            "🕒 " . now()->format('d/m/Y H:i');

        if ($lat !== null && $lng !== null) {
            $texto .= "\n📍 {$lat}, {$lng}";
        }

        // Notificar al admin
        $adminChat = config('telegram.admin_chat_id');
        if (!empty($adminChat)) {
            try {
                $this->telegram->sendMessage([
                    'chat_id' => $adminChat,
                    'text' => $texto,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Error notificando geocerca al admin', ['error' => $e->getMessage()]);
            }
        }

        // Notificar al instalador mismo (opcional, útil)
        if (!empty($usuario->telegram_chat_id)) {
            try {
                $this->telegram->sendMessage([
                    'chat_id' => $usuario->telegram_chat_id,
                    'text' => $texto,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Error notificando geocerca al instalador', ['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Envía un mensaje al instalador pidiéndole fotos de evidencia.
     * Se llama automáticamente al finalizar jornada, o manualmente desde el panel.
     */
    public function sendFotoRequest(string $chatId, int $instalacionId, string $tipo = 'fin'): void
    {
        $texto = match ($tipo) {
            'fin'        => "📷 *Sube tus fotos de cierre*\n\nEnvía las fotos del trabajo terminado como respuesta a este mensaje.",
            'incidencia' => "⚠️ *Reporta una incidencia*\n\nEnvía fotos de la incidencia que encontraste.",
            default      => "📷 *Sube tus fotos*\n\nEnvía las fotos de la instalación.",
        };

        $texto .= "\n\n📋 Instalación #{$instalacionId}";

        try {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $texto,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error enviando solicitud de fotos', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envía un mensaje al admin con resumen de instalaciones activas.
     * Útil para reportes diarios.
     */
    public function notifyResumenDiario(array $stats): void
    {
        $adminChat = config('telegram.admin_chat_id');
        if (empty($adminChat)) return;

        $texto = "📊 *Resumen del día*\n\n" .
            "🟢 Activas: " . ($stats['activas'] ?? 0) . "\n" .
            "✅ Completadas: " . ($stats['completadas'] ?? 0) . "\n" .
            "❌ Canceladas: " . ($stats['canceladas'] ?? 0) . "\n" .
            "👷 Instaladores activos: " . ($stats['instaladores'] ?? 0) . "\n" .
            "🕒 " . now()->format('d/m/Y');

        try {
            $this->telegram->sendMessage([
                'chat_id' => $adminChat,
                'text' => $texto,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error enviando resumen diario', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Construye el teclado inline contextual según el nuevo estatus.
     */
    private function buildEstatusKeyboard(Instalacion $instalacion, string $nuevo): ?Keyboard
    {
        $keyboard = Keyboard::make()->inline();
        $agregado = false;

        // Si pasa a "completada" → pedir fotos de fin
        if ($nuevo === 'completada') {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '📷 Subir fotos de fin',
                    'callback_data' => "foto|fin|{$instalacion->id}",
                ]),
            ]);
            $agregado = true;
        }

        // Si pasa a "pruebas" → pedir fotos de proceso
        if ($nuevo === 'pruebas') {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '📷 Subir evidencia de pruebas',
                    'callback_data' => "foto|proceso|{$instalacion->id}",
                ]),
            ]);
            $agregado = true;
        }

        // Si pasa a "asignada" o "pendiente" → botón de iniciar jornada
        if (in_array($nuevo, ['asignada', 'pendiente', 'programacion', 'preparacion'])) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '▶️ Iniciar Jornada',
                    'callback_data' => "inicio|{$instalacion->id}",
                ]),
            ]);
            $agregado = true;
        }

        return $agregado ? $keyboard : null;
    }
}