<?php

namespace Database\Seeders;

use App\Models\Vaccine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mascotas = Pet::all();

        if ($mascotas->isEmpty()) {
            $this->command->error('No hay mascotas para asignar vacunas.');
            return;
        }

        foreach ($mascotas as $mascota) {
            // Crear 1 a 3 vacunas por mascota
            Vaccine::factory()->count(rand(1, 3))->create([
                'id_mascota' => $mascota->id,
            ]);
        }
    }
}
