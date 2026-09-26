<?php

namespace Database\Factories;

use App\Models\Estatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstatusFactory extends Factory
{
    protected $model = Estatus::class;

    public function definition(): array
    {
        $tipo  = $this->faker->randomElement(['venta', 'instalacion', 'proyecto']);
        $valor = strtoupper($tipo) . '_' . $this->faker->unique()->word();

        return [
            'estatus' => $valor,
            'tipo'    => $tipo,
        ];
    }

    public function deTipo(string $tipo, string $nombre): static
    {
        return $this->state(fn () => [
            'estatus' => $nombre,
            'tipo'    => $tipo,
        ]);
    }
}
