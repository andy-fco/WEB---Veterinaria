<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdministratorController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard')->with('info', 'La gestión de administradores no tiene una vista de listado separada por ahora.');
    }

    public function show(Administrator $administrator)
    {
        $administrator->load('user');
        return redirect()->route('admin.dashboard')->with('info', 'Los detalles del administrador se gestionan internamente o no tienen una vista dedicada.');
    }

    public function update(Request $request, Administrator $administrator)
    {
        abort(403, 'Funcionalidad de actualización de administradores no implementada directamente vía esta ruta.');
    }

    public function destroy(Administrator $administrator)
    {
        abort(403, 'Funcionalidad de eliminación de administradores no implementada directamente vía esta ruta.');
    }


    /**/  public function store(Request $request)
 {
          $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => 3, 
            ]);
           
            $administrator = Administrator::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'user_id' => $user->id,
            ]);

            Administrator::create([
                'user_id' => $user->id,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Administrador creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el administrador: ' . $e->getMessage())->withInput();
        }
    }
}
