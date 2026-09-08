<?php

namespace App\Services;

use App\Models\Geocerca;
use App\Models\GeocercaEstado;
use App\Models\GeocercaAlerta;
use App\Models\UbicacionUsuario;
use App\Events\GeocercaAlertaEvent;
use Illuminate\Support\Facades\Log;

class GeocercaService
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Procesa una ubicación y verifica todas las geocercas activas
     */
    public function procesarUbicacion(UbicacionUsuario $ubicacion): void
    {
        $usuario = $ubicacion->usuario;
        if (!$usuario) {
            Log::warning('⚠️ Ubicación sin usuario asociado', ['ubicacion_id' => $ubicacion->id]);
            return;
        }

        $geocercas = Geocerca::where('activa', true)->get();
        if ($geocercas->isEmpty()) {
            return;
        }

        foreach ($geocercas as $geocerca) {
            $distancia = $this->calcularDistancia(
                $ubicacion->latitud,
                $ubicacion->longitud,
                $geocerca->latitud,
                $geocerca->longitud
            );

            $estaDentro = $distancia <= $geocerca->radio;

            // Buscar o crear estado actual
            $estado = GeocercaEstado::firstOrNew([
                'geocerca_id' => $geocerca->id,
                'usuario_id' => $usuario->id,
            ]);

            $estadoAnterior = $estado->estado ?? 'fuera';

            // Si cambió el estado
            if ($estaDentro && $estadoAnterior === 'fuera') {
                $this->crearAlerta($geocerca, $usuario, $ubicacion, 'entrada');
                $estado->estado = 'dentro';
                Log::info("📍 {$usuario->nombre} ENTRÓ a {$geocerca->nombre}");
            } elseif (!$estaDentro && $estadoAnterior === 'dentro') {
                $this->crearAlerta($geocerca, $usuario, $ubicacion, 'salida');
                $estado->estado = 'fuera';
                Log::info("📍 {$usuario->nombre} Salió de {$geocerca->nombre}");
            }

            // Actualizar última ubicación conocida
            $estado->ultima_latitud = $ubicacion->latitud;
            $estado->ultima_longitud = $ubicacion->longitud;
            $estado->ultima_actualizacion = now();
            $estado->save();
        }
    }

    /**
     * Calcula la distancia entre dos coordenadas (fórmula de Haversine)
     */
    public function calcularDistancia($lat1, $lng1, $lat2, $lng2): float
    {
        $tierraRadio = 6371000; // metros
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $tierraRadio * $c;
    }

    /**
     * Crea una alerta de geocerca y notifica
     */
    protected function crearAlerta($geocerca, $usuario, $ubicacion, $tipo)
    {
        // 1. Guardar alerta
        $alerta = GeocercaAlerta::create([
            'geocerca_id' => $geocerca->id,
            'usuario_id' => $usuario->id,
            'tipo' => $tipo,
            'latitud' => $ubicacion->latitud,
            'longitud' => $ubicacion->longitud,
            'fecha_hora' => now(),
            'notificado' => false,
        ]);

        // 2. Notificar por Telegram
        $this->notificarAlerta($alerta);

        // 3. Emitir evento WebSocket
        try {
            broadcast(new GeocercaAlertaEvent($alerta))->toOthers();
            Log::info('📡 Evento GeocercaAlerta emitido', ['alerta_id' => $alerta->id]);
        } catch (\Exception $e) {
            Log::error('❌ Error al emitir evento GeocercaAlerta: ' . $e->getMessage());
        }

        // 4. Marcar como notificado
        $alerta->notificado = true;
        $alerta->save();
    }

    /**
     * Envía notificaciones por Telegram
     */
    protected function notificarAlerta($alerta)
    {
        $geocerca = $alerta->geocerca;
        $usuario = $alerta->usuario;
        $tipo = $alerta->tipo === 'entrada' ? '🟢 ENTRÓ a' : '🔴 Salió de';

        $mensaje = "📍 Alerta de Geocerca\n\n" .
                   "{$tipo} la zona: *{$geocerca->nombre}*\n" .
                   "👤 Instalador: {$usuario->nombre}\n" .
                   "📅 Fecha: {$alerta->fecha_hora->format('d/m/Y H:i')}\n" .
                   "📍 Ubicación: {$alerta->latitud}, {$alerta->longitud}";

        // Enviar al instalador
        if ($usuario->telegram_chat_id) {
            $this->telegram->sendMessage($usuario->telegram_chat_id, $mensaje);
        }

        // Enviar al grupo de administradores (si está configurado)
        $adminGroup = env('TELEGRAM_GROUP_CHAT_ID');
        if ($adminGroup) {
            $this->telegram->sendMessage($adminGroup, $mensaje);
        }
    }
}