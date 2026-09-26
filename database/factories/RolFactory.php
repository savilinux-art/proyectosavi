<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolFactory extends Factory
{
    protected $model = Rol::class;

    public function definition(): array
    {
        return [
            'rol' => $this->faker->unique()->randomElement([
                'Administrador', 'Ventas', 'Instalador',
                'Contabilidad', 'Almacén', 'Supervisor', 'Técnico',
            ]) . ' ' . $this->faker->unique()->numerify('##'),
        ];
    }
}
