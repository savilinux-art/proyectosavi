<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handle(Request $request)
    {
        $payload = $request->all();

        // 🔍 Log 1: Ver qué llega exactamente
        Log::info('📨 Webhook recibido - PAYLOAD COMPLETO', $payload);

        try {
            // 🔥 Verificar si existe callback_query
            if (isset($payload['callback_query'])) {
                Log::info('📞 Procesando CALLBACK_QUERY', $payload['callback_query']);
                $this->telegramService->handleCallbackQuery($payload['callback_query']);
                return response()->json(['status' => 'callback_processed']);
            }

            // 🔥 Verificar si existe mensaje con ubicación
            if (isset($payload['message']['location'])) {
                Log::info('📍 Procesando LOCATION', $payload['message']['location']);
                $this->telegramService->handleLocation($payload['message']);
                return response()->json(['status' => 'location_processed']);
            }

            // Si es otro tipo de mensaje
            Log::info('📩 Mensaje ignorado', ['tipo' => 'otro']);
            return response()->json(['status' => 'ignored']);

        } catch (\Exception $e) {
            Log::error('❌ Error en webhook: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}