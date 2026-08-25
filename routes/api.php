<?php

use  Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\InstalacionController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ProyectoController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin autenticación)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index']);
    Route::get('/inventario/{id}', [InventarioController::class, 'show']);
    Route::post('/inventario', [InventarioController::class, 'store']);
    Route::put('/inventario/{id}', [InventarioController::class, 'update']);
    Route::delete('/inventario/{id}', [InventarioController::class, 'destroy']);
    Route::get('/inventario/categorias', [InventarioController::class, 'categorias']);
    
    // Ventas
    Route::get('/ventas', [VentaController::class, 'index']);
    Route::get('/ventas/{id}', [VentaController::class, 'show']);
    Route::post('/ventas', [VentaController::class, 'store']);
    Route::put('/ventas/{id}', [VentaController::class, 'update']);
    Route::delete('/ventas/{id}', [VentaController::class, 'destroy']);
    Route::get('/ventas/estatus', [VentaController::class, 'estatus']);
    
    // Instalaciones
    Route::get('/instalaciones', [InstalacionController::class, 'index']);
    Route::get('/instalaciones/{id}', [InstalacionController::class, 'show']);
    Route::post('/instalaciones', [InstalacionController::class, 'store']);
    Route::put('/instalaciones/{id}', [InstalacionController::class, 'update']);
    Route::delete('/instalaciones/{id}', [InstalacionController::class, 'destroy']);
    Route::post('/instalaciones/{id}/evidencia', [InstalacionController::class, 'uploadEvidence']);
    Route::post('/instalaciones/{id}/checklist', [InstalacionController::class, 'updateChecklist']);
    
    // Clientes
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::put('/clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);
    
    // Proyectos
    Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
    Route::post('/proyectos', [ProyectoController::class, 'store']);
    Route::put('/proyectos/{id}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']);
    
    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index']);
    Route::get('/notificaciones/{id}', [NotificacionController::class, 'show']);
    Route::put('/notificaciones/{id}/marcar-leida', [NotificacionController::class, 'markAsRead']);
    Route::post('/notificaciones/marcar-todas', [NotificacionController::class, 'markAllAsRead']);
    Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy']);
    Route::get('/notificaciones/noleidas/count', [NotificacionController::class, 'unreadCount']);

    // Clientes - Rutas adicionales
    Route::get('/clientes/buscar', [ClienteController::class, 'search']);
    Route::get('/clientes/resumen', [ClienteController::class, 'resumen']);
    Route::get('/clientes/regimen/{regimen}', [ClienteController::class, 'byRegimen']);
    Route::get('/clientes/{id}/constancia', [ClienteController::class, 'downloadConstancia']);

    // Proyectos - Rutas adicionales
    Route::get('/proyectos/buscar', [ProyectoController::class, 'search']);
    Route::get('/proyectos/resumen', [ProyectoController::class, 'resumen']);
    Route::get('/proyectos/recientes', [ProyectoController::class, 'recientes']);
    Route::get('/proyectos/estatus/{estatus}', [ProyectoController::class, 'byStatus']);
    Route::get('/proyectos/existe/{nombre}', [ProyectoController::class, 'exists']);
    Route::get('/proyectos/{id}/archivo/{tipo}', [ProyectoController::class, 'downloadFile']);

});

