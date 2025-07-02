<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VaccineController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard')->with('info', 'Las vacunas se gestionan a través de la información de cada mascota.');
    }

    public function create()
    {
        return redirect()->back()->with('info', 'El formulario de creación de vacunas está en el modal de la página de información de la mascota.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para registrar vacunas.');
        }

        $request->validate([
            'id_mascota' => 'required|exists:pets,id',
            'nombre' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date',
        ]);

        try {
            Vaccine::create([
                'id_mascota' => $request->id_mascota,
                'nombre' => $request->nombre,
                'fecha_aplicacion' => $request->fecha_aplicacion,
            ]);

            return redirect()->route('employee.mascotas.show', $request->id_mascota)->with('success', 'Vacuna registrada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar la vacuna: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Vaccine $vaccine)
    {
        return redirect()->back()->with('info', 'Los detalles de la vacuna se muestran en el contexto de la mascota.');
    }

    public function edit(Vaccine $vaccine)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para editar vacunas.');
        }
        return redirect()->back()->with('info', 'El formulario de modificación de vacunas está en el modal de la página de información de la mascota.');
    }

    public function update(Request $request, Vaccine $vaccine)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para actualizar vacunas.');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date',
        ]);

        try {
            $vaccine->update([
                'nombre' => $request->nombre,
                'fecha_aplicacion' => $request->fecha_aplicacion,
            ]);

            return redirect()->route('employee.mascotas.show', $vaccine->id_mascota)->with('success', 'Vacuna actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la vacuna: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Vaccine $vaccine)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para eliminar vacunas.');
        }

        try {
            $petId = $vaccine->id_mascota;
            $vaccine->delete();
            return redirect()->route('employee.mascotas.show', $petId)->with('success', 'Vacuna eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la vacuna: ' . $e->getMessage());
        }
    }
}
