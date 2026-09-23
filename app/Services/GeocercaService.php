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

    public function procesarUbicacion(UbicacionUsuario $ubicacion): void
    {
        $usuario = $ubicacion->usuario;
        if (!$usuario) {
            Log::warning('⚠️ Ubicación sin usuario asociado', ['ubicacion_id' => $ubicacion->id]);
            return;
        }

        $geocercas = Geocerca::where('activa', true)->get();
        if ($geocercas->isEmpty()) return;

        foreach ($geocercas as $geocerca) {
            $distancia = $this->calcularDistancia(
                $ubicacion->latitud, $ubicacion->longitud,
                $geocerca->latitud, $geocerca->longitud
            );

            $estaDentro = $distancia <= $geocerca->radio;

            $estado = GeocercaEstado::firstOrNew([
                'geocerca_id' => $geocerca->id,
                'usuario_id'  => $usuario->id,
            ]);

            $estadoAnterior = $estado->estado ?? 'fuera';

            if ($estaDentro && $estadoAnterior === 'fuera') {
                $this->crearAlerta($geocerca, $usuario, $ubicacion, 'entrada');
                $estado->estado = 'dentro';
                Log::info("📍 {$usuario->nombre} ENTRÓ a {$geocerca->nombre}");
            } elseif (!$estaDentro && $estadoAnterior === 'dentro') {
                $this->crearAlerta($geocerca, $usuario, $ubicacion, 'salida');
                $estado->estado = 'fuera';
                Log::info("📍 {$usuario->nombre} Salió de {$geocerca->nombre}");
            }

            $estado->ultima_latitud       = $ubicacion->latitud;
            $estado->ultima_longitud      = $ubicacion->longitud;
            $estado->ultima_actualizacion = now();
            $estado->save();
        }
    }

    public function calcularDistancia($lat1, $lng1, $lat2, $lng2): float
    {
        $tierraRadio = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) ** 2;

        return $tierraRadio * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    protected function crearAlerta($geocerca, $usuario, $ubicacion, $tipo): void
    {
        $alerta = GeocercaAlerta::create([
            'geocerca_id' => $geocerca->id,
            'usuario_id'  => $usuario->id,
            'tipo'        => $tipo,
            'latitud'     => $ubicacion->latitud,
            'longitud'    => $ubicacion->longitud,
            'fecha_hora'  => now(),
            'notificado'  => false, // = "no leído por el admin"
        ]);

        // Notificar SOLO al admin por Telegram
        $this->notificarAdmin($alerta);

        // Broadcast en tiempo real para el dashboard
        try {
            broadcast(new GeocercaAlertaEvent($alerta->load('usuario', 'geocerca')))->toOthers();
        } catch (\Throwable $e) {
            Log::error('❌ Error broadcast GeocercaAlerta: ' . $e->getMessage());
        }
    }

    /**
     * Notifica únicamente al admin. NO al instalador.
     */
    protected function notificarAdmin(GeocercaAlerta $alerta): void
    {
        $adminChatId = config('telegram.admin_chat_id')
            ?? env('TELEGRAM_ADMIN_CHAT_ID')
            ?? env('TELEGRAM_GROUP_CHAT_ID');

        if (!$adminChatId) {
            Log::warning('⚠️ No hay admin_chat_id configurado para notificar geocerca');
            return;
        }

        $emoji = $alerta->tipo === 'entrada' ? '🟢 ENTRÓ a' : '🔴 Salió de';
        $usuario = $alerta->usuario;
        $geocerca = $alerta->geocerca;

        $mensaje = "📍 *Alerta de Geocerca*\n\n"
                 . "{$emoji}: *{$geocerca->nombre}*\n"
                 . "👤 Instalador: {$usuario->nombre}\n"
                 . "📅 {$alerta->fecha_hora->format('d/m/Y H:i')}\n"
                 . "🗺️ [Ver en mapa](https://www.google.com/maps?q={$alerta->latitud},{$alerta->longitud})";

        try {
            $this->telegram->sendMessage($adminChatId, $mensaje);
        } catch (\Throwable $e) {
            Log::error('❌ Error enviando Telegram geocerca: ' . $e->getMessage());
        }
    }
}