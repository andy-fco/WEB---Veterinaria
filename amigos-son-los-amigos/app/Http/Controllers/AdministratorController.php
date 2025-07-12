<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use App\Models\Bill;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;




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


    /**/
    public function store(Request $request)
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




    //  METODO PARA MOSTRAR LOS GRÁFICOS 

    public function showReports()
    {

        // Pacientes 
        $petsCount = Pet::count();

        // Turnos agendados (futuros o en fecha actual)
        $today = now()->startOfDay();
        $scheduledAppointmentsCount = Appointment::where('fecha_turno', '>=', $today)->count();

        // Empleados
        $employeesCount = Employee::count();





        //datos para el primer gráfico de total facturado por mes
        //estan los nombres en ingles, si queres lo cambiamos
        $monthlyBillTotals = Bill::selectRaw('DATE_FORMAT(fecha_factura, "%Y-%m") as month, SUM(monto_total) as total_amount')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $billMonths = [];
        $billAmounts = [];

        foreach ($monthlyBillTotals as $data) {
            $timestamp = strtotime($data->month . '-01');
            //formatea el mes y año 
            $billMonths[] = strftime('%B %Y', $timestamp); // %B para nombre completo del mes %Y para año
            $billAmounts[] = (float) $data->total_amount;
        }


        //datos para el segundo gráfico de cant de mascotas x especie
        try {
            $petsBySpecies = Pet::selectRaw('especie, count(*) as count')
                ->groupBy('especie')
                ->orderBy('count', 'desc')
                ->get();
            $speciesLabels = $petsBySpecies->pluck('especie')->toArray();
            $speciesCounts = $petsBySpecies->pluck('count')->toArray();
        } catch (\Exception $e) {
            $speciesLabels = ['Sin datos (error en Pet o especie)'];
            $speciesCounts = [0];
        }

        // Fecha inicio y fin del mes actual
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Facturas del mes actual
        $billsThisMonth = Bill::with('client')
            ->whereBetween('fecha_factura', [$startOfMonth, $endOfMonth])
            ->orderBy('id', 'desc')
            ->get();

        // Fecha inicio y fin de la semana actual (lunes a domingo)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Turnos de esta semana
        $appointmentsThisWeek = Appointment::with('client')
            ->whereBetween('fecha_turno', [$startOfWeek, $endOfWeek])
            ->orderBy('fecha_turno')
            ->get();


        return view('admin.reportes', [
            'petsCount' => $petsCount,
            'scheduledAppointmentsCount' => $scheduledAppointmentsCount,
            'employeesCount' => $employeesCount,
            'billMonths' => $billMonths,
            'billAmounts' => $billAmounts,
            'speciesLabels' => $speciesLabels,
            'speciesCounts' => $speciesCounts,
            'billsThisMonth' => $billsThisMonth,
            'appointmentsThisWeek' => $appointmentsThisWeek
        ]);
    }
}

