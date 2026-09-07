<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeocercaAlertaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Los datos de la alerta que se enviarán por WebSocket.
     *
     * @var array
     */
    public $alerta;

    /**
     * Crea una nueva instancia del evento.
     *
     * @param  mixed  $alerta  Datos de la alerta (puede ser modelo o array)
     * @return void
     */
    public function __construct($alerta)
    {
        // Si es un objeto (modelo), lo convertimos a array o extraemos los datos necesarios
        if (is_object($alerta)) {
            $this->alerta = [
                'id' => $alerta->id,
                'tipo' => $alerta->tipo,
                'fecha_hora' => $alerta->fecha_hora ? $alerta->fecha_hora->toISOString() : now()->toISOString(),
                'geocerca' => [
                    'id' => $alerta->geocerca->id ?? null,
                    'nombre' => $alerta->geocerca->nombre ?? 'Sin nombre',
                    'color' => $alerta->geocerca->color ?? '#FF0000',
                ],
                'usuario' => [
                    'id' => $alerta->usuario->id ?? null,
                    'nombre' => $alerta->usuario->nombre ?? 'Usuario desconocido',
                ],
                'latitud' => $alerta->latitud,
                'longitud' => $alerta->longitud,
            ];
        } else {
            // Si ya es un array, lo usamos directamente (suponiendo que tiene la estructura correcta)
            $this->alerta = $alerta;
        }
    }

    /**
     * Obtiene los canales por los que se transmitirá el evento.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('geocercas'),
        ];
    }

    /**
     * Nombre con el que se transmitirá el evento.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'geocerca.alerta';
    }
public function activas()
{
    $geocercas = \App\Models\Geocerca::where('activa', true)->get();
    return response()->json($geocercas);
}

}
