<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UbicacionActualizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ubicacion;

    /**
     * Create a new event instance.
     */
    public function __construct($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('ubicaciones');
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'ubicacion.actualizada';
    }
}