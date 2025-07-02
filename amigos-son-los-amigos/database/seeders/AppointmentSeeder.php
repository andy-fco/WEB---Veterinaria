<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Employee;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Client::all();
        $empleados = Employee::all();

        if ($clientes->isEmpty() || $empleados->isEmpty()) {
            $this->command->error('No hay clientes o empleados para crear citas.');
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            $cliente = $clientes->random();

            $mascotas = Pet::where('cliente_id', $cliente->id)->get();

            if ($mascotas->isEmpty()) {
                continue;
            }


            $mascota = $mascotas->random();


            $empleado = $empleados->random();

            Appointment::factory()->create([
                'id_cliente' => $cliente->id,
                'id_mascota' => $mascota->id,
                'id_empleado' => $empleado->id,
            ]);
        }
    }
}
