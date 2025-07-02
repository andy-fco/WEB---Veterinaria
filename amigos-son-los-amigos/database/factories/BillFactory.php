<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'fecha_factura' => $this->faker->date(),
            'monto_total' => $this->faker->randomFloat(2, 100, 1000),
            'estado' => $this->faker->randomElement(['pagada', 'pendiente', 'cancelada']),
        ];
    }
}
