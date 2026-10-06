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
use App\Http\Controllers\TelegramLocationController;
use App\Http\Controllers\TraccarController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\GeocercaController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\GeocercaAlertaController;
use App\Http\Controllers\RecordatorioController;
use App\Http\Controllers\VentaMostradorController;

// ═══════════════════════════════════════════════════════════
// AUTH
// ═══════════════════════════════════════════════════════════
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ═══════════════════════════════════════════════════════════
// DASHBOARD
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-dashboard'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ═══════════════════════════════════════════════════════════
// INVENTARIO + CATEGORÍAS + ESTATUS
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-inventario'])->group(function () {
    // Específicas ANTES del resource
    Route::get('inventario/export', [InventarioController::class, 'export'])->name('inventario.export');
    Route::post('inventario/{id}/ajustar-stock', [InventarioController::class, 'ajustarStock'])
        ->name('inventario.ajustarStock');

    Route::resource('inventario', InventarioController::class);

    Route::resource('categorias', CategoriaController::class);
    Route::resource('estatus', EstatusController::class);
});

// ═══════════════════════════════════════════════════════════
// SALIDAS DE INVENTARIO
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-salidas'])->group(function () {
    // Buscador ANTES del resource
    Route::get('salidas/buscar-productos', [SalidaInventarioController::class, 'buscarProductos'])
        ->name('salidas.buscarProductos');

    Route::get('salidas/{id}/download/{copia?}', [SalidaInventarioController::class, 'downloadPDF'])
        ->name('salidas.download');
    Route::get('salidas/{id}/imprimir', [SalidaInventarioController::class, 'imprimir'])
        ->name('salidas.imprimir');

    Route::resource('salidas', SalidaInventarioController::class);
});

// ═══════════════════════════════════════════════════════════
// DEVOLUCIONES DE INVENTARIO (único bloque)
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-devoluciones'])->group(function () {
    Route::get('devoluciones/buscar-productos', [DevolucionInventarioController::class, 'buscarProductos'])
        ->name('devoluciones.buscarProductos');

    Route::resource('devoluciones', DevolucionInventarioController::class);
});

// ═══════════════════════════════════════════════════════════
// VENTAS
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-ventas'])->group(function () {
    Route::get('ventas/export', [VentaController::class, 'export'])->name('ventas.export');
    Route::resource('ventas', VentaController::class);
});

// ═══════════════════════════════════════════════════════════
// COTIZACIONES
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-ventas'])->group(function () {
    // Específicas ANTES del resource
    Route::get('cotizaciones/buscar-productos', [CotizacionController::class, 'buscarProductos'])
        ->name('cotizaciones.buscarProductos');
    Route::get('cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'pdf'])
        ->name('cotizaciones.pdf');
    Route::patch('cotizaciones/{cotizacion}/enviar', [CotizacionController::class, 'enviar'])
        ->name('cotizaciones.enviar');
    Route::get('cotizaciones/{cotizacion}/almacen', [CotizacionController::class, 'vistaAlmacen'])
        ->name('cotizaciones.almacen');

    Route::resource('cotizaciones', CotizacionController::class, [
        'parameters' => ['cotizaciones' => 'cotizacion']
    ]);
});

// ═══════════════════════════════════════════════════════════
// VENTAS DE MOSTRADOR
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ventas-mostrador'])->group(function () {
    Route::get('ventas_mostrador/buscar-inventario', [VentaMostradorController::class, 'buscarInventario'])
        ->name('ventas_mostrador.buscarInventario');
    Route::patch('ventas_mostrador/{ventas_mostrador}/cancelar', [VentaMostradorController::class, 'cancelar'])
        ->name('ventas_mostrador.cancelar');
    Route::patch('ventas_mostrador/{ventas_mostrador}/estado', [VentaMostradorController::class, 'cambiarEstado'])
        ->name('ventas_mostrador.cambiarEstado');
    Route::get('ventas_mostrador/{ventas_mostrador}/pdf', [VentaMostradorController::class, 'pdf'])
        ->name('ventas_mostrador.pdf');

    Route::resource('ventas_mostrador', VentaMostradorController::class)->except(['destroy']);
});

// ═══════════════════════════════════════════════════════════
// CLIENTES
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-clientes'])->group(function () {
    Route::resource('clientes', ClienteController::class);
});

// ═══════════════════════════════════════════════════════════
// PROYECTOS
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-proyectos'])->group(function () {
    // PDFs específicos ANTES del resource
    Route::get('proyectos/{proyecto}/salida-pdf', [ProyectoController::class, 'salidaPdf'])
        ->name('proyectos.salida-pdf');
    Route::get('proyectos/{proyecto}/devolucion-pdf', [ProyectoController::class, 'devolucionPdf'])
        ->name('proyectos.devolucion-pdf');
    Route::get('proyectos/{proyecto}/propuesta-pdf', [ProyectoController::class, 'propuestaPdf'])
        ->name('proyectos.propuesta-pdf');
    Route::get('proyectos/{proyecto}/as-built', [ProyectoController::class, 'asBuilt'])
        ->name('proyectos.as-built');

    Route::resource('proyectos', ProyectoController::class);
});

