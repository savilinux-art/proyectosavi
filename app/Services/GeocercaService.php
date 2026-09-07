<?php

namespace App\Services;

use App\Models\Geocerca;
use App\Models\GeocercaAlerta;
use App\Models\UbicacionUsuario;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use App\Events\GeocercaAlertaEvent;

class GeocercaService
{
    /**
     * Verifica si un punto está dentro de una geocerca
     */
    public function puntoEstaDentro($lat, $lng, Geocerca $geocerca): bool
    {
        $distancia = $this->calcularDistancia(
            $lat, $lng,
            $geocerca->latitud, $geocerca->longitud
        );
        return $distancia <= $geocerca->radio;
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

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $tierraRadio * $c;
    }

    /**
     * Procesa una ubicación y verifica todas las geocercas activas
     */
    public function procesarUbicacion(UbicacionUsuario $ubicacion): void
    {
        $usuario = $ubicacion->usuario;

        if (!$usuario) {
            return;
        }

        $geocercas = Geocerca::where('activa', true)->get();

        foreach ($geocercas as $geocerca) {
            $estaDentro = $this->puntoEstaDentro(
                $ubicacion->latitud,
                $ubicacion->longitud,
                $geocerca
            );

            // Buscar el último estado conocido
            $ultimaAlerta = GeocercaAlerta::where('geocerca_id', $geocerca->id)
                ->where('usuario_id', $usuario->id)
                ->latest('fecha_hora')
                ->first();

            $estabaDentro = $ultimaAlerta && $ultimaAlerta->tipo === 'entrada';

            if ($estaDentro && !$estabaDentro) {
                // ✅ ENTRÓ a la geocerca
                $this->crearAlerta($geocerca, $usuario, $ubicacion, 'entrada');
            } elseif (!$estaDentro && $estabaDentro) {
                // ❌ Salió de la geocerca
                $this->crearAlerta($geocerca, $usuario, $ubicacion, 'salida');
            }
        }
    }

    /**
     * Crea una alerta de geocerca y envía notificaciones
     */
    protected function crearAlerta($geocerca, $usuario, $ubicacion, $tipo)
    {
        $alerta = GeocercaAlerta::create([
            'geocerca_id' => $geocerca->id,
            'usuario_id' => $usuario->id,
            'tipo' => $tipo,
            'latitud' => $ubicacion->latitud,
            'longitud' => $ubicacion->longitud,
            'fecha_hora' => now(),
            'notificado' => false,
        ]);

        // Enviar notificaciones
        $this->notificarAlerta($alerta);

        // Emitir evento WebSocket (para actualizar el mapa en tiempo real)
        broadcast(new GeocercaAlertaEvent($alerta))->toOthers();

        Log::info("📍 Geocerca alerta: {$usuario->nombre} {$tipo} de {$geocerca->nombre}");
    }

    /**
     * Envía notificaciones por Telegram
     */
    protected function notificarAlerta($alerta)
    {
        $geocerca = $alerta->geocerca;
        $usuario = $alerta->usuario;
        $tipo = $alerta->tipo === 'entrada' ? '🟢 ENTRÓ' : '🔴 Salió de';

        $mensaje = "📍 Alerta de Geocerca\n\n" .
                   "{$tipo} la zona: *{$geocerca->nombre}*\n" .
                   "👤 Instalador: {$usuario->nombre}\n" .
                   "📅 Fecha: {$alerta->fecha_hora->format('d/m/Y H:i')}\n" .
                   "📍 Ubicación: {$alerta->latitud}, {$alerta->longitud}";

        // Enviar al instalador (para que sepa que cruzó la zona)
        if ($usuario->telegram_chat_id) {
            app(TelegramService::class)->sendMessage(
                $usuario->telegram_chat_id,
                $mensaje
            );
        }

        // Enviar al grupo de administradores (opcional)
        // Puedes obtener un chat_id de grupo de telegram desde una variable de entorno
        $adminGroup = env('TELEGRAM_GROUP_CHAT_ID');
        if ($adminGroup) {
            app(TelegramService::class)->sendMessage($adminGroup, $mensaje);
        }
    }
}