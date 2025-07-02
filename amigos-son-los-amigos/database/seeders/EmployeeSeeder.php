<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $rolEmpleado = Role::where('nombre', 'empleado')->first();

        $usuariosEmpleados = User::where('rol_id', $rolEmpleado->id)->get();

        foreach ($usuariosEmpleados as $usuario) {
            Employee::factory()->create([
                'user_id' => $usuario->id,
            ]);
        }
    }
}
