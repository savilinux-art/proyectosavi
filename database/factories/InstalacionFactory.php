<?php

namespace Database\Factories;

use App\Models\Estatus;
use App\Models\Instalacion;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instalacion>
 *
 * NOTA (Q-93/Q-101):
 *  - `id_usuario_asignado` es columna zombie NOT NULL en BD.
 *    El dominio usa el pivote `instalacion_instalador`.
 *    Se puebla aquí para no romper el INSERT, pero el filtro de la API
 *    y el web usan SOLO el pivote.
 *  - Pendiente Q-101b: migrar `id_usuario_asignado` a nullable y dejar
 *    de poblarla.
 *  - Pendiente Q-101c: quitar `Venta::factory()` (legacy del acople),
 *    requiere actualizar InstalacionObserverTest que cuenta con el admin
 *    transitivo de VentaFactory.
 */
class InstalacionFactory extends Factory
{
    protected $model = Instalacion::class;

    public function definition(): array
    {
        $venta = Venta::factory()->create();

        $estatus = Estatus::firstOrCreate(
            ['estatus' => 'Programada'],
            ['tipo'    => 'instalacion']
        );

        // Columna zombie NOT NULL — se llena, pero el dominio usa el pivote.
        $instaladorLegacy = Usuario::factory()->instalador()->create();

        return [
            'id_usuario_asignado'      => $instaladorLegacy->usuario,
            'nombre_proyecto'          => $venta->nombre_proyecto,
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
     * Puebla el pivote `instalacion_instalador` con el MISMO instalador
     * que quedó en `id_usuario_asignado`, manteniendo ambos lados en sync.
     * El dominio (filtro de la API y web) usa SOLO el pivote.
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
