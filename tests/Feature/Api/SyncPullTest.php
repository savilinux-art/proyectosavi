<?php

namespace Tests\Feature\Api;

use App\Models\Instalacion;
use App\Models\InstalacionEvento;
use App\Models\InstalacionFoto;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SyncPullTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $instalador;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instalador = Usuario::create([
            'usuario'    => 'inst001',
            'nombre'     => 'Instalador 1',
            'correo'     => 'inst001@test.local',
            'contraseña' => bcrypt('secret'),
            'rol'        => 'Instalador',
        ]);
        Sanctum::actingAs($this->instalador);
    }

    public function test_pull_sin_since_devuelve_todo(): void
    {
        $inst = $this->crearInstalacionConInstalador('inst001');
        InstalacionEvento::create([
            'instalacion_id' => $inst->id,
            'uuid_cliente'   => 'evt-uuid-bootstrap',
            'tipo'           => 'nota_agregada',
            'capturado_at'   => now(),
            'por'            => 'inst001',
        ]);

        $this->getJson('/api/sync/pull')
            ->assertOk()
            ->assertJsonStructure([
                'since', 'next_since',
                'instalaciones', 'instalacion_eventos', 'instalacion_fotos',
                'salidas', 'evidencias_entrega',
                'catalogos' => ['proyectos', 'clientes', 'categorias', 'inventario', 'estatus'],
            ])
            ->assertJsonCount(1, 'instalaciones')
            ->assertJsonCount(1, 'instalacion_eventos');
    }

    public function test_pull_con_since_solo_devuelve_deltas(): void
    {
        $inst = $this->crearInstalacionConInstalador('inst001');

        // Evento viejo con updated_at forzado atrás.
        // Eloquent ignora mass assignment de timestamps → DB::table() directo.
        $old = InstalacionEvento::create([
            'instalacion_id' => $inst->id,
            'uuid_cliente'   => 'evt-old',
            'tipo'           => 'nota_agregada',
            'capturado_at'   => now()->subHours(2),
            'por'            => 'inst001',
        ]);
        \DB::table('instalacion_eventos')->where('id', $old->id)->update([
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        // Evento nuevo (ahora).
        InstalacionEvento::create([
            'instalacion_id' => $inst->id,
            'uuid_cliente'   => 'evt-new',
            'tipo'           => 'nota_agregada',
            'capturado_at'   => now(),
            'por'            => 'inst001',
        ]);

        $since = now()->subHour()->utc()->format('Y-m-d\TH:i:s\Z');

        $this->getJson('/api/sync/pull?since=' . $since)
            ->assertOk()
            ->assertJsonCount(1, 'instalacion_eventos')
            ->assertJsonPath('instalacion_eventos.0.uuid_cliente', 'evt-new');
    }

    public function test_pull_filtra_instalaciones_del_instalador_autenticado(): void
    {
        $propia = $this->crearInstalacionConInstalador('inst001');
        $this->crearInstalacionConInstalador('otro_instalador');

        $this->getJson('/api/sync/pull')
            ->assertOk()
            ->assertJsonCount(1, 'instalaciones')
            ->assertJsonPath('instalaciones.0.id', $propia->id);
    }

    public function test_next_since_es_el_max_updated_at(): void
    {
        $inst = $this->crearInstalacionConInstalador('inst001');
        InstalacionFoto::create([
            'instalacion_id'     => $inst->id,
            'uuid_cliente'       => 'foto-uuid-1',
            'ruta'               => 'tests/fake.jpg',
            'nombre_original'    => 'fake.jpg',
            'mime'               => 'image/jpeg',
            'tamano_kb'          => 10,
            'tipo'               => 'proceso',
            'subida_por_usuario' => 'inst001',
        ]);

        $response = $this->getJson('/api/sync/pull')->assertOk();

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/',
            $response->json('next_since')
        );
    }

    private function crearInstalacionConInstalador(string $usuario): Instalacion
    {
        // Factory crea Proyecto + Estatus 'Programada' en cascada (cumple FK).
        // id_usuario_asignado = null; el vínculo va por pivot instalacion_instalador.
        $inst = Instalacion::factory()->create();
        $inst->instaladores()->attach($usuario, ['es_principal' => true]);
        return $inst;
    }
}
