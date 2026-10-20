<?php

use App\Exceptions\ProyectoNoEncontradoException;
use App\Models\Proyecto;
use App\Support\ProyectoResolver;
use Illuminate\Support\Facades\Log;

/**
 * Helper: crea un Proyecto con nombre controlado.
 * Si ProyectoFactory::definition() pre-crea una Venta con nombre aleatorio
 * y el override de nombre_proyecto viola la FK a ventas, ajustar aquí.
 */
function crearProyectoConNombre(string $nombre): Proyecto
{
    return Proyecto::factory()->create(['nombre_proyecto' => $nombre]);
}

it('resuelve un proyecto por nombre exacto', function () {
    $proyecto = crearProyectoConNombre('Palomar 3 Bis');

    $resuelto = ProyectoResolver::resolver('Palomar 3 Bis');

    expect($resuelto->id)->toBe($proyecto->id);
});

it('resuelve ignorando espacios al inicio y fin', function () {
    $proyecto = crearProyectoConNombre('Palomar 3 Bis');

    expect(ProyectoResolver::resolver('  Palomar 3 Bis  ')->id)->toBe($proyecto->id);
    expect(ProyectoResolver::resolver("\tPalomar 3 Bis\n")->id)->toBe($proyecto->id);
});

it('resuelve ignorando mayúsculas y minúsculas', function () {
    $proyecto = crearProyectoConNombre('Palomar 3 Bis');

    expect(ProyectoResolver::resolver('palomar 3 bis')->id)->toBe($proyecto->id);
    expect(ProyectoResolver::resolver('PALOMAR 3 BIS')->id)->toBe($proyecto->id);
    expect(ProyectoResolver::resolver('PaLoMaR 3 bIs')->id)->toBe($proyecto->id);
});

it('lanza excepción y loguea warning si el proyecto no existe', function () {
    Log::spy();

    expect(fn () => ProyectoResolver::resolver('Proyecto Inexistente XYZ'))
        ->toThrow(ProyectoNoEncontradoException::class);

    Log::shouldHaveReceived('warning')
        ->withArgs(fn ($msg) => str_contains($msg, 'ProyectoResolver'))
        ->once();
});

it('lanza excepción si el nombre está vacío o solo tiene espacios', function () {
    expect(fn () => ProyectoResolver::resolver(''))->toThrow(ProyectoNoEncontradoException::class);
    expect(fn () => ProyectoResolver::resolver('   '))->toThrow(ProyectoNoEncontradoException::class);
});

it('elige el más reciente si hay múltiples con el mismo nombre', function () {
    Log::spy();

    $antiguo = crearProyectoConNombre('Palomar 3 Bis');
    $reciente = crearProyectoConNombre('palomar 3 bis');

    $resuelto = ProyectoResolver::resolver('PALOMAR 3 BIS');

    expect($resuelto->id)->toBe($reciente->id);
    expect($resuelto->id)->not->toBe($antiguo->id);

    Log::shouldHaveReceived('warning')
        ->withArgs(fn ($msg) => str_contains($msg, 'múltiples'))
        ->once();
});

it('la excepción incluye el nombre buscado y mensaje accionable', function () {
    try {
        ProyectoResolver::resolver('Fantasma');
        $this->fail('Se esperaba ProyectoNoEncontradoException');
    } catch (ProyectoNoEncontradoException $e) {
        expect($e->nombreBuscado)->toBe('Fantasma');
        expect($e->getMessage())->toContain('Fantasma');
        expect($e->getMessage())->toContain('administrador');
    }
});