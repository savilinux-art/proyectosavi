<?php

namespace Database\Factories;

use App\Models\Estatus;
use App\Models\Instalacion;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        // TODO(legacy): columna zombie, solo existe en testing.
        // El dominio usa el pivote `instalacion_instalador`.
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
}
