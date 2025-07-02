<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Pet;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DiagnosisController extends Controller
{
    /**
     * Muestra una lista de todos los diagnósticos.
     * La vista dependerá del rol del usuario (empleado o cliente).
     */
    public function index()
    {
        $user = Auth::user();
        $diagnoses = collect();
        if ($user->isEmployee()) {
            $diagnoses = Diagnosis::with(['pet.client', 'employee'])
                ->orderBy('id', 'asc')
                ->get();
            return view('employee.diagnosticos', compact('diagnoses'));
        } elseif ($user->isClient()) {
            $client = $user->client;
            if ($client) {
                $petIds = $client->pets->pluck('id');
                $diagnoses = Diagnosis::whereIn('id_mascota', $petIds)
                    ->with(['pet.client', 'employee'])
                    ->orderBy('id', 'asc')
                    ->get();
            }
            return view('client.diagnosticos', compact('diagnoses'));
        }

        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado a diagnósticos.');
    }

    /**
     * Muestra el formulario para crear un nuevo diagnóstico.
     * Asumimos que este formulario se presenta en un modal o en la misma página del índice.
     */
    public function create()
    {
        $user = Auth::user();
        $redirectToRoute = $user->isEmployee() ? 'employee.diagnosticos.index' : 'client.diagnosticos.index';
        // Redirige al índice con un mensaje informativo
        return redirect()->route($redirectToRoute)->with('info', 'El formulario de creación de diagnósticos se encuentra en la página principal de diagnósticos.');
    }

    /**
     * Almacena un nuevo diagnóstico en la base de datos.
     * Solo los empleados tienen permiso para crear diagnósticos.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Verificar si el usuario es un empleado
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear diagnósticos.');
        }

        $request->validate([
            'Mascota' => 'required|exists:pets,id',


            'descripcion' => 'required|string|max:1000',
            'tratamiento' => 'nullable|string|max:1000',

        ], [
            'id_mascota.required' => 'La mascota es obligatoria para el diagnóstico.',
            'id_mascota.exists' => 'La mascota seleccionada no es válida.',

            'descripcion.required' => 'La descripción del diagnóstico es obligatoria.',
            'descripcion.max' => 'La descripción del diagnóstico no puede exceder los 1000 caracteres.',
            'tratamiento' => 'El tratamiento sugerido no puede exceder los 1000 caracteres.',
        ]);

        try {

            $employee = $user->employee;

            Diagnosis::create([
                'id_mascota' => $request->Mascota,
                'id_empleado' => $employee->id,
                'descripcion' => $request->descripcion,
                'tratamiento' => $request->tratamiento,

            ]);

            // Redirigir de vuelta a la página de diagnósticos con un mensaje de éxito
            return redirect()->route('employee.diagnosticos.index')->with('success', 'Diagnóstico registrado exitosamente.');
        } catch (\Exception $e) {
            // Manejar cualquier error que ocurra durante la creación
            return redirect()->back()->with('error', 'Error al registrar el diagnóstico: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Muestra los detalles de un diagnóstico específico.
     */
    public function show(Diagnosis $diagnosis)
    {
        $user = Auth::user();

        if ($user->isClient()) {
            if ($diagnosis->pet->cliente_id !== $user->client->id) {
                return redirect()->route('client.diagnosticos.index')->with('error', 'No tienes permiso para ver este diagnóstico.');
            }
        } elseif (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para ver diagnósticos.');
        }

        $diagnosis->load(['pet.client', 'employee']);

        return redirect()->back()->with('info', 'La información del diagnóstico se muestra en el modal de la lista.');
    }

    /**
     * Muestra el formulario para editar un diagnóstico existente.
     * Asumimos que este formulario se presenta en un modal o en la misma página del índice.
     */
    public function edit(Diagnosis $diagnosis)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para editar diagnósticos.');
        }
        return redirect()->route('employee.diagnosticos.index')->with('info', 'El formulario de modificación de diagnósticos se encuentra en el modal de la página de diagnósticos.');
    }

    /**
     * Actualiza un diagnóstico existente en la base de datos.
     * Solo los empleados tienen permiso para actualizar diagnósticos.
     */
    public function update(Request $request, Diagnosis $diagnosis)
    {
        $user = Auth::user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para actualizar diagnósticos.');
        }

        $request->validate([
            'id_mascota' => 'required|exists:pets,id',
            'id_empleado' => 'required|exists:employees,id',
            'descripcion' => 'required|string|max:1000',
            'tratamiento' => 'nullable|string|max:1000',
        ], [
            'id_mascota.required' => 'La mascota es obligatoria para el diagnóstico.',
            'id_mascota.exists' => 'La mascota seleccionada no es válida.',
            'id_empleado.required' => 'El empleado que realiza el diagnóstico es obligatorio.',
            'id_empleado.exists' => 'El empleado seleccionado no es válido.',
            'descripcion.required' => 'La descripción del diagnóstico es obligatoria.',
            'descripcion.max' => 'La descripción del diagnóstico no puede exceder los 1000 caracteres.',
            'tratamiento.max' => 'El tratamiento sugerido no puede exceder los 1000 caracteres.',

        ]);

        try {
            $fechaDiagnostico = Carbon::createFromFormat('d/m/Y H:i', $request->input('fecha_diagnostico'));

            $diagnosis->update([
                'id_mascota' => $request->id_mascota,
                'id_empleado' => $request->id_empleado,
                'descripcion' => $request->descripcion,
                'tratamiento' => $request->tratamiento_sugerido,
            ]);

            return redirect()->route('employee.diagnosticos.index')->with('success', 'Diagnóstico actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el diagnóstico: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Elimina un diagnóstico de la base de datos.
     * Solo los empleados tienen permiso para eliminar diagnósticos.
     */
    public function destroy(Diagnosis $diagnosis)
    {
        $user = Auth::user();


        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para eliminar diagnósticos.');
        }

        try {
            $diagnosis->delete();
            return redirect()->route('employee.diagnosticos.index')->with('success', 'Diagnóstico eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el diagnóstico: ' . $e->getMessage());
        }
    }
}