// ═══════════════════════════════════════════════════════════
// INSTALACIONES
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-instalaciones'])->group(function () {
    // Específicas ANTES del resource
    Route::get('instalaciones/mapa-data', [InstalacionController::class, 'mapaData'])
        ->name('instalaciones.mapaData');
    Route::post('instalaciones/{instalacion}/estatus', [InstalacionController::class, 'cambiarEstatus'])
        ->name('instalaciones.cambiarEstatus');
    Route::delete('instalaciones/{instalacion}/fotos/{foto}', [InstalacionController::class, 'eliminarFoto'])
        ->name('instalaciones.eliminarFoto');

    Route::resource('instalaciones', InstalacionController::class)
        ->parameters(['instalaciones' => 'instalacion']);
});

// ═══════════════════════════════════════════════════════════
// ASIGNACIONES (solo admin)
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'administrador'])->group(function () {
    Route::resource('asignaciones', AsignacionInstalacionController::class);
});

// ═══════════════════════════════════════════════════════════
// USUARIOS (solo admin)
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'administrador'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});

// ═══════════════════════════════════════════════════════════
// ROLES Y PERMISOS (solo admin)
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'administrador'])->group(function () {
    Route::resource('roles', RolController::class)->except(['show']);
    Route::resource('permisos', PermisoController::class);
});

// ═══════════════════════════════════════════════════════════
// RECORDATORIOS (solo auth — D2 pendiente)
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session'])->prefix('recordatorios')->name('recordatorios.')->group(function () {
    Route::get('/',               [RecordatorioController::class, 'index'])->name('index');
    Route::get('/create',         [RecordatorioController::class, 'create'])->name('create');
    Route::post('/',              [RecordatorioController::class, 'store'])->name('store');
    Route::get('/{id}/edit',      [RecordatorioController::class, 'edit'])->name('edit');
    Route::put('/{id}',           [RecordatorioController::class, 'update'])->name('update');
    Route::delete('/{id}',        [RecordatorioController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/cancelar', [RecordatorioController::class, 'cancelar'])->name('cancelar');
    Route::post('/{id}/renovar',  [RecordatorioController::class, 'renovar'])->name('renovar');
});

// ═══════════════════════════════════════════════════════════
// NOTIFICACIONES
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session'])->group(function () {
    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::get('notificaciones/count', [NotificacionController::class, 'count'])->name('notificaciones.count');
    Route::post('notificaciones/marcar-todas', [NotificacionController::class, 'markAllAsRead'])->name('notificaciones.markAllAsRead');
    Route::post('notificaciones/{id}/marcar-leida', [NotificacionController::class, 'markAsRead'])->name('notificaciones.markAsRead');
    Route::delete('notificaciones/{id}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
});

// ═══════════════════════════════════════════════════════════
// TELEGRAM — envío de ubicación
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session'])->group(function () {
    Route::post('/telegram/send-location/start/{id}', [TelegramLocationController::class, 'sendStartLocation'])
        ->name('telegram.send-start');
    Route::post('/telegram/send-location/end/{id}', [TelegramLocationController::class, 'sendEndLocation'])
        ->name('telegram.send-end');
});

// ═══════════════════════════════════════════════════════════
// REPORTES
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session'])->group(function () {
    Route::resource('reportes', ReporteController::class)->except(['update']);
});

// ═══════════════════════════════════════════════════════════
// UBICACIONES + TRACCAR (mapa)
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-ubicaciones'])->group(function () {
    Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
    Route::get('/ubicaciones/data', [UbicacionController::class, 'getUbicaciones'])->name('ubicaciones.data');
    Route::get('/ubicaciones/usuario/{id}', [UbicacionController::class, 'getUbicacion'])->name('ubicaciones.usuario');

    Route::get('/mapa', [TraccarController::class, 'index'])->name('mapa.index');
    Route::get('/mapa/posiciones', [TraccarController::class, 'getPositions'])->name('mapa.positions');
    Route::get('/mapa/dispositivo/{deviceId}', [TraccarController::class, 'getDevicePosition'])->name('mapa.device');
    Route::get('/mapa/historial/{deviceId}', [TraccarController::class, 'getHistory'])->name('mapa.history');
    Route::get('/traccar', [TraccarController::class, 'index'])->name('traccar.index');
});

// ═══════════════════════════════════════════════════════════
// GEOCERCAS — alertas + recurso
// ═══════════════════════════════════════════════════════════
Route::middleware(['auth.session', 'permiso:ver-geocercas'])->prefix('geocercas/alertas')->name('geocercas.alertas.')->group(function () {
    Route::get('recientes',   [GeocercaAlertaController::class, 'recientes'])->name('recientes');
    Route::post('leer-todas', [GeocercaAlertaController::class, 'marcarTodas'])->name('leerTodas');
    Route::post('{id}/leer',  [GeocercaAlertaController::class, 'marcarLeida'])->name('leer');
});

Route::middleware(['auth.session', 'permiso:ver-geocercas'])->group(function () {
    Route::get('geocercas/activas', [GeocercaController::class, 'activas'])->name('geocercas.activas');
    Route::resource('geocercas', GeocercaController::class);
});