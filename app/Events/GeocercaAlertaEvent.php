<?php

namespace App\Events;

use App\Models\GeocercaAlerta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeocercaAlertaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alerta;

    public function __construct(GeocercaAlerta $alerta)
    {
        $this->alerta = $alerta->load(['geocerca', 'usuario']);
    }

    public function broadcastOn()
    {
        return new Channel('geocercas');
    }

    public function broadcastAs()
    {
        return 'geocerca.alerta';
    }
}