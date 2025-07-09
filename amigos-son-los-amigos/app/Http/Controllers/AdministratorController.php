<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use App\Models\Bill; // agrego estos dos 
use App\Models\Pet; // para los graficos
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




 //  METODO PARA MOSTRAR LOS GRÁFICOS 

    public function showReports()
    {
        //datos para el primer gráfico de total facturado por mes
        //estan los nombres en ingles porque hizo la base del codigo gemini, si queres lo cambiamos
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
        
        return view('reportes', [
            'billMonths' => $billMonths,
            'billAmounts' => $billAmounts,
            'speciesLabels' => $speciesLabels,
            'speciesCounts' => $speciesCounts,
        ]);
    }
}

