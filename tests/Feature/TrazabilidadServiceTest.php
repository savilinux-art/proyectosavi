<?php

use App\Models\Proyecto;
use App\Models\Inventario;
use App\Models\Cotizacion;
use App\Models\SalidaInventario;
use App\Models\EvidenciaEntrega;
use App\Models\TrazabilidadDiscrepancia;
use App\Services\TrazabilidadService;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Usuario;



// ── Helpers locales, prefijo traz* para no colisionar con nada global ──

function trazProyecto(string $nombre): Proyecto
{
    // Igual que crearProyecto() de ProyectoControllerTest:
    // la venta debe existir ANTES y con el MISMO nombre_proyecto.
    Venta::factory()->create([
        'nombre_proyecto' => $nombre,
    ]);

    return Proyecto::factory()->create([
        'nombre_proyecto' => $nombre,
    ]);
}


function trazInventario(): Inventario
{
    $categoria = Categoria::firstOrCreate(
        ['nombre_categoria' => 'CCTV'],
    );

    return Inventario::factory()->create([
        'categoria'   => $categoria->nombre_categoria,
        'marca'       => 'Hikvision',
        'modelo'      => 'DS-2CD-' . uniqid(),
        'descripcion' => 'Cámara IP de prueba',
        'existencia'  => 100,
        'precio'      => 100.00,
    ]);
}


function trazCotizacionAprobada(Proyecto $proyecto, array $items): Cotizacion
{
     $cliente = Cliente::factory()->create(); 
     
    $cot = Cotizacion::forceCreate([
        'folio'         => 'COT-' . uniqid(),
        'proyecto_id'   => $proyecto->id,
        'fecha_emision' => now()->toDateString(),
        'fecha_validez' => now()->addDays(15)->toDateString(),
        'cliente_id'    => $cliente->id,
        'subtotal'      => 0,
        'iva'           => 0,
        'total'         => 0,
        'moneda'        => 'USD',
        'estatus'       => 'aprobada',
        'creado_por'    => 'sistema',
    ]);

    foreach ($items as $it) {
        DB::table('cotizacion_detalles')->insert([
            'cotizacion_id'   => $cot->id,
            'inventario_id'   => $it['inventario_id'],
            'descripcion'     => $it['descripcion'] ?? 'snapshot',
            'cantidad'        => $it['cantidad'],
            'precio_unitario' => $it['precio_unitario'] ?? 100.00,
            'importe'         => ($it['precio_unitario'] ?? 100.00) * $it['cantidad'],
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    return $cot;
}

function trazSalida(Proyecto $proyecto, array $items, ?string $entregadoPor = null, ?string $entregadoA = null): SalidaInventario
{
    $entregador = $entregadoPor ?? Usuario::factory()->create()->usuario;
    $receptor   = $entregadoA   ?? Usuario::factory()->create()->usuario;

    $salida = SalidaInventario::forceCreate([
        'nombre_proyecto'   => $proyecto->nombre_proyecto,
        'entregado_por'     => $entregador,
        'entregado_a'       => $receptor,
        'fecha_hora_salida' => now(),
    ]);

    foreach ($items as $item) {
        \App\Models\SalidaDetalle::forceCreate([
            'salida_id'     => $salida->id,
            'inventario_id' => $item['inventario_id'],
            'cantidad'      => $item['cantidad'],
        ]);
    }

    return $salida;
}

// ── Tests ────────────────────────────────────────────────────────

test('test_responsablesPorProyecto_devuelve_salidas_con_evidencias', function () {
    $proyecto = trazProyecto('traz-resp-' . uniqid());
    $inv      = trazInventario();

    // Los usuarios deben existir antes de la salida (FK constraints).
    $entregador = Usuario::factory()->create(['usuario' => 'lino']);
    $receptor   = Usuario::factory()->create(['usuario' => 'cliente-x']);
    $subidor    = Usuario::factory()->create(['usuario' => 'juan']);

    $salida = trazSalida(
        $proyecto,
        [['inventario_id' => $inv->id, 'cantidad' => 1]],
        $entregador->usuario,
        $receptor->usuario
    );

    EvidenciaEntrega::create([
        'salida_id'    => $salida->id,
        'proyecto_id'  => $proyecto->id,
        'archivo_path' => 'evidencias/' . $proyecto->id . '/a.jpg',
        'subido_por'   => $subidor->usuario,
    ]);

    $resp = app(TrazabilidadService::class)->responsablesPorProyecto($proyecto);

    expect($resp)->toHaveCount(1);
    expect($resp[0]['salida_id'])->toBe($salida->id);
    expect($resp[0]['entregado_por'])->toBe('lino');
    expect($resp[0]['entregado_a'])->toBe('cliente-x');
    expect($resp[0]['evidencias_count'])->toBe(1);
});

test('test_registrarResolucion_persiste_en_bd', function () {
    $proyecto = trazProyecto('traz-resol-' . uniqid());
    $inv      = trazInventario();

    $disc = TrazabilidadDiscrepancia::create([
        'proyecto_id'           => $proyecto->id,
        'inventario_id'         => $inv->id,
        'tipo'                  => 'faltante',
        'cantidad_discrepancia' => 2,
        'estado'                => 'abierta',
        'detectado_en'          => now()->subDay(),
    ]);

    $resuelta = app(TrazabilidadService::class)->registrarResolucion(
        $disc->id,
        'maria',
        [
            'notas'                       => 'ajustada salida #55',
            'corregido_por_salida_id'     => 55,
            'corregido_por_cotizacion_id' => null,
        ]
    );

    expect($resuelta)->toBeInstanceOf(TrazabilidadDiscrepancia::class);
    expect($resuelta->estado)->toBe('resuelta');
    expect($resuelta->resuelto_por)->toBe('maria');
    expect($resuelta->resuelto_en)->not->toBeNull();
    expect($resuelta->notas_resolucion)->toBe('ajustada salida #55');
    expect($resuelta->corregido_por_salida_id)->toBe(55);
    expect($resuelta->corregido_por_cotizacion_id)->toBeNull();

    $fresh = TrazabilidadDiscrepancia::find($disc->id);
    expect($fresh->estado)->toBe('resuelta');
    expect($fresh->resuelto_por)->toBe('maria');
});

test('test_registrarResolucion_sin_datos_opcionales_funciona', function () {
    $proyecto = trazProyecto('traz-resol-min-' . uniqid());
    $inv      = trazInventario();

    $disc = TrazabilidadDiscrepancia::create([
        'proyecto_id'           => $proyecto->id,
        'inventario_id'         => $inv->id,
        'tipo'                  => 'sobrante',
        'cantidad_discrepancia' => 1,
        'estado'                => 'abierta',
        'detectado_en'          => now(),
    ]);

    $resuelta = app(TrazabilidadService::class)->registrarResolucion($disc->id, 'pedro', []);

    expect($resuelta->estado)->toBe('resuelta');
    expect($resuelta->resuelto_por)->toBe('pedro');
    expect($resuelta->notas_resolucion)->toBeNull();
    expect($resuelta->corregido_por_salida_id)->toBeNull();
    expect($resuelta->corregido_por_cotizacion_id)->toBeNull();
});