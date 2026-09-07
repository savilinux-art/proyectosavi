<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use App\Events\UbicacionActualizada;
use App\Models\UbicacionUsuario;
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

        Log::info('📨 Webhook recibido - PAYLOAD COMPLETO', $payload);

        try {
            if (isset($payload['callback_query'])) {
                Log::info('📞 Procesando CALLBACK_QUERY', $payload['callback_query']);
                $this->telegramService->handleCallbackQuery($payload['callback_query']);
                return response()->json(['status' => 'callback_processed']);
            }

            if (isset($payload['message']['location'])) {
                Log::info('📍 Procesando LOCATION', $payload['message']['location']);
                
                // Procesar ubicación (asumimos que guarda y devuelve algo)
                $result = $this->telegramService->handleLocation($payload['message']);
                
                // Si el servicio devuelve la ubicación guardada, emitir evento
                if ($result && isset($result['ubicacion'])) {
                    $ubicacion = $result['ubicacion'];
                    broadcast(new UbicacionActualizada($ubicacion))->toOthers();
                    Log::info('📡 Evento UbicacionActualizada emitido', ['ubicacion_id' => $ubicacion->id]);
                } else {
                    // Fallback: obtener la última ubicación del usuario
                    $chatId = $payload['message']['chat']['id'] ?? null;
                    if ($chatId) {
                        $ultima = UbicacionUsuario::whereHas('usuario', function($q) use ($chatId) {
                            $q->where('telegram_chat_id', $chatId);
                        })->latest()->first();
                        if ($ultima) {
                            broadcast(new UbicacionActualizada($ultima))->toOthers();
                            Log::info('📡 Evento UbicacionActualizada emitido (fallback)', ['ubicacion_id' => $ultima->id]);
                        }
                    }
                }
                
                return response()->json(['status' => 'location_processed']);
            }

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