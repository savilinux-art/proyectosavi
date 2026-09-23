<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function __construct(
        protected TelegramService $telegramService
    ) {}

    public function handle(Request $request)
    {
        // 🔒 SIEMPRE devolver 200 OK. Nunca relanzar excepciones.
        try {
            $payload  = $request->all();
            $updateId = $payload['update_id'] ?? null;

            // 🔒 IDEMPOTENCIA: si el mismo update ya se procesó, ignorar
            if ($updateId) {
                $cacheKey = "tg:update:{$updateId}";
                if (Cache::has($cacheKey)) {
                    Log::info('⏭️ Update duplicado ignorado', ['update_id' => $updateId]);
                    return response('OK', 200);
                }
                Cache::put($cacheKey, true, now()->addHours(24));
            }

            Log::info('📨 Webhook recibido', [
                'update_id' => $updateId,
                'tipo'      => isset($payload['callback_query']) ? 'callback' :
                              (isset($payload['message']['location']) ? 'location' : 'otro'),
            ]);

            // Callback (botones inline)
            if (isset($payload['callback_query'])) {
                $this->telegramService->handleCallbackQuery($payload['callback_query']);
                return response('OK', 200);
            }

            // Location (compartir ubicación)
            if (isset($payload['message']['location'])) {
                // handleLocation ya hace todo: guarda + geocercas + broadcast
                $this->telegramService->handleLocation($payload['message']);
                return response('OK', 200);
            }

            Log::info('📩 Update ignorado', ['update_id' => $updateId]);
            return response('OK', 200);

        } catch (\Throwable $e) {
            // 🔒 CRÍTICO: NUNCA devolver 500 ni relanzar.
            // Si lo haces, Telegram reintenta este mismo update para siempre.
            Log::error('❌ Error webhook Telegram', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response('OK', 200);
        }
    }
}