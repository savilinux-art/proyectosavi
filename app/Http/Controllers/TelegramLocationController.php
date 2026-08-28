<?php
namespace App\Http\Controllers;

use App\Http\Requests\Telegram\SendLocationRequest;
use App\Services\TelegramService;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class TelegramLocationController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function sendLocationRequest(SendLocationRequest $request)
    {
        $usuario = Usuario::findOrFail($request->id);

        try {
            $this->telegramService->sendLocationRequest($usuario);
            return back()->with('success', "✅ Solicitud enviada a {$usuario->nombre}.");
        } catch (\Exception $e) {
            Log::error('Error en solicitud de ubicación: ' . $e->getMessage());
            return back()->with('error', '❌ Error al enviar la solicitud.');
        }
    }
}