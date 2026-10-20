<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalidaInventario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalidaApiController extends Controller
{
    /**
     * GET /api/salidas
     * Pull de salidas donde el usuario autenticado es el receptor (entregado_a).
     * entregado_a es string (FK a usuarios.usuario), NO id numérico.
     */
    public function index(Request $request): JsonResponse
    {
        $user    = $request->user();
        $perPage = min((int) $request->input('per_page', 50), 100);

        $query = SalidaInventario::query()
            ->where('entregado_a', $user->usuario);

        if ($request->filled('since')) {
            $query->where('updated_at', '>=', $request->input('since'));
        }

        $salidas = $query->orderByDesc('updated_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $salidas->items(),
            'total'   => $salidas->total(),
            'meta'    => [
                'current_page' => $salidas->currentPage(),
                'last_page'    => $salidas->lastPage(),
                'per_page'     => $salidas->perPage(),
                'total'        => $salidas->total(),
            ],
        ]);
    }
}