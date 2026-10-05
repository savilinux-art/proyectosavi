<?php

namespace Tests\Feature;

use App\Models\Inventario;
use App\Models\Proyecto;
use App\Models\Usuario;
use App\Models\VentaMostrador;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VentaMostradorControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Usuario $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // UsuarioFactory default = rol 'Administrador'
        // El middleware permiso: deja pasar a Administrador siempre
        // (v22 sección 3), así que no necesitamos poblar `permisos`.
        $this->admin = Usuario::factory()->create();
    }

    private function sesion(): array
    {
        return [
            'user_id'      => $this->admin->id,
            'user_usuario' => $this->admin->usuario,
            'user_rol'     => 'Administrador',
        ];
    }

    public function test_index_muestra_listado_de_ventas(): void
    {
        VentaMostrador::factory()->create();

        $response = $this->withSession($this->sesion())
            ->get(route('ventas_mostrador.index'));

        $response->assertOk();
        $response->assertViewIs('ventas_mostrador.index');
        $response->assertViewHas('ventas');
        $response->assertViewHas('proyectos');
    }

    public function test_index_filtra_por_estado(): void
    {
        VentaMostrador::factory()->create(['estado' => 'pendiente']);
        VentaMostrador::factory()->create(['estado' => 'cancelada']);

        $response = $this->withSession($this->sesion())
            ->get(route('ventas_mostrador.index', ['estado' => 'pendiente']));

        $response->assertOk();
        $ventas = $response->viewData('ventas');
        $this->assertCount(1, $ventas);
        $this->assertSame('pendiente', $ventas->first()->estado);
    }

    public function test_create_muestra_formulario_con_proyectos_e_inventario(): void
    {
        Inventario::factory()->create(['existencia' => 10]);
        Inventario::factory()->create(['existencia' => 0]);

        $response = $this->withSession($this->sesion())
            ->get(route('ventas_mostrador.create'));

        $response->assertOk();
        $response->assertViewIs('ventas_mostrador.create');
        $response->assertViewHas('proyectos');
        $response->assertViewHas('inventario', function ($inv) {
            return $inv->count() === 1 && $inv->first()->existencia > 0;
        });
    }

    public function test_store_crea_venta_con_proyecto_existente(): void
    {
        $proyecto = Proyecto::factory()->create();
        $item = Inventario::factory()->create(['existencia' => 10]);

        $response = $this->withSession($this->sesion())
            ->post(route('ventas_mostrador.store'), [
                'proyecto_id' => $proyecto->id,
                'items' => [
                    [
                        'inventario_id'   => $item->id,
                        'cantidad'        => 3,
                        'precio_unitario' => 100,
                        'descuento'       => 10,
                    ],
                ],
            ]);

        $venta = VentaMostrador::latest('id')->first();
        $response->assertRedirect(route('ventas_mostrador.show', $venta));

        $this->assertSame('pendiente', $venta->estado);
        $this->assertEquals(270.0, (float) $venta->total);
        $this->assertEquals($proyecto->id, $venta->proyecto_id);
        $this->assertCount(1, $venta->detalles);
        $this->assertEquals(270.0, (float) $venta->detalles->first()->subtotal);
    }

    public function test_store_crea_venta_con_proyecto_nuevo(): void
    {
        $item = Inventario::factory()->create(['existencia' => 10]);

        $response = $this->withSession($this->sesion())
            ->post(route('ventas_mostrador.store'), [
                'proyecto_nuevo' => 'PROY-NUEVO-TEST-001',
                'items' => [
                    [
                        'inventario_id'   => $item->id,
                        'cantidad'        => 2,
                        'precio_unitario' => 50,
                        'descuento'       => 0,
                    ],
                ],
            ]);

        $venta = VentaMostrador::latest('id')->first();
        $response->assertRedirect(route('ventas_mostrador.show', $venta));

        $this->assertNotNull($venta->proyecto_id);
        $this->assertSame('PROY-NUEVO-TEST-001', $venta->proyecto->nombre_proyecto);
        $this->assertEquals(100.0, (float) $venta->total);
    }

    public function test_cancelar_cambia_estado_a_cancelada_si_estaba_pendiente(): void
    {
        $venta = VentaMostrador::factory()->create(['estado' => 'pendiente']);

        $response = $this->withSession($this->sesion())
            ->patch(route('ventas_mostrador.cancelar', $venta));

        $response->assertRedirect(route('ventas_mostrador.show', $venta));
        $this->assertSame('cancelada', $venta->fresh()->estado);
    }

    public function test_cambiar_estado_a_completada_desde_pendiente(): void
{
    $venta = VentaMostrador::factory()->create(['estado' => 'pendiente']);

    $this->withSession($this->sesion())
        ->patch(route('ventas_mostrador.cambiarEstado', $venta), ['estado' => 'completada'])
        ->assertRedirect(route('ventas_mostrador.show', $venta));

    $this->assertDatabaseHas('ventas_mostrador', [
        'id' => $venta->id, 'estado' => 'completada',
    ]);
}

