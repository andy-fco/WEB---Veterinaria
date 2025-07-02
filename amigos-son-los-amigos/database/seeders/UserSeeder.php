<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $rolCliente = Role::where('nombre', 'cliente')->first();
        $rolEmpleado = Role::where('nombre', 'empleado')->first();
        $rolAdmin = Role::where('nombre', 'administrador')->first();

        // Crear 10 clientes
        User::factory(10)->create([
            'rol_id' => $rolCliente->id,
        ]);

        // Crear 5 empleados
        User::factory(5)->create([
            'rol_id' => $rolEmpleado->id,
        ]);

        // Crear 2 administradores
        User::factory(2)->create([
            'rol_id' => $rolAdmin->id,
        ]);

        // Obtener todos los usuarios
        $usuarios = User::all();

        // Texto que vamos a escribir (acá sabemos que la password es "password" para todos)
        $contenido = "Usuarios y contraseñas generados:\n\n";

        foreach ($usuarios as $usuario) {
            $contenido .= "Email: {$usuario->email} | Contraseña: password\n";
        }

        // Ruta donde se guardará el archivo
        $rutaArchivo = storage_path('app/usuarios_passwords.txt');

        // Guardar el archivo
        File::put($rutaArchivo, $contenido);
    }
}
