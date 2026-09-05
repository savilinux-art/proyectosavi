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

    public function index()
    {
        $devices = $this->traccar->getDevicesWithPositions();
        return view('traccar.map', compact('devices'));
    }

    public function getPositions()
    {
        $devices = $this->traccar->getDevicesWithPositions();
        return response()->json($devices);
    }

    public function getDevicePosition($deviceId)
    {
        $position = $this->traccar->getDevicePosition($deviceId);
        return response()->json($position);
    }

    public function getHistory($deviceId, Request $request)
    {
        $from = $request->get('from', now()->subDay()->toIso8601String());
        $to = $request->get('to', now()->toIso8601String());
        $history = $this->traccar->getDeviceHistory($deviceId, $from, $to);
        return response()->json($history);
    }
}