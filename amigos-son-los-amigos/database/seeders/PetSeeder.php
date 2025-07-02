<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\Client;
use App\Models\User;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Client::all();

        foreach ($clientes as $cliente) {
            // Crear 1 mascota obligatoria para cada cliente
            Pet::factory()->create([
                'cliente_id' => $cliente->id,
            ]);

            // Decidir si creamos 1 o 2 mascotas adicionales (random)
            $mascotasExtras = rand(0, 2); // 0, 1 o 2 mascotas extras

            for ($i = 0; $i < $mascotasExtras; $i++) {
                Pet::factory()->create([
                    'cliente_id' => $cliente->id,
                ]);
            }
        }
    }
}
