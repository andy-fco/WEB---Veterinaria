<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $rolCliente = Role::where('nombre', 'cliente')->first();

        $usuariosClientes = User::where('rol_id', $rolCliente->id)->get();

        foreach ($usuariosClientes as $usuario) {
            Client::factory()->create([
                'user_id' => $usuario->id,
            ]);
        }
    }
}
