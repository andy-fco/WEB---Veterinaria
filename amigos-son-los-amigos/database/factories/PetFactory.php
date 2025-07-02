<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
     
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),
            'especie' => $this->faker->word(),
            'raza' => $this->faker->word(),
            'fecha_nacimiento' => $this->faker->date(),
        ];
    }
}
