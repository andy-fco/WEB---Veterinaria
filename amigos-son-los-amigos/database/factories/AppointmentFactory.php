<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'fecha_turno' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmado', 'cancelado', 'completado']),
        ];
    }
}
