<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TraccarService
{
    protected string $baseUrl;
    protected ?string $username;
    protected ?string $password;
    protected ?string $token;
    protected string $authMethod;

    public function __construct()
    {
        $this->baseUrl    = rtrim(config('traccar.base_url', 'http://localhost:8082'), '/') . '/';
        $this->username   = config('traccar.auth.username');
        $this->password   = config('traccar.auth.password');
        $this->token      = config('traccar.auth.token');
        $this->authMethod = config('traccar.auth.method', 'auto');
    }

    /* ============================================================
     *  HELPER PRIVADO — única fuente de auth y manejo de errores
     * ============================================================ */
    protected function request(string $endpoint, array $params = []): ?array
    {
        try {
            $url = $this->baseUrl . ltrim($endpoint, '/');

            $http = Http::timeout(15)->acceptJson();

            // Elegir método de autenticación
            $method = $this->authMethod;

            if ($method === 'auto') {
                $method = $this->token ? 'token' : 'basic';
            }

            if ($method === 'token' && $this->token) {
                // Traccar acepta: Authorization: Bearer <token>
                $http = $http->withToken($this->token);
            } elseif ($method === 'basic' && $this->username && $this->password) {
                $http = $http->withBasicAuth($this->username, $this->password);
            } else {
                Log::error('Traccar: no hay credenciales válidas configuradas', [
                    'method' => $method,
                    'has_token' => !empty($this->token),
                    'has_basic' => !empty($this->username) && !empty($this->password),
                ]);
                return null;
            }

            $response = $http->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Traccar API error', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
                'method' => $method,
            ]);
            return null;

        } catch (\Throwable $e) {
            Log::error('Traccar exception: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
            ]);
            return null;
        }
    }

    /* ============================================================
     *  MÉTODOS PÚBLICOS
     * ============================================================ */

    public function getDevices(): array
    {
        return $this->request('api/devices') ?? [];
    }

    public function getLatestPosition($deviceId): ?array
    {
        $positions = $this->request('api/positions', [
            'deviceId' => $deviceId,
            'limit'    => 1,
        ]);

        return $positions[0] ?? null;
    }

    public function getAllPositions(): array
    {
        return $this->request('api/positions') ?? [];
    }

    public function getServerInfo(): ?array
    {
        return $this->request('api/server');
    }
}