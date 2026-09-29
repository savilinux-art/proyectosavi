<?php

use App\Models\Recordatorio;
use App\Services\Recordatorios\Procesador;
use App\Services\Recordatorios\Notificadores\NotificadorInterface;
use App\Services\Recordatorios\Notificadores\TelegramNotificador;
use App\Services\Recordatorios\Notificadores\EmailNotificador;
use App\Services\Recordatorios\Notificadores\WebNotificador;
use Carbon\Carbon;

/**
 * Registra mocks de los 3 notificadores en el container de Laravel.
 * El Procesador los resuelve en su constructor vía app(), así que
 * los mocks deben estar registrados ANTES de instanciarlo.
 *
 * $retornosPorCanal: ['telegram' => true, 'email' => false, 'web' => false]
 * Cualquier canal ausente del array usa false por default.
 */
if (!function_exists('mockNotificadores')) {
    function mockNotificadores(array $retornosPorCanal): array
    {
        $map = [
            'telegram' => TelegramNotificador::class,
            'email'    => EmailNotificador::class,
            'web'      => WebNotificador::class,
        ];

        $mocks = [];
        foreach ($map as $canal => $clase) {
            $mock = \Mockery::mock(NotificadorInterface::class);
            $mock->shouldReceive('canal')->andReturn($canal)->byDefault();
            $mock->shouldReceive('enviar')
                ->andReturn($retornosPorCanal[$canal] ?? false)
                ->byDefault();
            app()->instance($clase, $mock);
            $mocks[$canal] = $mock;
        }

        return $mocks;
    }
}

