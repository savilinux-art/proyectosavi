<?php

namespace Database\Factories;

use App\Models\Estatus;
use App\Models\Instalacion;
use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instalacion>
 *
 * Q-101b/c RESUELTOS (8-oct-2026):
 *   - `id_usuario_asignado` ya es NULLABLE en BD (verificado con DDL).
 *     El factory ya NO la puebla. El dominio usa SOLO el pivote.
 *   - Ya NO crea Venta. `nombre_proyecto` se deriva de un Proyecto,
 *     que es la entidad canónica (FK real desde antes del 4-oct-2026).
 *   - El `afterCreating` del pivote se mantiene para el caso en que
 *     un test pase `id_usuario_asignado` vía state().
 */
class InstalacionFactory extends Factory
{
    protected $model = Instalacion::class;

    public function definition(): array
    {
        $proyecto = Proyecto::factory()->create();

        $estatus = Estatus::firstOrCreate(
            ['estatus' => 'Programada'],
            ['tipo'    => 'instalacion']
        );

        return [
            'id_usuario_asignado'      => null,
            'nombre_proyecto'          => $proyecto->nombre_proyecto,
            'nombre_instalacion'       => 'Instalación ' . $this->faker->unique()->numerify('###'),
            'ubicacion_actual'         => null,
            'latitud'                  => $this->faker->latitude(),
            'longitud'                 => $this->faker->longitude(),
            'direccion'                => $this->faker->address(),
            'ubicacion_actualizada_en' => null,
            'evidencia_inicio'         => null,
            'incidencias'              => null,
            'evidencia_fin'            => null,
            'check_list'               => null,
            'fecha_hora_inicio'        => $this->faker->dateTimeBetween('-1 month', 'now'),
            'fecha_hora_fin'           => null,
            'estatus_instalacion'      => $estatus->estatus,
        ];
    }

    /**
     * Si un test pasa `id_usuario_asignado` explícitamente vía state(),
     * sincroniza el pivote. Si es null (default), no hace nada.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Instalacion $instalacion) {
            if ($instalacion->id_usuario_asignado) {
                $instalacion->instaladores()->syncWithoutDetaching([
                    $instalacion->id_usuario_asignado,
                ]);
            }
        });
    }
}