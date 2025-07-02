<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pets = collect();

        if ($user->isClient()) {
            $client = $user->client;
            if ($client) {
                $pets = Pet::where('cliente_id', $client->id)
                            ->with('client')
                            ->get();
            }
            return view('client.mascotas', compact('pets'));
        } elseif ($user->isEmployee()) {
            $pets = Pet::with('client')->get();
            return view('employee.mascotas', compact('pets'));
        }

        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado a la gestión de mascotas.');
    }

    public function create()
    {
        $user = Auth::user();
        $redirectToRoute = $user->isClient() ? 'client.mascotas.index' : 'employee.mascotas.index';
        return redirect()->route($redirectToRoute)->with('info', 'El formulario de creación de mascotas está en el modal de la página de mascotas.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'nombre' => 'required|string|max:50',
            'especie' => 'required|string|max:50',
            'raza' => 'nullable|string|max:50',
            'fecha-nacimiento' => 'nullable|date',
        ];

        $petData = [
            'nombre' => $request->input('nombre'),
            'especie' => $request->input('especie'),
            'raza' => $request->input('raza'),
            'fecha_nacimiento' => $request->input('fecha-nacimiento') ? Carbon::parse($request->input('fecha-nacimiento')) : null,
        ];

        $redirectToRoute = '';

        if ($user->isClient()) {
            $client = $user->client;
            if (!$client) {
                return redirect()->back()->with('error', 'No se pudo asociar la mascota: no tienes un perfil de cliente.');
            }
            $petData['cliente_id'] = $client->id;
            $redirectToRoute = 'client.mascotas.index';
        } elseif ($user->isEmployee()) {
            $rules['owner'] = 'required|exists:clients,id';
            $request->validate($rules);
            $petData['cliente_id'] = $request->input('owner');
            $redirectToRoute = 'employee.mascotas.index';
        } else {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear mascotas.');
        }

        $request->validate($rules, [
            'fecha-nacimiento.date' => 'El formato de la fecha de nacimiento no es válido.',
            'owner.required' => 'El cliente (dueño) es obligatorio para crear una mascota como empleado.',
            'owner.exists' => 'El cliente (dueño) seleccionado no es válido.',
        ]);

        try {
            Pet::create($petData);
            return redirect()->route($redirectToRoute)->with('success', 'Mascota creada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la mascota: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Pet $pet)
    {
        $user = Auth::user();
        $pet->load(['client', 'appointments', 'consultations', 'vaccines']);

        if ($user->isClient()) {
            if ($pet->cliente_id !== $user->client->id) {
                return redirect()->route('client.mascotas.index')->with('error', 'No tienes permiso para ver esta mascota.');
            }
            return view('client.mascota-info', compact('pet'));
        } elseif ($user->isEmployee()) {
            return view('employee.mascota-info', compact('pet'));
        }

        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
    }

    public function edit(Pet $pet)
    {
        $user = Auth::user();

        if ($user->isClient()) {
            if ($pet->cliente_id !== $user->client->id) {
                return redirect()->route('client.mascotas.index')->with('error', 'No tienes permiso para editar esta mascota.');
            }
            return view('client.mascota-info', compact('pet'));
        } elseif ($user->isEmployee()) {
            return view('employee.mascota-info', compact('pet'));
        }
        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para editar mascotas.');
    }

    public function update(Request $request, Pet $pet)
    {
        $user = Auth::user();
        $rules = [
            'nombre' => 'required|string|max:50',
            'especie' => 'required|string|max:50',
            'raza' => 'nullable|string|max:50',
            'fecha-nacimiento' => 'nullable|date',
        ];

        $petData = [
            'nombre' => $request->input('nombre'),
            'especie' => $request->input('especie'),
            'raza' => $request->input('raza'),
            'fecha_nacimiento' => $request->input('fecha-nacimiento') ? Carbon::parse($request->input('fecha-nacimiento')) : null,
        ];

        $redirectToRoute = '';

        if ($user->isClient()) {
            if ($pet->cliente_id !== $user->client->id) {
                return redirect()->route('client.mascotas.index')->with('error', 'No tienes permiso para actualizar esta mascota.');
            }
            $redirectToRoute = 'client.mascotas.index';
        } elseif ($user->isEmployee()) {
            $rules['owner'] = 'required|exists:clients,id';
            $petData['cliente_id'] = $request->input('owner');
            $redirectToRoute = 'employee.mascotas.index';
        } else {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para actualizar mascotas.');
        }

        $request->validate($rules, [
            'fecha-nacimiento.date' => 'El formato de la fecha de nacimiento no es válido.',
            'owner.required' => 'El cliente (dueño) es obligatorio para actualizar una mascota como empleado.',
            'owner.exists' => 'El cliente (dueño) seleccionado no es válido.',
        ]);

        try {
            $pet->update($petData);
            return redirect()->route($redirectToRoute)->with('success', 'Mascota actualizada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la mascota: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Pet $pet)
    {
        $user = Auth::user();
        $redirectToRoute = '';

        if ($user->isClient()) {
            if ($pet->cliente_id !== $user->client->id) {
                return redirect()->route('client.mascotas.index')->with('error', 'No tienes permiso para eliminar esta mascota.');
            }
            $redirectToRoute = 'client.mascotas.index';
        } elseif ($user->isEmployee()) {
            $redirectToRoute = 'employee.mascotas.index';
        } else {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para eliminar mascotas.');
        }

        try {
            $pet->delete();
            return redirect()->route($redirectToRoute)->with('success', 'Mascota eliminada exitosamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la mascota: ' . $e->getMessage());
        }
    }
}