// Congelar el tiempo para que los factories y las aserciones sean deterministas.
beforeEach(function () {
    Carbon::setTestNow('2026-01-15 10:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

// ============================================================================
// Sin pendientes
// ============================================================================

test('sin pendientes devuelve enviados=0 fallidos=0', function () {
    mockNotificadores([]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(0);
    expect($resultado['fallidos'])->toBe(0);
});

// ============================================================================
// Recordatorio normal
// ============================================================================

test('normal con 1 canal OK marca enviado', function () {
    $recordatorio = Recordatorio::factory()->general()->create();

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(1);
    expect($resultado['fallidos'])->toBe(0);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('enviado');
    expect($recordatorio->enviado_at)->not->toBeNull();
    expect($recordatorio->intentos)->toBe(1);
    expect($recordatorio->ultimo_error)->toBeNull();
});

test('normal con todos los canales fallando incrementa intentos y queda pendiente', function () {
    $recordatorio = Recordatorio::factory()
        ->general()
        ->porCanal(['telegram', 'email', 'web'])
        ->create();

    mockNotificadores(['telegram' => false, 'email' => false, 'web' => false]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(0);
    expect($resultado['fallidos'])->toBe(1);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('pendiente');
    expect($recordatorio->intentos)->toBe(1);
    expect($recordatorio->ultimo_error)->not->toBeNull();
    expect($recordatorio->ultimo_error)->toContain('Ningún canal tuvo éxito');
});

test('normal con 3er intento fallido pasa a estatus error', function () {
    $recordatorio = Recordatorio::factory()
        ->general()
        ->conIntentos(2)
        ->create();

    mockNotificadores(['telegram' => false]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['fallidos'])->toBe(1);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('error');
    expect($recordatorio->intentos)->toBe(3);
});

test('solo llama a los notificadores del canal indicado', function () {
    Recordatorio::factory()
        ->general()
        ->porCanal(['telegram'])
        ->create();

    $mocks = mockNotificadores([
        'telegram' => true,
        'email'    => false,
        'web'      => false,
    ]);

    // Sobrescribo la expectativa: email y web NO deben ser llamados.
    $mocks['email']->shouldNotReceive('enviar');
    $mocks['web']->shouldNotReceive('enviar');

    app(Procesador::class)->procesarPendientes();

    // Mockery verifica expectativas al tearDown.
    expect(true)->toBeTrue();
});

// ============================================================================
// Certificados
// ============================================================================

test('certificado con avisos pendientes reagenda y queda pendiente', function () {
    $recordatorio = Recordatorio::factory()
        ->certificado()             // vence en 30 días, avisos [0,1,3,7,15]
        ->create();

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(1);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('pendiente');
    expect($recordatorio->intentos)->toBe(0);        // reagendado → reset
    expect($recordatorio->enviado_at)->not->toBeNull();
    expect($recordatorio->ultimo_error)->toBeNull();

    // Siguiente aviso = 15 días antes del vencimiento
    $esperado = now()->addDays(30)->startOfDay()->subDays(15)->setTime(9, 0, 0);
    expect($recordatorio->fecha_hora_programada->equalTo($esperado))->toBeTrue();
});

test('certificado en último aviso cierra ciclo como completado', function () {
    $recordatorio = Recordatorio::factory()
        ->certificadoVencidoHoy()
        ->create();

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(1);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('completado');
    expect($recordatorio->intentos)->toBe(1);        // no reagendado → +1
    expect($recordatorio->enviado_at)->not->toBeNull();
});

// ============================================================================
// Recurrencia
// ============================================================================

test('recurrente diario crea clon con fecha +1 día', function () {
    $original = Recordatorio::factory()
        ->general()
        ->recurrente('diario')
        ->create();

    $fechaOriginal = $original->fecha_hora_programada->copy();

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    // Original marcado como enviado
    $original->refresh();
    expect($original->estatus)->toBe('enviado');

    // Debe existir un clon con estatus pendiente
    $clones = Recordatorio::where('id', '!=', $original->id)
        ->where('titulo', $original->titulo)
        ->get();

    expect($clones)->toHaveCount(1);

    $clon = $clones->first();
    expect($clon->estatus)->toBe('pendiente');
    expect($clon->intentos)->toBe(0);
    expect($clon->enviado_at)->toBeNull();
    expect($clon->fecha_hora_programada->equalTo($fechaOriginal->copy()->addDay()))->toBeTrue();
});

// ============================================================================
// Certificados — casos borde de reagendado
// ============================================================================

test('certificado sin cert_avisos_dias cierra ciclo como completado', function () {
    $recordatorio = Recordatorio::factory()
        ->certificado()
        ->create(['cert_avisos_dias' => null]);

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(1);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('completado');
    expect($recordatorio->intentos)->toBe(1);
});

test('certificado sin cert_fecha_vencimiento cierra ciclo como completado', function () {
    $recordatorio = Recordatorio::factory()
        ->certificado()
        ->create(['cert_fecha_vencimiento' => null]);

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(1);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('completado');
    expect($recordatorio->intentos)->toBe(1);
});

test('certificado que vence en 10 días reagenda al aviso de 7', function () {
    $recordatorio = Recordatorio::factory()
        ->certificado()
        ->create(['cert_fecha_vencimiento' => now()->addDays(10)->toDateString()]);

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('pendiente');

    // venc = 2026-01-25, avisos < 10: [0,1,3,7], max = 7
    $esperado = now()->addDays(10)->startOfDay()->subDays(7)->setTime(9, 0, 0);
    expect($recordatorio->fecha_hora_programada->equalTo($esperado))->toBeTrue();
});

test('certificado que vence en 2 días reagenda al aviso de 1', function () {
    $recordatorio = Recordatorio::factory()
        ->certificado()
        ->create(['cert_fecha_vencimiento' => now()->addDays(2)->toDateString()]);

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('pendiente');

    // avisos < 2: [0,1], max = 1
    $esperado = now()->addDays(2)->startOfDay()->subDays(1)->setTime(9, 0, 0);
    expect($recordatorio->fecha_hora_programada->equalTo($esperado))->toBeTrue();
});

test('certificado que vence exactamente en 15 días reagenda al aviso de 7 (borde)', function () {
    // La condición es $a < $diasRestantes (estricto). Si faltan exactamente 15,
    // el aviso de 15 NO aplica → salta al 7. Documentar este comportamiento.
    $recordatorio = Recordatorio::factory()
        ->certificado()
        ->create(['cert_fecha_vencimiento' => now()->addDays(15)->toDateString()]);

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('pendiente');

    $esperado = now()->addDays(15)->startOfDay()->subDays(7)->setTime(9, 0, 0);
    expect($recordatorio->fecha_hora_programada->equalTo($esperado))->toBeTrue();
});

// ============================================================================
// Recurrencia — no clonar en casos que no corresponden
// ============================================================================

test('certificado recurrente NO clona', function () {
    $recordatorio = Recordatorio::factory()
        ->certificado()
        ->recurrente('mensual')
        ->create();

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    $total = Recordatorio::where('id', '!=', $recordatorio->id)->count();
    expect($total)->toBe(0);
});

test('recurrente una_vez NO clona', function () {
    $recordatorio = Recordatorio::factory()
        ->general()
        ->recurrente('una_vez')
        ->create();

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    $total = Recordatorio::where('id', '!=', $recordatorio->id)->count();
    expect($total)->toBe(0);
});

test('recurrente semanal clona con fecha +7 días', function () {
    $original = Recordatorio::factory()
        ->general()
        ->recurrente('semanal')
        ->create();

    $fechaOriginal = $original->fecha_hora_programada->copy();

    mockNotificadores(['telegram' => true]);

    app(Procesador::class)->procesarPendientes();

    $clones = Recordatorio::where('id', '!=', $original->id)->get();
    expect($clones)->toHaveCount(1);

    $clon = $clones->first();
    expect($clon->estatus)->toBe('pendiente');
    expect($clon->fecha_hora_programada->equalTo($fechaOriginal->copy()->addWeek()))->toBeTrue();
});

// ============================================================================
// Canales — éxito parcial y defaults
// ============================================================================

test('normal con 2 canales OK y 1 fallando guarda ultimo_error del fallido', function () {
    $recordatorio = Recordatorio::factory()
        ->general()
        ->porCanal(['telegram', 'email', 'web'])
        ->create();

    mockNotificadores(['telegram' => true, 'email' => false, 'web' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(1);
    expect($resultado['fallidos'])->toBe(0);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('enviado');
    expect($recordatorio->intentos)->toBe(1);
    expect($recordatorio->ultimo_error)->toBe('Falló en: email');
});

// NOTA: el caso "canal = null" no aplica — la columna es NOT NULL con
// DEFAULT '["telegram"]'. El operador ?? ['telegram'] en Procesador es
// defensivo por si el schema cambia en el futuro, pero no es alcanzable hoy.

// ============================================================================
// Bordes — scope y múltiples pendientes
// ============================================================================

test('recordatorio con intentos=3 NO se procesa (scope lo excluye)', function () {
    $recordatorio = Recordatorio::factory()
        ->general()
        ->conIntentos(3)
        ->create();

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(0);
    expect($resultado['fallidos'])->toBe(0);

    $recordatorio->refresh();
    expect($recordatorio->estatus)->toBe('pendiente');
    expect($recordatorio->intentos)->toBe(3);
    expect($recordatorio->enviado_at)->toBeNull();
});

test('múltiples pendientes se procesan todos', function () {
    $r1 = Recordatorio::factory()->general()->create();
    $r2 = Recordatorio::factory()->general()->create();
    $r3 = Recordatorio::factory()->general()->create();

    mockNotificadores(['telegram' => true]);

    $resultado = app(Procesador::class)->procesarPendientes();

    expect($resultado['enviados'])->toBe(3);
    expect($resultado['fallidos'])->toBe(0);

    $r1->refresh(); $r2->refresh(); $r3->refresh();
    expect($r1->estatus)->toBe('enviado');
    expect($r2->estatus)->toBe('enviado');
    expect($r3->estatus)->toBe('enviado');
});
