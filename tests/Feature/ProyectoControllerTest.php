<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\DevolucionDetalle;
use App\Models\DevolucionInventario;
use App\Models\Inventario;
use App\Models\Proyecto;
use App\Models\SalidaDetalle;
use App\Models\SalidaInventario;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProyectoControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Usuario $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Usuario real — requerido por las FK:
        //   proyectos.modificado_por
        //   cotizaciones.creado_por
        //   salidas_inventario.entregado_por / entregado_a
        //   devoluciones_inventario.devuelto_por / recibido_por
        $this->admin = Usuario::factory()->create();
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function sesionAdmin(): array
    {
        return [
            'user_id'      => $this->admin->id,
            'user_usuario' => $this->admin->usuario,
            'user_rol'     => 'Administrador',
        ];
    }

    private function crearProyecto(string $nombre = 'proyecto-test'): Proyecto
    {
        Venta::factory()->create(['nombre_proyecto' => $nombre]);

        return Proyecto::create([
            'nombre_proyecto'    => $nombre,
            'correo_electronico' => 'test@test.com',
            'modificado_por'     => $this->admin->usuario,
        ]);
    }

    private function crearCotizacionAprobada(Proyecto $p, Inventario $inv, int $cantidad): void
    {
        $cliente = Cliente::factory()->create();

        $cot = Cotizacion::create([
            'folio'         => 'COT-' . uniqid(),
            'cliente_id'    => $cliente->id,
            'proyecto_id'   => $p->id,
            'fecha_emision' => now(),
            'moneda'        => 'MXN',
            'estatus'       => 'aprobada',
            'creado_por'    => $this->admin->usuario,
        ]);

        CotizacionDetalle::create([
            'cotizacion_id'   => $cot->id,
            'inventario_id'   => $inv->id,
            'descripcion'     => $inv->descripcion,
            'cantidad'        => $cantidad,
            'precio_unitario' => 100,
            'importe'         => 100 * $cantidad,
        ]);
    }

    /**
     * Crea una salida de inventario + detalle.
     *
     * Nota: `productos` es una columna LEGACY que existe en testing
     * (longtext NOT NULL sin default) pero NO en dev. El modelo no la
     * declara en $fillable a propósito (evitar "Unknown column" en dev).
     * Ver Q-45 en pendientes-*.txt.
     */
    private function crearSalida(string $proyecto, Inventario $inv, int $cantidad): SalidaInventario
    {
                    // ⚠️ forceCreate (no create): `productos` NO está en $fillable
        // del modelo (evita "Unknown column" en dev, ver Q-45), pero SÍ
        // existe en testing como NOT NULL sin default. forceCreate
        // bypassea el filtro de $fillable para que el atributo llegue a BD.
        // Valor '[]' = JSON válido por el CHECK json_valid() del schema.
        $salida = SalidaInventario::forceCreate([
            'nombre_proyecto'   => $proyecto,
            'entregado_por'     => $this->admin->usuario,
            'entregado_a'       => $this->admin->usuario,
            'fecha_hora_salida' => now(),
            'productos'         => '[]',
        ]);

        SalidaDetalle::create([
            'salida_id'     => $salida->id,
            'inventario_id' => $inv->id,
            'cantidad'      => $cantidad,
        ]);

        return $salida;
    }

    // ------------------------------------------------------------------
    // Tests
    // ------------------------------------------------------------------

    public function test_proyecto_sin_cotizacion_muestra_seccion_vacia(): void
    {
        $proyecto = $this->crearProyecto('p-sin-cot');

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $proyecto->id))
            ->assertOk()
            ->assertSee('Trazabilidad de Materiales')
            ->assertSee('No hay cotizaciones aprobadas');
    }

    public function test_cotizacion_aprobada_cuenta_como_vendido(): void
    {
        $p   = $this->crearProyecto('p-vendido');
        $inv = Inventario::factory()->create(['descripcion' => 'Producto Test Vendido']);
        $this->crearCotizacionAprobada($p, $inv, 5);

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $p->id))
            ->assertOk()
            ->assertSee('Producto Test Vendido');
    }

    public function test_cotizacion_enviada_no_cuenta_como_vendido(): void
    {
        $p       = $this->crearProyecto('p-enviada');
        $inv     = Inventario::factory()->create(['descripcion' => 'Solo Enviada Producto']);
        $cliente = Cliente::factory()->create();

        $cot = Cotizacion::create([
            'folio'         => 'COT-' . uniqid(),
            'cliente_id'    => $cliente->id,
            'proyecto_id'   => $p->id,
            'fecha_emision' => now(),
            'moneda'        => 'MXN',
            'estatus'       => 'enviada',
            'creado_por'    => $this->admin->usuario,
        ]);

        CotizacionDetalle::create([
            'cotizacion_id'   => $cot->id,
            'inventario_id'   => $inv->id,
            'descripcion'     => $inv->descripcion,
            'cantidad'        => 5,
            'precio_unitario' => 100,
            'importe'         => 500,
        ]);

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $p->id))
            ->assertOk()
            ->assertDontSee('Solo Enviada Producto');
    }

    public function test_salida_se_registra_como_entregado(): void
    {
        $p   = $this->crearProyecto('p-salida');
        $inv = Inventario::factory()->create(['descripcion' => 'Material Salida X']);

        $this->crearSalida('p-salida', $inv, 3);

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $p->id))
            ->assertOk()
            ->assertSee('Material Salida X');
    }

    public function test_devolucion_ajusta_el_neto(): void
    {
        $p   = $this->crearProyecto('p-devol');
        $inv = Inventario::factory()->create(['descripcion' => 'Material Devolucion Y']);

        $this->crearSalida('p-devol', $inv, 10);

        // forceCreate: `productos` NO está en $fillable (Q-45), pero
        // SÍ es NOT NULL sin default en testing. Mismo patrón que
        // salidas_inventario.
        $dev = DevolucionInventario::forceCreate([
            'nombre_proyecto'       => 'p-devol',
            'devuelto_por'          => $this->admin->usuario,
            'recibido_por'          => $this->admin->usuario,
            'fecha_hora_devolucion' => now(),
            'productos'             => '[]',
        ]);

        DevolucionDetalle::create([
            'devolucion_id' => $dev->id,
            'inventario_id' => $inv->id,
            'cantidad'      => 2,
        ]);

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $p->id))
            ->assertOk()
            ->assertSee('Material Devolucion Y');
    }

    public function test_salida_de_otro_proyecto_no_contamina(): void
    {
        $p   = $this->crearProyecto('p-aislado-a');
        // p-aislado-b debe existir: salidas_inventario tiene FK a ventas
        // por nombre_proyecto, y crearSalida no crea la venta.
        $pB  = $this->crearProyecto('p-aislado-b');
        $inv = Inventario::factory()->create(['descripcion' => 'Material Ajeno B']);

        $this->crearSalida('p-aislado-b', $inv, 7);

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $p->id))
            ->assertOk()
            ->assertDontSee('Material Ajeno B');
    }

    public function test_mano_de_obra_con_inventario_null_se_ignora(): void
    {
        $p       = $this->crearProyecto('p-mano-obra');
        $cliente = Cliente::factory()->create();

        $cot = Cotizacion::create([
            'folio'         => 'COT-' . uniqid(),
            'cliente_id'    => $cliente->id,
            'proyecto_id'   => $p->id,
            'fecha_emision' => now(),
            'moneda'        => 'MXN',
            'estatus'       => 'aprobada',
            'creado_por'    => $this->admin->usuario,
        ]);

        CotizacionDetalle::create([
            'cotizacion_id'   => $cot->id,
            'inventario_id'   => null,
            'descripcion'     => 'mano de obra test 1234',
            'cantidad'        => 1,
            'precio_unitario' => 2500,
            'importe'         => 2500,
        ]);

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.show', $p->id))
            ->assertOk()
            ->assertDontSee('mano de obra test 1234');
    }

    public function test_salida_pdf_inexistente_devuelve_404(): void
    {
        $p = $this->crearProyecto('p-pdf-null');

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.salida-pdf', $p->id))
            ->assertNotFound();
    }

    public function test_propuesta_pdf_inexistente_devuelve_404(): void
    {
        $p = $this->crearProyecto('p-propuesta-null');

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.propuesta-pdf', $p->id))
            ->assertNotFound();
    }

    public function test_as_built_inexistente_devuelve_404(): void
    {
        $p = $this->crearProyecto('p-as-built-null');

        $this->withSession($this->sesionAdmin())
            ->get(route('proyectos.as-built', $p->id))
            ->assertNotFound();
    }
}