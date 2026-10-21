<?php

namespace Tests\Feature;

use App\Models\Instalacion;
use App\Models\InstalacionEvento;
use App\Models\Proyecto;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstalacionEventoTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function tabla_existe_con_columnas_esperadas(): void
    {
        $this->assertTrue(Schema::hasTable('instalacion_eventos'));
        $this->assertTrue(Schema::hasColumns('instalacion_eventos', [
            'id', 'instalacion_id', 'tipo',
            'latitud', 'longitud', 'precision_metros', 'fuente',
            'payload_json', 'capturado_at', 'por',
            'created_at', 'updated_at',
        ]));
    }

    #[Test]
    public function crea_evento_y_castea_correctamente(): void
    {
        $inst = $this->instalacionDePrueba();

        $evento = InstalacionEvento::create([
            'instalacion_id'   => $inst->id,
            'tipo'             => 'iniciada',
            'latitud'          => 20.6708140,
            'longitud'         => -105.2126830,
            'precision_metros' => 12,
            'fuente'           => 'gps',
            'payload_json'     => ['nota' => 'prueba'],
            'capturado_at'     => now(),
            'por'              => 'lino',
        ]);

        $this->assertNotNull($evento->id);
        $this->assertEqualsWithDelta(20.6708140, $evento->latitud, 0.0000001);
        $this->assertEqualsWithDelta(-105.2126830, $evento->longitud, 0.0000001);
        $this->assertSame(12, $evento->precision_metros);
        $this->assertSame(['nota' => 'prueba'], $evento->payload_json);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $evento->capturado_at);
    }

        #[Test]
    public function forceDelete_de_instalacion_con_eventos_falla_por_restrict(): void
    {
        $inst = $this->instalacionDePrueba();

        InstalacionEvento::create([
            'instalacion_id' => $inst->id,
            'tipo'           => 'iniciada',
            'capturado_at'   => now(),
            'por'            => 'lino',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->expectExceptionMessageMatches('/fk_instalacion_eventos_instalacion_id/');

        $inst->forceDelete();
    }

    #[Test]
    public function softDelete_de_instalacion_con_eventos_funciona_y_preserva_evidencia(): void
    {
        $inst = $this->instalacionDePrueba();

        $evento = InstalacionEvento::create([
            'instalacion_id' => $inst->id,
            'tipo'           => 'iniciada',
            'capturado_at'   => now(),
            'por'            => 'lino',
        ]);

        $inst->delete(); // soft delete, no dispara RESTRICT

        $this->assertSoftDeleted('instalaciones', ['id' => $inst->id]);
        $this->assertDatabaseHas('instalacion_eventos', ['id' => $evento->id]);
    }

    #[Test]
    public function forceDelete_de_instalacion_sin_eventos_funciona(): void
    {
        $inst = $this->instalacionDePrueba();

        $inst->forceDelete();

        $this->assertDatabaseMissing('instalaciones', ['id' => $inst->id]);
    }

   private function instalacionDePrueba(): Instalacion
{
    // 1. Usuario — FK proyectos.modificado_por → usuarios.usuario
    //    insertOrIgnore: crea solo si no existe. Idempotente.
    DB::table('usuarios')->insertOrIgnore([
        'usuario'    => 'test_user',
        'nombre'     => 'Test User',
        'correo'     => 'test@example.com',
        'rol'        => 'Instalador',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 2. Estatus — FK instalaciones.estatus_instalacion → estatus.estatus
    DB::table('estatus')->insertOrIgnore([
        'estatus'    => 'en_proceso',
        'tipo'       => 'instalacion',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 3. Proyecto — FK instalaciones.nombre_proyecto → proyectos.nombre_proyecto
    //    nombre_proyecto es UNIQUE (si no, la FK no existiría).
    //    insertOrIgnore: reusable entre tests.
    DB::table('proyectos')->insertOrIgnore([
        'nombre_proyecto' => 'PROY-TEST',
        'modificado_por'  => 'test_user',
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    // 4. Instalación — creada nueva cada test (DatabaseTransactions la revierte)
    return Instalacion::create([
        'nombre_proyecto'     => 'PROY-TEST',
        'fecha_hora_inicio'   => now(),
        'estatus_instalacion' => 'en_proceso',
        'nombre_instalacion'  => 'Instalación Test',
    ]);
}
}