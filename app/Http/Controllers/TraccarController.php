<?php

namespace App\Http\Controllers;

use App\Services\TraccarService;  // Usa tu servicio personalizado
use Illuminate\Http\Request;

class TraccarController extends Controller
{
    protected $traccar;

    public function __construct(TraccarService $traccar)
    {
        $this->traccar = $traccar;
    }

    /**
     * Muestra el mapa con los dispositivos
     */
 public function index()
{
    // 1. Obtener todos los dispositivos
    $devices = $this->traccar->getDevices();

    // 2. Obtener TODAS las posiciones en una sola llamada (evita N+1)
    //    Si tu cliente no tiene este método, usa el endpoint /api/positions
    $allPositions = collect($this->traccar->getPositions() ?? [])
        ->keyBy(fn($pos) => is_array($pos) ? $pos['deviceId'] : $pos->deviceId);

    // 3. Combinar dispositivos con su última posición
    $positions = [];
    foreach ($devices as $device) {
        // Normalizar: puede venir como array o como DTO/objeto
        $deviceId = is_array($device) ? ($device['id'] ?? null) : ($device->id ?? null);

        if (!$deviceId) {
            continue; // Dispositivo sin ID, lo saltamos
        }

        $position = $allPositions->get($deviceId);

        $positions[] = [
            'device'   => $device,
            'position' => $position, // puede ser null si no tiene posición
        ];
    }

    // 4. URL del socket desde el .env
    $socketUrl = env('TRACCAR_SOCKET_URL');

    // 5. Pasar TODAS las variables a la vista
    return view('traccar.map', compact('positions', 'socketUrl'));
}

    /**
     * Devuelve las posiciones en JSON
     */
    public function getPositions()
    {
        $devices = $this->traccar->getDevices();
        $positions = [];

        foreach ($devices as $device) {
            $pos = $this->traccar->getLatestPosition($device['id'] ?? $device['uniqueId']);
            if ($pos) {
                $positions[] = [
                    'device' => $device,
                    'position' => $pos,
                ];
            }
        }

        return response()->json($positions);
    }

    /**
     * Obtiene la posición de un dispositivo específico
     */
    public function getDevicePosition($deviceId)
    {
        $position = $this->traccar->getLatestPosition($deviceId);
        return response()->json($position);
    }

    /**
     * Obtiene el historial de un dispositivo (requiere implementar en TraccarService)
     */
    public function getHistory($deviceId, Request $request)
    {
        $from = $request->get('from', now()->subDay()->toIso8601String());
        $to = $request->get('to', now()->toIso8601String());
        
        // Implementa este método en tu servicio o usa la API directamente
        $history = $this->traccar->getDeviceHistory($deviceId, $from, $to);
        
        return response()->json($history);
    }
}