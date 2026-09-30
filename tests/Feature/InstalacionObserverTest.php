<?php

use App\Models\Estatus;
use App\Models\Instalacion;
use App\Models\Recordatorio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // El catálogo de testing está incompleto (falta en_proceso, entrega).
    // Sembramos los que el Observer usa para que las FK no revienten.
    foreach (['pendiente', 'en_proceso', 'completada', 'entrega', 'cancelada'] as $e) {
        Estatus::firstOrCreate(
            ['estatus' => $e],
            ['tipo'    => 'instalacion']
        );
    }
});

function crearAdmin(): Usuario
{
    return Usuario::factory()->admin()->create();
}

function contarAdmins(): int
{
    return Usuario::where('rol', 'Administrador')->count();
}

// ---------------------------------------------------------------------------
// Guards — no-op
// ---------------------------------------------------------------------------

test('update sin cambio de estatus_instalacion no crea recordatorios', function () {
    crearAdmin();
    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'pendiente']);

    // Toca un campo cualquiera que NO sea estatus (no rompe FKs).
    $instalacion->update(['direccion' => 'dirección nueva']);

    expect(Recordatorio::count())->toBe(0);
});

test('cambio a estatus fuera de AVISOS no crea recordatorios', function () {
    crearAdmin();
    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'pendiente']);

    $instalacion->update(['estatus_instalacion' => 'en_proceso']);

    expect(Recordatorio::count())->toBe(0);
});

test('sin admins no crea recordatorios ni lanza excepción', function () {
    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'pendiente']);

    // VentaFactory crea un admin por default. Los borramos.
    Usuario::where('rol', 'Administrador')->update(['rol' => 'Instalador']);

    $instalacion->update(['estatus_instalacion' => 'completada']);

    expect(Recordatorio::count())->toBe(0);
});

// ---------------------------------------------------------------------------
// Happy path
// ---------------------------------------------------------------------------

test('pasar a completada crea un recordatorio por admin con campos correctos', function () {
    $admin1 = crearAdmin();
    $admin2 = crearAdmin();

    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'en_proceso']);

    // Contar TODOS los admins (incluye el que creó VentaFactory transitivamente).
    $totalAdmins = contarAdmins();

    $instalacion->update(['estatus_instalacion' => 'completada']);

    expect(Recordatorio::count())->toBe($totalAdmins);

    $r = Recordatorio::where('usuario_id', $admin1->id)->first();
    expect($r)->not->toBeNull();
    expect($r->tipo)->toBe('sistema');
    expect($r->canal)->toBe(['telegram', 'web']);
    expect($r->estatus)->toBe('pendiente');
    expect($r->intentos)->toBe(0);
    expect($r->recordable_type)->toBe(Instalacion::class);
    expect($r->recordable_id)->toBe($instalacion->id);
    expect($r->metadata['estatus_previo'])->toBe('en_proceso');
    expect($r->metadata['estatus_nuevo'])->toBe('completada');
    expect($r->metadata['instalacion_id'])->toBe($instalacion->id);

    expect(Recordatorio::where('usuario_id', $admin2->id)->exists())->toBeTrue();
});

test('pasar a entrega cierra previos tipo=sistema pendientes y crea nuevos', function () {
    $admin = crearAdmin();
    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'completada']);

    $previo = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => Instalacion::class,
        'recordable_id'   => $instalacion->id,
        'estatus'         => 'pendiente',
        'tipo'            => 'sistema',
    ]);

    $instalacion->update(['estatus_instalacion' => 'entrega']);

    expect($previo->fresh()->estatus)->toBe('completado');

    $nuevos = Recordatorio::where('id', '!=', $previo->id)->get();
    expect($nuevos->count())->toBeGreaterThanOrEqual(1);
    foreach ($nuevos as $n) {
        expect($n->estatus)->toBe('pendiente');
        expect($n->metadata['estatus_nuevo'])->toBe('entrega');
    }
});

test('pasar a cancelada cierra previos y crea nuevos', function () {
    $admin = crearAdmin();
    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'en_proceso']);

    $previo = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => Instalacion::class,
        'recordable_id'   => $instalacion->id,
        'estatus'         => 'pendiente',
        'tipo'            => 'sistema',
    ]);

    $instalacion->update(['estatus_instalacion' => 'cancelada']);

    expect($previo->fresh()->estatus)->toBe('completado');

    $nuevos = Recordatorio::where('id', '!=', $previo->id)->get();
    expect($nuevos->count())->toBeGreaterThanOrEqual(1);
    foreach ($nuevos as $n) {
        expect($n->metadata['estatus_nuevo'])->toBe('cancelada');
    }
});

// ---------------------------------------------------------------------------
// Aislamiento
// ---------------------------------------------------------------------------

test('al cerrar previos NO toca otros tipos/recordables/estatus', function () {
    $admin = crearAdmin();
    $instalacion = Instalacion::factory()->create(['estatus_instalacion' => 'completada']);

    $target = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => Instalacion::class,
        'recordable_id'   => $instalacion->id,
        'estatus'         => 'pendiente',
        'tipo'            => 'sistema',
    ]);
    $otroTipo = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => Instalacion::class,
        'recordable_id'   => $instalacion->id,
        'estatus'         => 'pendiente',
        'tipo'            => 'general',
    ]);
    $otroId = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => Instalacion::class,
        'recordable_id'   => 999999,
        'estatus'         => 'pendiente',
        'tipo'            => 'sistema',
    ]);
    $otroType = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => 'App\\Models\\OtraCosa',
        'recordable_id'   => $instalacion->id,
        'estatus'         => 'pendiente',
        'tipo'            => 'sistema',
    ]);
    $yaCompletado = Recordatorio::factory()->create([
        'usuario_id'      => $admin->id,
        'recordable_type' => Instalacion::class,
        'recordable_id'   => $instalacion->id,
        'estatus'         => 'completado',
        'tipo'            => 'sistema',
    ]);

    $instalacion->update(['estatus_instalacion' => 'entrega']);

    expect($target->fresh()->estatus)->toBe('completado');
    expect($otroTipo->fresh()->estatus)->toBe('pendiente');
    expect($otroId->fresh()->estatus)->toBe('pendiente');
    expect($otroType->fresh()->estatus)->toBe('pendiente');
    expect($yaCompletado->fresh()->estatus)->toBe('completado');

    // Solo los admins reciben notificación nueva → contar los "nuevos" excluyendo
    // los 5 previos que armamos a mano.
    $excluir = [$target->id, $otroTipo->id, $otroId->id, $otroType->id, $yaCompletado->id];
    $nuevos = Recordatorio::whereNotIn('id', $excluir)->get();

    expect($nuevos->count())->toBe(contarAdmins());
    foreach ($nuevos as $n) {
        expect($n->metadata['estatus_nuevo'])->toBe('entrega');
    }
});