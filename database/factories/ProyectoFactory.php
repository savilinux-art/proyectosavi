<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    protected $model = Proyecto::class;

    public function definition(): array
    {
        $venta = Venta::factory()->create();

        return [
            'nombre_proyecto'       => $venta->nombre_proyecto,
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
