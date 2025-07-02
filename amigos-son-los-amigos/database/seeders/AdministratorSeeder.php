<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrator;
use App\Models\User;
use App\Models\Role;

class AdministratorSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Role::where('nombre', 'administrador')->first();

        $usuariosAdmins = User::where('rol_id', $rolAdmin->id)->get();

        foreach ($usuariosAdmins as $usuario) {
            Administrator::factory()->create([
                'user_id' => $usuario->id,
            ]);
        }
    }
}
