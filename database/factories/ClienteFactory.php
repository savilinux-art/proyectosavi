<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'rfc'                         => strtoupper($this->faker->unique()->bothify('???######???')),
            'razon_social'                => $this->faker->company() . ' SA de CV',
            'nombre_proyecto'             => 'Proyecto ' . $this->faker->unique()->word() . ' ' . $this->faker->numerify('###'),
            'regimen_fiscal'              => '601',
            'constancia_situacion_fiscal' => null,
            'codigo_postal'               => (int) $this->faker->postcode(),
            'correo_electronico'          => $this->faker->unique()->companyEmail(),
        ];
    }
}
