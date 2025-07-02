<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('user');

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('apellido', 'like', '%' . $search . '%');
            });
        }

        $clients = $query->get();

        return view('admin.usuarios', compact('clients'));
    }

    public function create()
    {
        return redirect()->route('admin.usuarios')->with('info', 'El formulario de creación está en la página de usuarios.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:100',
            'correo' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:30|unique:users,name',
            'contra' => 'required|string|min:8|max:30',
        ], [
            'correo.unique' => 'Ya existe un usuario con este correo electrónico.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'contra.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            $user = User::create([
                'name' => $request->username,
                'email' => $request->correo,
                'password' => Hash::make($request->contra),
                'rol_id' => 1,
            ]);

            $client = Client::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'user_id' => $user->id,
            ]);

            return redirect()->route('admin.usuarios')->with('success', 'Usuario Cliente creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el Usuario Cliente: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Client $client)
    {
        $client->load('user', 'pets');
        return view('admin.usuario-info', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load('user');
        return view('admin.usuario-info', compact('client'))->with('info', 'El formulario de modificación está la página de información del usuario.');
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:100',
            'correo' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($client->user->id),
            ],
            'username' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'name')->ignore($client->user->id),
            ],
            'contra' => 'nullable|string|min:8|max:30',
        ], [
            'correo.unique' => 'Ya existe un usuario con este correo electrónico.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'contra.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            $user = $client->user;

            $user->name = $request->username;
            $user->email = $request->correo;
            if ($request->filled('contra')) {
                $user->password = Hash::make($request->contra);
            }
            $user->save();

            $client->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            return redirect()->route('admin.usuarios')->with('success', 'Cliente y usuario actualizados exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el cliente y usuario: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Client $client)
    {
        try {
            $user = $client->user;

            if ($user) {
                $user->delete();
            } else {
                $client->delete();
            }

            return redirect()->route('admin.usuarios')->with('success', 'Cliente y usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el cliente y usuario: ' . $e->getMessage());
        }
    }
}
