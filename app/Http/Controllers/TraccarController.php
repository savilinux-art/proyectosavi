<?php

namespace App\Http\Controllers;

use App\Services\TraccarService;
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
        // 1. Dispositivos
        $devices = $this->traccar->getDevices();

        // 2. Todas las posiciones en una sola llamada
        $allPositions = collect($this->traccar->getAllPositions() ?? [])
            ->keyBy(fn($pos) => is_array($pos) ? $pos['deviceId'] : $pos->deviceId);

        // 3. Combinar dispositivos con su posición
        $positions = [];
        foreach ($devices as $device) {
            $deviceId = is_array($device) ? ($device['id'] ?? null) : ($device->id ?? null);

            if (!$deviceId) {
                continue;
            }

            $positions[] = [
                'device'   => $device,
                'position' => $allPositions->get($deviceId), // null si no tiene
            ];
        }

        // 4. URL del socket desde config
        $socketUrl = config('traccar.websocket_url');

        return view('traccar.map', compact('positions', 'socketUrl'));
    }

    /**
     * Devuelve las posiciones en JSON (útil para AJAX / polling)
     */
    public function getPositions()
    {
        $devices   = $this->traccar->getDevices();
        $positions = [];

        foreach ($devices as $device) {
            $deviceId = is_array($device)
                ? ($device['id'] ?? $device['uniqueId'] ?? null)
                : ($device->id ?? $device->uniqueId ?? null);

            if (!$deviceId) {
                continue;
            }

            $pos = $this->traccar->getLatestPosition($deviceId);

            if ($pos) {
                $positions[] = [
                    'device'   => $device,
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
     * Obtiene el historial de un dispositivo
     */
    public function getHistory($deviceId, Request $request)
    {
        $from = $request->get('from', now()->subDay()->toIso8601String());
        $to   = $request->get('to', now()->toIso8601String());

        // Asegúrate de tener este método en tu TraccarService
        $history = $this->traccar->getDeviceHistory($deviceId, $from, $to);

        return response()->json($history);
    }
}