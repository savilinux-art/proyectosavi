<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TraccarService
{
    protected string $baseUrl;
    protected ?string $username;
    protected ?string $password;

    public function __construct()
    {
        // Normaliza la URL: siempre termina con exactamente un '/'
        $this->baseUrl  = rtrim(config('traccar.base_url', 'http://localhost:8082'), '/') . '/';
        $this->username = config('traccar.auth.username');
        $this->password = config('traccar.auth.password');
    }

    /* ============================================================
     *  HELPER PRIVADO — única fuente de auth y manejo de errores
     * ============================================================ */
    protected function request(string $endpoint, array $params = []): ?array
    {
        try {
            $url = $this->baseUrl . ltrim($endpoint, '/');

            $response = Http::withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->acceptJson()
                ->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Traccar API error', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
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

    /**
     * Lista todos los dispositivos.
     */
    public function getDevices(): array
    {
        return $this->request('api/devices') ?? [];
    }

    /**
     * Última posición de un dispositivo específico.
     */
    public function getLatestPosition($deviceId): ?array
    {
        $positions = $this->request('api/positions', [
            'deviceId' => $deviceId,
            'limit'    => 1,
        ]);

        return $positions[0] ?? null;
    }

    /**
     * Últimas posiciones de TODOS los dispositivos (1 sola llamada HTTP).
     * Más eficiente que iterar getLatestPosition().
     */
    public function getAllPositions(): array
    {
        return $this->request('api/positions') ?? [];
    }

    /**
     * Info del servidor Traccar (diagnóstico).
     */
    public function getServerInfo(): ?array
    {
        return $this->request('api/server');
    }
}