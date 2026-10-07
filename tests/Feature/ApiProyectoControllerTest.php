<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Proyecto;
use App\Models\Usuario;
use App\Models\Instalacion;

class ApiProyectoControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Usuario $admin;

        protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Usuario::factory()->create();

        // Los estatus son FK de instalaciones.estatus_instalacion
        // La BD de testing está vacía → crearlos aquí.
        foreach (['en_proceso', 'preparacion', 'Programada'] as $est) {
            \App\Models\Estatus::firstOrCreate(
                ['estatus' => $est],
                ['tipo'    => 'instalacion']
            );
        }
    }
    private function actingAsAdmin(): Usuario
    {
        $this->actingAs($this->admin, 'sanctum');
        return $this->admin;
    }

    #[Test]
    public function index_no_falla(): void
    {
        Proyecto::factory()->count(3)->create();
        $this->actingAsAdmin();

        $this->getJson('/api/proyectos')
            ->assertOk()
            ->assertJson(['success' => true, 'total' => 3]);
    }

    #[Test]
    public function show_no_falla(): void
    {
        $proyecto = Proyecto::factory()->create();
        $this->actingAsAdmin();

        $this->getJson("/api/proyectos/{$proyecto->id}")
            ->assertOk()
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function store_crea_proyecto_con_nombre_libre_sin_exists_ventas(): void
    {
        $this->actingAsAdmin();

        $nombre = 'proyecto-test-' . uniqid();
        $resp = $this->postJson('/api/proyectos', [
            'nombre_proyecto'    => $nombre,
            'correo_electronico' => 'test@example.com',
        ]);

        $resp->assertStatus(201)
             ->assertJson(['success' => true]);

        $this->assertDatabaseHas('proyectos', ['nombre_proyecto' => $nombre]);
    }

    #[Test]
    public function update_con_mismo_nombre_no_falla(): void
    {
        $proyecto = Proyecto::factory()->create();
        $this->actingAsAdmin();

        // Bug histórico: unique sin coma → SQL buscaba columna "nombre_proyecto{$id}"
        $this->putJson("/api/proyectos/{$proyecto->id}", [
            'nombre_proyecto' => $proyecto->nombre_proyecto,
        ])->assertOk()->assertJson(['success' => true]);
    }

    #[Test]
    public function buscar_no_es_shadowed_por_show(): void
    {
        Proyecto::factory()->create(['nombre_proyecto' => 'PALOMAR-3BIS']);
        $this->actingAsAdmin();

        // Si el orden de rutas está mal, esto llama show('buscar') → 404
        $this->getJson('/api/proyectos/buscar?q=PALOMAR')
            ->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonFragment(['nombre_proyecto' => 'PALOMAR-3BIS']);
    }

    #[Test]
    public function resumen_no_es_shadowed_por_show(): void
    {
        Proyecto::factory()->count(2)->create();
        $this->actingAsAdmin();

        $this->getJson('/api/proyectos/resumen')
            ->assertOk()
            ->assertJsonStructure(['success', 'data' => ['total', 'por_estatus']])
            ->assertJsonPath('data.total', 2);
    }

    #[Test]
    public function recientes_no_es_shadowed_por_show(): void
    {
        Proyecto::factory()->count(3)->create();
        $this->actingAsAdmin();

        $this->getJson('/api/proyectos/recientes?limit=2')
            ->assertOk()
            ->assertJson(['success' => true, 'total' => 2]);
    }

    #[Test]
    public function byStatus_filtra_por_estatus_instalacion(): void
    {
        // Proyecto A → instalación en_proceso
        $proyectoA = Proyecto::factory()->create();
        Instalacion::factory()->create([
            'nombre_proyecto'     => $proyectoA->nombre_proyecto,
            'estatus_instalacion' => 'en_proceso',
        ]);

        // Proyecto B → instalación preparacion (no debe aparecer)
        $proyectoB = Proyecto::factory()->create();
        Instalacion::factory()->create([
            'nombre_proyecto'     => $proyectoB->nombre_proyecto,
            'estatus_instalacion' => 'preparacion',
        ]);

        $this->actingAsAdmin();

        $resp = $this->getJson('/api/proyectos/estatus/en_proceso')
            ->assertOk()
            ->assertJson(['success' => true]);

        $nombres = collect($resp->json('data'))->pluck('nombre_proyecto');
        $this->assertTrue($nombres->contains($proyectoA->nombre_proyecto));
        $this->assertFalse($nombres->contains($proyectoB->nombre_proyecto));
    }

    #[Test]
    public function instalador_solo_ve_sus_proyectos_via_pivote(): void
    {
        // Dos instaladores distintos
        $instalador1 = Usuario::factory()->instalador()->create();
        $instalador2 = Usuario::factory()->instalador()->create();

        // Dos proyectos
        $proyectoA = Proyecto::factory()->create();
        $proyectoB = Proyecto::factory()->create();

        // Una instalación por proyecto
        $inst1 = Instalacion::factory()->create([
            'nombre_proyecto' => $proyectoA->nombre_proyecto,
        ]);
        $inst2 = Instalacion::factory()->create([
            'nombre_proyecto' => $proyectoB->nombre_proyecto,
        ]);

        // Asignar SOLO instalador1 → proyectoA, vía pivote
        // (la factory no puebla el pivote, ver Q-101)
        $inst1->instaladores()->attach($instalador1->usuario);
        $inst2->instaladores()->attach($instalador2->usuario);

        // Autenticar como instalador1
        $this->actingAs($instalador1, 'sanctum');

        $resp = $this->getJson('/api/proyectos')->assertOk();
        $nombres = collect($resp->json('data'))->pluck('nombre_proyecto');

        $this->assertTrue(
            $nombres->contains($proyectoA->nombre_proyecto),
            "Instalador1 debe ver proyectoA"
        );
        $this->assertFalse(
            $nombres->contains($proyectoB->nombre_proyecto),
            "Instalador1 NO debe ver proyectoB"
        );
    }

    #[Test]
    public function download_file_de_proyecto_sin_archivo_devuelve_404(): void
    {
        $proyecto = Proyecto::factory()->create(['propuesta_economica' => null]);
        $this->actingAsAdmin();

        $this->getJson("/api/proyectos/{$proyecto->id}/archivo/propuesta_economica")
            ->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    #[Test]
    public function existe_devuelve_true_si_el_proyecto_existe(): void
    {
        $proyecto = Proyecto::factory()->create(['nombre_proyecto' => 'EXISTE-123']);
        $this->actingAsAdmin();

        $this->getJson('/api/proyectos/existe/EXISTE-123')
            ->assertOk()
            ->assertJson(['success' => true, 'exists' => true]);

        $this->getJson('/api/proyectos/existe/NO-EXISTE-999')
            ->assertOk()
            ->assertJson(['success' => true, 'exists' => false]);
    }
}
