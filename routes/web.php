<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\AsignacionInstalacionController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\SalidaInventarioController;
use App\Http\Controllers\DevolucionInventarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\EstatusController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\TelegramLocationController;
use App\Services\TelegramService;

// Auth
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard y módulos (protegidos por autenticación y permisos)
Route::middleware(['auth.session', 'permiso:ver-dashboard'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Inventario
Route::middleware(['auth.session', 'permiso:ver-inventario'])->group(function () {
    Route::resource('inventario', InventarioController::class);
    Route::get('inventario/export', [InventarioController::class, 'export'])->name('inventario.export');
});

// Salidas de Inventario
Route::middleware(['auth.session'])->group(function () {
    Route::resource('salidas', SalidaInventarioController::class);
    Route::get('salidas/buscar-productos', [SalidaInventarioController::class, 'buscarProductos'])->name('salidas.buscarProductos');
    Route::get('salidas/{id}/download/{copia?}', [SalidaInventarioController::class, 'downloadPDF'])->name('salidas.download');
    Route::get('salidas/{id}/imprimir', [SalidaInventarioController::class, 'imprimir'])->name('salidas.imprimir');
});

// ✅ Devoluciones de Inventario (corregido)
Route::middleware(['auth.session'])->group(function () {
    Route::resource('devoluciones', DevolucionInventarioController::class);
    Route::get('devoluciones/buscar-productos', [DevolucionInventarioController::class, 'buscarProductos'])->name('devoluciones.buscarProductos');
});

// Ventas
Route::middleware(['auth.session', 'permiso:ver-ventas'])->group(function () {
    Route::resource('ventas', VentaController::class);
    Route::get('ventas/export', [VentaController::class, 'export'])->name('ventas.export');
});

// Instalaciones
Route::middleware(['auth.session', 'permiso:ver-instalaciones'])->group(function () {
    Route::resource('instalaciones', InstalacionController::class);
});

// Clientes
Route::middleware(['auth.session', 'permiso:ver-clientes'])->group(function () {
    Route::resource('clientes', ClienteController::class);
});

// Proyectos
Route::middleware(['auth.session', 'permiso:ver-proyectos'])->group(function () {
    Route::resource('proyectos', ProyectoController::class);
});

// Categorías y Estatus
Route::middleware(['auth.session', 'permiso:ver-inventario'])->group(function () {
    Route::resource('categorias', CategoriaController::class);
    Route::resource('estatus', EstatusController::class);
});

// Usuarios (solo admin)
Route::middleware(['auth.session', 'administrador'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});

// Roles y Permisos (solo admin)
Route::middleware(['auth.session', 'administrador'])->group(function () {
    Route::resource('roles', RolController::class);
    Route::resource('permisos', PermisoController::class);
});

// Asignaciones (solo admin)
Route::middleware(['auth.session', 'administrador'])->group(function () {
    Route::resource('asignaciones', AsignacionInstalacionController::class);
});

// Notificaciones
Route::middleware(['auth.session'])->group(function () {
    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('notificaciones/{id}/marcar-leida', [NotificacionController::class, 'markAsRead'])->name('notificaciones.markAsRead');
    Route::post('notificaciones/marcar-todas', [NotificacionController::class, 'markAllAsRead'])->name('notificaciones.markAllAsRead');
    Route::delete('notificaciones/{id}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
    Route::get('notificaciones/count', [NotificacionController::class, 'count'])->name('notificaciones.count');
});

// Telegram Location
Route::post('/telegram/send-location/start/{id}', [TelegramLocationController::class, 'sendStartLocation'])
    ->name('telegram.send-start');

Route::post('/telegram/send-location/end/{id}', [TelegramLocationController::class, 'sendEndLocation'])
    ->name('telegram.send-end');

// Salidas y Devoluciones (repetido, pero lo dejamos)
Route::middleware(['auth.session', 'permiso:ver-salidas'])->group(function () {
    Route::resource('salidas', SalidaInventarioController::class);
    Route::get('salidas/{id}/download/{copia?}', [SalidaInventarioController::class, 'downloadPDF'])->name('salidas.download');
});

Route::middleware(['auth.session', 'permiso:ver-devoluciones'])->group(function () {
    Route::resource('devoluciones', DevolucionInventarioController::class);
});

// Reportes
Route::middleware(['auth.session'])->group(function () {
    Route::resource('reportes', ReporteController::class)->except(['update']);
});

// ============================================================
// 🧪 RUTAS DE PRUEBA (CORREGIDAS)
// ============================================================

// Ruta de prueba: envía un mensaje simple a un chat_id fijo
Route::get('/test-telegram-system', function (TelegramService $telegram) {
    $chatId = '8884130238'; // Reemplaza con tu chat_id real
    try {
        $telegram->sendMessage($chatId, '✅ Mensaje de prueba desde Laravel');
        return '✅ Mensaje enviado correctamente desde Laravel';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

// Ruta de prueba: notifica a un instalador por su ID
Route::get('/test-notify/{id}', function ($id, TelegramService $telegram) {
    $usuario = \App\Models\Usuario::find($id);
    if (!$usuario) {
        return '❌ Usuario no encontrado';
    }
    $instalacion = \App\Models\Instalacion::first(); // toma cualquier instalación
    if (!$instalacion) {
        return '❌ No hay instalaciones disponibles';
    }
    try {
        $telegram->notifyInstalacionAsignada($usuario, $instalacion);
        return '✅ Notificación enviada al instalador ' . $usuario->nombre;
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});