public function test_cambiar_estado_a_cancelada_desde_pendiente(): void
{
    $venta = VentaMostrador::factory()->create(['estado' => 'pendiente']);

    $this->withSession($this->sesion())
        ->patch(route('ventas_mostrador.cambiarEstado', $venta), ['estado' => 'cancelada'])
        ->assertRedirect();

    $this->assertDatabaseHas('ventas_mostrador', [
        'id' => $venta->id, 'estado' => 'cancelada',
    ]);
}

public function test_no_puede_revertir_completada_a_pendiente(): void
{
    $venta = VentaMostrador::factory()->create(['estado' => 'completada']);

    $this->withSession($this->sesion())
        ->patch(route('ventas_mostrador.cambiarEstado', $venta), ['estado' => 'pendiente'])
        ->assertSessionHas('error');

    $this->assertDatabaseHas('ventas_mostrador', [
        'id' => $venta->id, 'estado' => 'completada',
    ]);
}

public function test_no_puede_cambiar_estado_de_cancelada(): void
{
    $venta = VentaMostrador::factory()->create(['estado' => 'cancelada']);

    $this->withSession($this->sesion())
        ->patch(route('ventas_mostrador.cambiarEstado', $venta), ['estado' => 'completada'])
        ->assertSessionHas('error');

    $this->assertDatabaseHas('ventas_mostrador', [
        'id' => $venta->id, 'estado' => 'cancelada',
    ]);
}

public function test_pdf_venta_mostrador_genera_archivo(): void
{
    $venta = VentaMostrador::factory()->create();

    $response = $this->withSession($this->sesion())
        ->get(route('ventas_mostrador.pdf', $venta));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
}
public function test_show_muestra_venta(): void
{
    $venta = VentaMostrador::factory()->create();

    $response = $this->withSession($this->sesion())
        ->get(route('ventas_mostrador.show', $venta));

    $response->assertOk();
    $response->assertViewIs('ventas_mostrador.show');
    $response->assertViewHas('ventaMostrador');
}

public function test_edit_muestra_formulario(): void
{
    $venta = VentaMostrador::factory()->create(['estado' => 'pendiente']);

    $response = $this->withSession($this->sesion())
        ->get(route('ventas_mostrador.edit', $venta));

    $response->assertOk();
    $response->assertViewIs('ventas_mostrador.edit');
    $response->assertViewHas('ventaMostrador');
}
public function test_buscar_inventario_devuelve_json(): void
{
    Inventario::factory()->create([
        'modelo'      => 'CABLE-HDMI-001',
        'descripcion' => 'Cable HDMI 2.0 5m',
        'marca'       => 'Genérico',
        'existencia'  => 10,
        'precio'      => 12.50,
    ]);

    $response = $this->withSession($this->sesion())
        ->getJson(route('ventas_mostrador.buscarInventario', ['q' => 'HDMI']));

    $response->assertOk();
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['modelo' => 'CABLE-HDMI-001']);
}

public function test_buscar_inventario_con_menos_de_2_caracteres_devuelve_vacio(): void
{
    Inventario::factory()->create([
        'modelo'     => 'CABLE-HDMI-001',
        'existencia' => 10,
    ]);

    $response = $this->withSession($this->sesion())
        ->getJson(route('ventas_mostrador.buscarInventario', ['q' => 'H']));

    $response->assertOk();
    $response->assertJsonCount(0);
}

public function test_buscar_inventario_excluye_productos_sin_existencia(): void
{
    Inventario::factory()->create([
        'modelo'      => 'CABLE-HDMI-001',
        'descripcion' => 'Cable HDMI sin stock',
        'existencia'  => 0,
    ]);

    $response = $this->withSession($this->sesion())
        ->getJson(route('ventas_mostrador.buscarInventario', ['q' => 'HDMI']));

    $response->assertOk();
    $response->assertJsonCount(0);
}

}