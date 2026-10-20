<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProyectoNoEncontradoException extends Exception
{
    public function __construct(
        public readonly string $nombreBuscado,
        string $message = '',
    ) {
        parent::__construct(
            $message ?: "El proyecto '{$nombreBuscado}' no existe en el catálogo. " .
                        "Contacta al administrador para que lo cree y reintenta la subida."
        );
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success'         => false,
                'message'         => $this->getMessage(),
                'nombre_buscado'  => $this->nombreBuscado,
                'accion_sugerida' => 'contactar_administrador',
                'detalle'         => 'Los proyectos solo pueden ser creados por Administrador o Ventas. ' .
                                     'Reporta esta salida para que sea corregida en el sistema web.',
            ], 422);
        }

        return back()->withErrors([
            'proyecto' => $this->getMessage(),
        ]);
    }
}
