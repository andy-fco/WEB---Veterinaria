<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Pet;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $consultations = collect();

        if ($user->isEmployee()) {
            $consultations = Consultation::with(['pet.client', 'employee'])->get();
            return view('employee.diagnosticos', compact('consultations'));
        } elseif ($user->isClient()) {
            $client = $user->client;
            if ($client) {
                $petIds = $client->pets->pluck('id')->toArray();
                $consultations = Consultation::whereIn('id_mascota', $petIds)
                                ->with(['pet', 'employee'])
                                ->get();
            }
            return redirect()->route('client.dashboard')->with('error', 'La vista de listado de diagnósticos para clientes no está implementada. Puedes ver diagnósticos desde la ficha de tu mascota.');
        }

        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado a diagnósticos.');
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear consultas.');
        }
        return redirect()->route('employee.diagnosticos.index')->with('info', 'El formulario de creación está en el modal de la página de diagnósticos.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear consultas.');
        }

        $rules = [
            'Mascota' => 'required|exists:pets,id',
            'Consulta' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tratamiento' => 'nullable|string',
        ];
        $request->validate($rules, [
            'Mascota.required' => 'La mascota es obligatoria.',
            'Mascota.exists' => 'La mascota seleccionada no es válida.',
            'Consulta.required' => 'La consulta es obligatoria.',
        ]);

        try {
            Consultation::create([
                'id_mascota' => $request->input('Mascota'),
                'titulo' => $request->input('Consulta'),
                'descripcion' => $request->input('descripcion'),
                'tratamiento' => $request->input('tratamiento'),
                'id_empleado' => $user->employee->id ?? null,
                'fecha' => now(), 
            ]);

            return redirect()->route('employee.diagnosticos.index')->with('success', 'Diagnóstico creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el diagnóstico: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Consultation $consultation)
    {
        $user = Auth::user();

        if ($user->isClient()) {
            if ($consultation->pet->cliente_id !== $user->client->id) {
                return redirect()->route('client.dashboard')->with('error', 'No tienes permiso para ver esta consulta.');
            }
            return view('client.mascota-info', ['pet' => $consultation->pet->load('consultations')]);
        } elseif ($user->isEmployee()) {
            $consultation->load(['pet.client', 'employee']);
            return redirect()->route('employee.diagnosticos.index')->with('info', 'Los detalles del diagnóstico se muestran en el modal.');
        }
        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
    }

    public function edit(Consultation $consultation)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para editar consultas.');
        }
        return redirect()->route('employee.diagnosticos.index')->with('info', 'El formulario de modificación está en el modal de la página de diagnósticos.');
    }

    public function update(Request $request, Consultation $consultation)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para actualizar consultas.');
        }

        $request->validate([
            'Mascota' => 'required|exists:pets,id',
            'Consulta' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tratamiento' => 'nullable|string',
        ]);

        try {
            $consultation->update([
                'id_mascota' => $request->input('Mascota'),
                'titulo' => $request->input('Consulta'),
                'descripcion' => $request->input('descripcion'),
                'tratamiento' => $request->input('tratamiento'),
            ]);

            return redirect()->route('employee.diagnosticos.index')->with('success', 'Diagnóstico actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el diagnóstico: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Consultation $consultation)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para eliminar consultas.');
        }

        try {
            $consultation->delete();
            return redirect()->route('employee.diagnosticos.index')->with('success', 'Diagnóstico eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el diagnóstico: ' . $e->getMessage());
        }
    }
}
