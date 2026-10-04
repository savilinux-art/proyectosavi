<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\Usuario;
use App\Models\VentaMostrador;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaMostradorFactory extends Factory
{
    protected $model = VentaMostrador::class;

    public function definition(): array
    {
        return [
            'proyecto_id' => Proyecto::factory(),
            'estado' => 'pendiente',
            'total' => 0,
            'observaciones' => $this->faker->optional()->sentence(),
            'creado_por' => Usuario::factory()->create()->usuario,
            'modificado_por' => null,
        ];
    }

    public function completada(): static
    {
        return $this->state(fn (array $attrs) => ['estado' => 'completada']);
    }

    public function cancelada(): static
    {
        return $this->state(fn (array $attrs) => ['estado' => 'cancelada']);
    }
}
