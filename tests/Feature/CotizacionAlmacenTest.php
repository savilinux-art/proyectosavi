<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Inventario;
use App\Models\Proyecto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CotizacionAlmacenTest extends TestCase
{
    use DatabaseTransactions;

    private Usuario $almacenista;

    protected function setUp(): void
    {
        parent::setUp();
        $this->almacenista = Usuario::factory()->create();
    }

    private function sesionInventarios(): array
    {
        return [
            'user_id'      => $this->almacenista->id,
            'user_usuario' => $this->almacenista->usuario,
            'user_rol'     => 'Inventarios',
        ];
    }

    private function crearCotizacionConDetalle(): array
    {
        $cliente = Cliente::factory()->create();
        Venta::factory()->create(['nombre_proyecto' => 'p-almacen']);

        $proyecto = Proyecto::create([
            'nombre_proyecto'    => 'p-almacen',
            'correo_electronico' => 'a@b.com',
            'modificado_por'     => $this->almacenista->usuario,
        ]);

        $inventario = Inventario::factory()->create([
            'descripcion' => 'NVR Test 1080p',
            'marca'       => 'Hikvision',
            'modelo'      => 'DS-7108',
        ]);

        $cot = Cotizacion::create([
            'folio'         => 'COT-' . uniqid(),
            'cliente_id'    => $cliente->id,
            'proyecto_id'   => $proyecto->id,
            'fecha_emision' => now(),
            'moneda'        => 'MXN',
            'estatus'       => 'aprobada',
            'creado_por'    => $this->almacenista->usuario,
        ]);

        CotizacionDetalle::create([
            'cotizacion_id'   => $cot->id,
            'inventario_id'   => $inventario->id,
            'descripcion'     => 'NVR Test 1080p',
            'cantidad'        => 3,
            'precio_unitario' => 1344.00,
            'importe'         => 4032.00,
        ]);

        return compact('cot', 'inventario');
    }

    public function test_almacenista_ve_la_cotizacion(): void
    {
        ['cot' => $cot] = $this->crearCotizacionConDetalle();

        $this->withSession($this->sesionInventarios())
            ->get(route('cotizaciones.almacen', $cot->id))
            ->assertOk()
            ->assertSee('NVR Test 1080p')
            ->assertSee('Hikvision')
            ->assertSee('DS-7108');
    }

    public function test_almacen_no_ve_precios(): void
    {
        ['cot' => $cot] = $this->crearCotizacionConDetalle();

        $this->withSession($this->sesionInventarios())
            ->get(route('cotizaciones.almacen', $cot->id))
            ->assertOk()
            ->assertDontSee('1344.00')
            ->assertDontSee('4032.00');
    }

    public function test_sin_sesion_redirige_a_login(): void
    {
        ['cot' => $cot] = $this->crearCotizacionConDetalle();

        $this->get(route('cotizaciones.almacen', $cot->id))
            ->assertRedirect(route('login'));
    }
}