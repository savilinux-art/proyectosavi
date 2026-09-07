<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TraccarService
{
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('traccar.base_url', 'http://localhost:8082/');
        $this->username = config('traccar.auth.username');
        $this->password = config('traccar.auth.password');
    }

    public function getDevices()
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->get($this->baseUrl . 'api/devices');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Traccar getDevices failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('Traccar getDevices exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

   public function getLatestPosition($deviceId)
{
    try {
        // Construir URL
        $url = $this->baseUrl . 'api/positions';
        $params = ['deviceId' => $deviceId, 'limit' => 1];

        \Log::info('Traccar getLatestPosition - request', [
            'url' => $url,
            'params' => $params,
            'username' => $this->username,
        ]);

        $response = Http::withBasicAuth($this->username, $this->password)
            ->get($url, $params);

        \Log::info('Traccar getLatestPosition - response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            \Log::info('Traccar getLatestPosition - data', ['data' => $data]);
            return $data[0] ?? null;
        }

        \Log::error('Traccar getLatestPosition failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return null;
    } catch (\Exception $e) {
        \Log::error('Traccar getLatestPosition exception', ['error' => $e->getMessage()]);
        return null;
    }
}
}