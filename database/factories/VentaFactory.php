<?php

namespace Database\Factories;

use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaFactory extends Factory
{
    protected $model = Venta::class;

    public function definition(): array
    {
        return [
            'titulo_venta'             => $this->faker->sentence(3),
            'nombre_proyecto'          => 'PROY-' . strtoupper($this->faker->unique()->bothify('????-####')),
            'moneda'                   => 'MXN',
            'monto_venta'              => $this->faker->randomFloat(2, 1000, 500000),
            'requerimiento_venta'      => $this->faker->sentence(),
            'cotizacion'               => null,
            'ubicacion'                => null,
            'vendedor'                 => Usuario::factory()->create()->usuario,
            'fecha_hora_levantamiento' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'levantamiento'            => null,
            'venta_ganada'             => true,
            'razon_perdida_venta'      => null,
            'estatus'                  => null,
        ];
    }
}
