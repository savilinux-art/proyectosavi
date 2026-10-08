<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proyecto>
 *
 * Q-100b RESUELTO (8-oct-2026):
 *   Ya NO crea Venta. La FK `instalaciones.nombre_proyecto` apunta a
 *   `proyectos.nombre_proyecto` desde antes del 4-oct-2026 (verificado
 *   con SHOW CREATE TABLE en tinker).
 *   El formato del nombre replica el de VentaFactory para compatibilidad.
 *
 * NOTA: `Usuario::factory()->create()` es el default de UsuarioFactory.
 * Si ese default crea rol=Administrador, este factory también crea un
 * admin colateral. Es esperado y los tests lo manejan con contarAdmins().
 */
class ProyectoFactory extends Factory
{
    protected $model = Proyecto::class;

    public function definition(): array
    {
        return [
            'nombre_proyecto'       => 'PROY-' . strtoupper($this->faker->unique()->bothify('????-####')),
            'correo_electronico'    => $this->faker->unique()->companyEmail(),
            'ubicacion'             => null,
            'propuesta_economica'   => null,
            'archivo_as_built'      => null,
            'credenciales'          => null,
            'salida_inventario'     => null,
            'devolucion_inventario' => null,
            'modificado_por'        => Usuario::factory()->create()->usuario,
        ];
    }
}