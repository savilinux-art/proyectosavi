<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TraccarService
{
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = env('TRACCAR_BASE_URL', 'http://localhost:8082/');
        $this->username = env('TRACCAR_USERNAME', 'savilinux@gamil.com');
        $this->password = env('TRACCAR_PASSWORD', 'Is660222..');
    }

    public function login()
    {
        $response = Http::post($this->baseUrl . 'api/session', [
            'email' => $this->username,
            'password' => $this->password,
        ]);

        if ($response->successful()) {
            $cookie = $response->header('Set-Cookie');
            preg_match('/JSESSIONID=([^;]+)/', $cookie, $matches);
            return $matches[1] ?? null;
        }

        return null;
    }

    public function getLatestPosition($deviceId)
    {
        $sessionId = $this->login();
        if (!$sessionId) {
            return null;
        }

        $response = Http::withHeaders([
            'Cookie' => 'JSESSIONID=' . $sessionId,
        ])->get($this->baseUrl . 'api/positions', [
            'deviceId' => $deviceId,
            'limit' => 1,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data[0] ?? null;
        }

        return null;
    }

    public function getDevices()
    {
        $sessionId = $this->login();
        if (!$sessionId) {
            return [];
        }

        $response = Http::withHeaders([
            'Cookie' => 'JSESSIONID=' . $sessionId,
        ])->get($this->baseUrl . 'api/devices');

        return $response->successful() ? $response->json() : [];
    }
}