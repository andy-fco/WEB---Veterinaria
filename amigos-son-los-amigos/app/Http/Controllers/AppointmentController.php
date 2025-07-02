<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = collect();

        if ($user->isClient()) {
            $client = $user->client;
            if ($client) {
                $appointments = Appointment::where('id_cliente', $client->id)
                    ->with(['pet', 'employee'])
                    ->orderBy('fecha_turno', 'desc')
                    ->get();
            }
            return view('client.turnos', compact('appointments'));
        } elseif ($user->isEmployee()) {
            $appointments = Appointment::with(['client', 'pet', 'employee'])
                ->orderBy('fecha_turno', 'desc')
                ->get();
            return view('employee.turnos', compact('appointments'));
        }

        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado a turnos.');
    }

    public function create()
    {
        $user = Auth::user();
        $redirectToRoute = $user->isClient() ? 'client.turnos.index' : 'employee.turnos.index';
        return redirect()->route($redirectToRoute)->with('info', 'El formulario de creación de turnos está en el modal de la página de turnos.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'fecha' => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',

            'mascota' => 'required|exists:pets,id',
        ];

        if ($user->isClient()) {
            $client = $user->client;
            if (!$client) {
                return redirect()->back()->with('error', 'No se pudo crear el turno: no tienes un perfil de cliente asociado.');
            }
            $rules['mascota'] .= ',cliente_id,' . $client->id;
            $request->merge(['id_cliente' => $client->id]);
        } elseif ($user->isEmployee()) {
            $rules['id_cliente'] = 'required|exists:clients,id';
            $rules['id_empleado'] = 'required|exists:employees,id';
            $rules['estado'] = 'nullable|in:pendiente,confirmado,cancelado,completado';
        } else {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear turnos.');
        }

        $request->validate($rules, [
            'fecha.date_format' => 'El formato de la fecha y hora debe ser DD/MM/AAAA HH:MM.',
            'fecha.after_or_equal' => 'La fecha del turno debe ser hoy o posterior.',
            'mascota.exists' => 'La mascota seleccionada no es válida o no te pertenece.',
            'id_cliente.required' => 'El cliente es obligatorio para crear un turno como empleado.',
            'id_cliente.exists' => 'El cliente seleccionado no es válido.',
            'id_empleado.required' => 'El empleado es obligatorio para crear un turno.',
            'id_empleado.exists' => 'El empleado seleccionado no es válido.',
        ]);

        try {
            $fechaTurno = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('fecha'));

            $appointmentData = [
                'fecha_turno' => $fechaTurno,
                'id_mascota' => $request->input('mascota'),
                'estado' => $request->input('estado') ?? 'pendiente',
            ];

            if ($user->isClient()) {
                $appointmentData['id_cliente'] = $request->id_cliente;
                $appointmentData['id_empleado'] = $request->input('id_empleado') ?? 1;
            } elseif ($user->isEmployee()) {
                $appointmentData['id_cliente'] = $request->input('id_cliente');
                $appointmentData['id_empleado'] = $request->input('id_empleado');
            }

            Appointment::create($appointmentData);

            $redirectToRoute = $user->isClient() ? 'client.turnos.index' : 'employee.turnos.index';
            return redirect()->route($redirectToRoute)->with('success', 'Turno creado exitosamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el turno: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        $redirectToRoute = $user->isClient() ? 'client.turnos.index' : 'employee.turnos.index';

        if ($user->isClient()) {
            if ($appointment->id_cliente !== $user->client->id) {
                return redirect()->route($redirectToRoute)->with('error', 'No tienes permiso para ver este turno.');
            }
        }

        $appointment->load(['client', 'pet', 'employee']);
        return redirect()->route($redirectToRoute)->with('info', 'La información detallada del turno se muestra en el modal de la lista de turnos.');
    }

    public function edit(Appointment $appointment)
    {
        $user = Auth::user();
        $redirectToRoute = $user->isClient() ? 'client.turnos.index' : 'employee.turnos.index';

        if ($user->isClient()) {
            if ($appointment->id_cliente !== $user->client->id) {
                return redirect()->route($redirectToRoute)->with('error', 'No tienes permiso para editar este turno.');
            }
        }
        return redirect()->route($redirectToRoute)->with('info', 'El formulario de modificación de turnos está en el modal de la página de turnos.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $rules = [
            'fecha' => 'required|date_format:d/m/Y H:i',
            'mascota' => 'required|exists:pets,id',
        ];

        if ($user->isClient()) {
            if ($appointment->id_cliente !== $user->client->id) {
                return redirect()->route('client.turnos.index')->with('error', 'No tienes permiso para actualizar este turno.');
            }
            $rules['mascota'] .= ',cliente_id,' . $user->client->id;
        } elseif ($user->isEmployee()) {
            $rules['id_cliente'] = 'required|exists:clients,id';
            $rules['id_empleado'] = 'required|exists:employees,id';
            $rules['estado'] = 'required|in:pendiente,confirmado,cancelado,completado';
        } else {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para actualizar turnos.');
        }

        $request->validate($rules, [
            'fecha.date_format' => 'El formato de la fecha y hora debe ser DD/MM/AAAA HH:MM.',
            'mascota.exists' => 'La mascota seleccionada no es válida o no te pertenece.',
            'id_cliente.required' => 'El cliente es obligatorio para actualizar un turno como empleado.',
            'id_cliente.exists' => 'El cliente seleccionado no es válido.',
            'id_empleado.required' => 'El empleado es obligatorio para actualizar un turno.',
            'id_empleado.exists' => 'El empleado seleccionado no es válido.',
        ]);

        try {
            $fechaTurno = Carbon::createFromFormat('d/m/Y H:i', $request->input('fecha'));

            $appointmentData = [
                'fecha_turno' => $fechaTurno,
                'id_mascota' => $request->input('mascota'),
                'estado' => $request->input('estado') ?? $appointment->estado,
            ];

            if ($user->isEmployee()) {
                $appointmentData['id_cliente'] = $request->input('id_cliente');
                $appointmentData['id_empleado'] = $request->input('id_empleado');
            } else {
                $appointmentData['id_empleado'] = $request->input('id_empleado') ?? $appointment->id_empleado;
                $appointmentData['estado'] = $appointment->estado;
            }

            $appointment->update($appointmentData);

            $redirectToRoute = $user->isClient() ? 'client.turnos.index' : 'employee.turnos.index';
            return redirect()->route($redirectToRoute)->with('success', 'Turno actualizado exitosamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el turno: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();
        $redirectToRoute = $user->isClient() ? 'client.turnos.index' : 'employee.turnos.index';

        if ($user->isClient()) {
            if ($appointment->id_cliente !== $user->client->id) {
                return redirect()->route($redirectToRoute)->with('error', 'No tienes permiso para eliminar este turno.');
            }
        } elseif (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para eliminar turnos.');
        }

        try {
            $appointment->delete();
            return redirect()->route($redirectToRoute)->with('success', 'Turno eliminado exitosamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el turno: ' . $e->getMessage());
        }
    }
}
