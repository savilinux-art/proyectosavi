<?php

namespace App\Services;

use App\Models\Instalacion;
use App\Models\InstalacionFoto;
use App\Models\SolicitudUbicacion;
use App\Models\UbicacionUsuario;
use App\Models\Usuario;
use App\Events\UbicacionActualizada;
use Illuminate\Support\Facades\Cache;
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
     *  NOTIFICAR INSTALACIÓN ASIGNADA
     * ============================================================ */
    public function notifyInstalacionAsignada(Usuario $instalador, Instalacion $instalacion): void
    {
        if (empty($instalador->telegram_chat_id)) {
            Log::warning('⚠️ Chat ID vacío', ['instalador_id' => $instalador->id]);
            return;
        }

        $nombreInstalacion = $instalacion->nombre_instalacion ?? 'Principal';

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
            Log::info('✅ Notificación enviada', ['chat_id' => $instalador->telegram_chat_id]);
        } catch (\Exception $e) {
            Log::error('❌ Error notificando asignación', [
                'chat_id' => $instalador->telegram_chat_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /* ============================================================
     *  CALLBACK QUERY (botones inline)
     * ============================================================ */
    public function handleCallbackQuery($callbackQuery): void
    {
        $chatId       = $callbackQuery['message']['chat']['id'] ?? null;
        $callbackData = $callbackQuery['data'] ?? null;
        $messageId    = $callbackQuery['message']['message_id'] ?? null;

        if (empty($chatId) || empty($callbackData)) return;

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
                ->whereNotExists(function ($q) use ($usuario, $instalacionId) {
                    $q->from('ubicaciones_usuarios')
                      ->where('ubicaciones_usuarios.usuario_id', $usuario->id)
                      ->where('instalacion_id', $instalacionId)
                      ->where('tipo', 'fin');
                })
                ->exists();

            if ($inicioSinFin) {
                $this->sendMessage($chatId, '⚠️ Ya tienes una jornada iniciada. Finaliza primero.');
                return;
            }
        }

        // Limpiar solicitudes viejas del usuario
        SolicitudUbicacion::where('usuario_id', $usuario->id)->delete();

        SolicitudUbicacion::create([
            'usuario_id'     => $usuario->id,
            'chat_id'        => $chatId,
            'tipo'           => $tipo,
            'instalacion_id' => $instalacionId,
        ]);

        if ($tipo === 'inicio' && $messageId) {
            try {
                $this->telegram->deleteMessage([
                    'chat_id'    => $chatId,
                    'message_id' => $messageId,
                ]);
            } catch (\Exception $e) {
                Log::warning('No se pudo eliminar mensaje', ['chat_id' => $chatId]);
            }
        }

        $this->sendLocationRequest($chatId, $tipo);
    }

    /* ============================================================
     *  SOLICITAR UBICACIÓN
     * ============================================================ */
    public function sendLocationRequest(string $chatId, string $tipo): void
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

        try {
            $this->telegram->sendMessage([
                'chat_id'      => $chatId,
                'text'         => $texto,
                'reply_markup' => $keyboard,
                'parse_mode'   => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Error enviando solicitud de ubicación', ['error' => $e->getMessage()]);
        }
    }

    /* ============================================================
     *  HANDLE LOCATION (compartir ubicación)
     * ============================================================ */
    public function handleLocation(array $message): void
    {
        $chatId   = $message['chat']['id'] ?? null;
        $location = $message['location'] ?? null;

        if (empty($chatId) || empty($location)) {
            Log::warning('⚠️ Ubicación incompleta', ['chatId' => $chatId]);
            return;
        }

        $lat = $location['latitude'] ?? null;
        $lng = $location['longitude'] ?? null;

        if ($lat === null || $lng === null) {
            Log::warning('⚠️ Coordenadas inválidas');
            return;
        }

        $usuario = Usuario::where('telegram_chat_id', $chatId)->first();
        if (!$usuario) {
            $this->sendMessage($chatId, '❌ No estás registrado.');
            return;
        }

        $solicitud = SolicitudUbicacion::where('usuario_id', $usuario->id)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest()
            ->first();

        if (!$solicitud) {
            Log::warning('⚠️ No hay solicitud activa', ['usuario_id' => $usuario->id]);

            // Anti-spam: solo enviar 1 vez cada 5 minutos
            $cacheKey = "tg:no-solicitud:{$usuario->id}";
            if (!Cache::has($cacheKey)) {
                $this->sendMessage(
                    $chatId,
                    '⏳ No hay una solicitud activa. Presiona nuevamente el botón "Iniciar Jornada".'
                );
                Cache::put($cacheKey, true, now()->addMinutes(5));
            }
            return;
        }

        $instalacionId = $solicitud->instalacion_id;
        $usuarioId     = $usuario->id;
        $tipo          = $solicitud->tipo;

        // Validaciones de jornada
        if ($tipo === 'fin') {
            $tieneInicio = UbicacionUsuario::where('usuario_id', $usuarioId)
                ->where('instalacion_id', $instalacionId)
                ->where('tipo', 'inicio')
                ->exists();

            if (!$tieneInicio) {
                $this->sendMessage($chatId, '⚠️ No has iniciado la jornada.');
                $solicitud->delete();
                return;
            }
        }

        if ($tipo === 'inicio') {
            $tieneInicioSinFin = UbicacionUsuario::where('usuario_id', $usuarioId)
                ->where('instalacion_id', $instalacionId)
                ->where('tipo', 'inicio')
                ->whereNotExists(function ($q) use ($usuarioId, $instalacionId) {
                    $q->from('ubicaciones_usuarios')
                      ->where('ubicaciones_usuarios.usuario_id', $usuarioId)
                      ->where('instalacion_id', $instalacionId)
                      ->where('tipo', 'fin');
                })
                ->exists();

            if ($tieneInicioSinFin) {
                $this->sendMessage($chatId, '⚠️ Ya tienes una jornada iniciada.');
                $solicitud->delete();
                return;
            }
        }

        try {
            $ubicacion = UbicacionUsuario::create([
                'usuario_id'     => $usuarioId,
                'instalacion_id' => $instalacionId,
                'latitud'        => $lat,
                'longitud'       => $lng,
                'fecha_hora'     => now(),
                'fuente'         => 'telegram',
                'tipo'           => $tipo,
                'detalles'       => json_encode($message ?? []),
            ]);

            Log::info('✅ Ubicación guardada', [
                'ubicacion_id' => $ubicacion->id,
                'tipo'         => $tipo,
            ]);

            // Sincronizar coordenadas en instalación (solo inicio)
            if ($tipo === 'inicio') {
                try {
                    $instalacion = Instalacion::find($instalacionId);
                    if ($instalacion) {
                        $instalacion->update([
                            'latitud'                  => $lat,
                            'longitud'                 => $lng,
                            'ubicacion_actualizada_en' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('No se pudo sincronizar coords: ' . $e->getMessage());
                }
            }

            // Geocercas
            try {
                app(\App\Services\GeocercaService::class)->procesarUbicacion($ubicacion);
            } catch (\Exception $e) {
                Log::error('Error geocercas: ' . $e->getMessage());
            }

            // Evento tiempo real
            try {
                broadcast(new UbicacionActualizada($ubicacion))->toOthers();
            } catch (\Exception $e) {
                Log::error('Error broadcast: ' . $e->getMessage());
            }

            // Borrar solicitud ANTES de enviar mensajes finales
            $solicitud->delete();

            if ($tipo === 'inicio') {
                $this->sendMessage($chatId, '✅ Ubicación de inicio guardada. ¡Buen trabajo!');
                $this->sendFinButton($chatId, $instalacionId);
            } else {
                $this->sendMessage($chatId, '✅ Ubicación de fin guardada. ¡Hasta luego!');
                $this->sendFotoRequest($chatId, $instalacionId, 'fin');
            }

        } catch (\Exception $e) {
            Log::error('❌ Error al guardar ubicación', [
                'error'      => $e->getMessage(),
                'usuario_id' => $usuarioId,
            ]);
            $this->sendMessage($chatId, '❌ Error al guardar la ubicación.');
        }
    }

    /* ============================================================
     *  BOTÓN FINALIZAR
     * ============================================================ */
    public function sendFinButton(string $chatId, int $instalacionId): void
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
        } catch (\Exception $e) {
            Log::error('Error enviando botón FIN', ['error' => $e->getMessage()]);
        }
    }

    /* ============================================================
     *  SEND MESSAGE (con anti-spam)
     * ============================================================ */
    public function sendMessage(string $chatId, string $text): void
    {
        // Anti-spam global
        $hash     = md5($chatId . '|' . $text);
        $cacheKey = "tg:msg:{$hash}";

        if (Cache::has($cacheKey)) {
            Log::info('🔇 Mensaje duplicado silenciado', ['chat_id' => $chatId]);
            return;
        }
        Cache::put($cacheKey, true, now()->addSeconds(30));

        try {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text'    => $text,
            ]);
            Log::info('📤 Mensaje enviado', ['chat_id' => $chatId]);
        } catch (\Exception $e) {
            Log::error('❌ Error enviando mensaje', [
                'chat_id' => $chatId,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /* ============================================================
     *  CAMBIO DE ESTATUS
     * ============================================================ */
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

        $keyboard = $this->buildEstatusKeyboard($instalacion, $nuevo);

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
                Log::error('Error notificando a instalador', ['error' => $e->getMessage()]);
            }
        }

        $adminChat = config('telegram.admin_chat_id');
        if (!empty($adminChat)) {
            try {
                $this->telegram->sendMessage([
                    'chat_id'    => $adminChat,
                    'text'       => $texto,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (\Exception $e) {
                Log::error('Error notificando a admin', ['error' => $e->getMessage()]);
            }
        }
    }

    /* ============================================================
     *  NOTIFICAR FOTO SUBIDA
     * ============================================================ */
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
            if ($foto && $foto->ruta) {
                $rutaCompleta = storage_path('app/public/' . $foto->ruta);
                if (file_exists($rutaCompleta)) {
                    $this->telegram->sendPhoto([
                        'chat_id' => $adminChat,
                        'photo'   => \Telegram\Bot\FileUpload\InputFile::create($rutaCompleta),
                        'caption' => $texto,
                        'parse_mode' => 'Markdown',
                    ]);
                    return;
                }
            }

            $this->telegram->sendMessage([
                'chat_id' => $adminChat,
                'text' => $texto,
                'parse_mode' => 'Markdown',
            ]);

        } catch (\Exception $e) {
            Log::error('Error notificando foto', ['error' => $e->getMessage()]);
        }
    }

    /* ============================================================
     *  GEOCERCAS (SOLO ADMIN)
     * ============================================================ */
    public function notifyGeocercaAlerta(
        Usuario $usuario,
        string $nombreGeocerca,
        string $tipo,
        ?float $lat = null,
        ?float $lng = null
    ): void {
        $emoji  = $tipo === 'entrada' ? '🟢' : '🔴';
        $accion = $tipo === 'entrada' ? 'entró a' : 'salió de';

        $texto = "{$emoji} *Alerta de geocerca*\n\n" .
            "👷 {$usuario->nombre}\n" .
            "{$accion}: *{$nombreGeocerca}*\n" .
            "🕒 " . now()->format('d/m/Y H:i');

        if ($lat !== null && $lng !== null) {
            $texto .= "\n📍 {$lat}, {$lng}";
        }

        // SOLO admin
        $adminChat = config('telegram.admin_chat_id');
        if (empty($adminChat)) return;

        try {
            $this->telegram->sendMessage([
                'chat_id'    => $adminChat,
                'text'       => $texto,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Error notificando geocerca', ['error' => $e->getMessage()]);
        }
    }

    /* ============================================================
     *  SOLICITAR FOTOS
     * ============================================================ */
    public function sendFotoRequest(string $chatId, int $instalacionId, string $tipo = 'fin'): void
    {
        $texto = match ($tipo) {
            'fin'        => "📷 *Sube tus fotos de cierre*\n\nEnvía las fotos del trabajo terminado.",
            'incidencia' => "⚠️ *Reporta una incidencia*\n\nEnvía fotos de la incidencia.",
            default      => "📷 *Sube tus fotos*\n\nEnvía las fotos de la instalación.",
        };

        $texto .= "\n\n📋 Instalación #{$instalacionId}";

        try {
            $this->telegram->sendMessage([
                'chat_id'    => $chatId,
                'text'       => $texto,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Error enviando solicitud de fotos', ['error' => $e->getMessage()]);
        }
    }

    /* ============================================================
     *  RESUMEN DIARIO
     * ============================================================ */
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
                'chat_id'    => $adminChat,
                'text'       => $texto,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Error enviando resumen diario', ['error' => $e->getMessage()]);
        }
    }

    /* ============================================================
     *  KEYBOARD CONTEXTUAL SEGÚN ESTATUS
     * ============================================================ */
    private function buildEstatusKeyboard(Instalacion $instalacion, string $nuevo): ?Keyboard
    {
        $keyboard = Keyboard::make()->inline();
        $agregado = false;

        if ($nuevo === 'completada') {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '📷 Subir fotos de fin',
                    'callback_data' => "foto|fin|{$instalacion->id}",
                ]),
            ]);
            $agregado = true;
        }

        if ($nuevo === 'pruebas') {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '📷 Subir evidencia de pruebas',
                    'callback_data' => "foto|proceso|{$instalacion->id}",
                ]),
            ]);
            $agregado = true;
        }

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