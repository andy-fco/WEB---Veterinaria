<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\Employee;
use App\Models\Diagnosis;

class DiagnosisSeeder extends Seeder
{
    public function run(): void
    {
        $mascotas = Pet::all();
        $empleados = Employee::all();

        if ($mascotas->isEmpty() || $empleados->isEmpty()) {
            $this->command->error('No hay mascotas o empleados para crear diagnósticos.');
            return;
        }

        foreach ($mascotas as $mascota) {
            for ($i = 0; $i < 2; $i++) {
                $empleado = $empleados->random();

                Diagnosis::factory()->create([
                    'id_mascota' => $mascota->id,
                    'id_empleado' => $empleado->id,
                ]);
            }
        }
    }
}
