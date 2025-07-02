<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BillController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bills = collect();

        if ($user->isClient()) {
            $client = $user->client;
            if ($client) {
                $bills = Bill::where('id_cliente', $client->id)
                    ->with('client')
                    ->get();
            }
            return view('client.facturas', compact('bills'));
        } elseif ($user->isEmployee()) {
            $bills = Bill::with('client')->get();
            return view('employee.facturacion', compact('bills'));
        }

        return redirect()->route('dashboard')->with('error', 'Acceso no autorizado a facturas.');
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear facturas.');
        }
        return redirect()->route('employee.facturacion.index')->with('info', 'El formulario de creación de facturas está en el modal.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para crear facturas.');
        }

        $rules = [
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'cliente' => 'required|string|max:255',
        ];
        $request->validate($rules);

        try {
            $client = Client::where(function ($query) use ($request) {
                $query->where('id', '=', $request->input('cliente'));
            })->first();

            if (!$client) {
                return redirect()->back()->with('error', 'Cliente no encontrado. Por favor, introduzca un cliente válido.')->withInput();
            }

            Bill::create([
                'fecha_factura' => $request->input('fecha') ? Carbon::parse($request->input('fecha')) : null,
                'monto_total' => $request->input('monto'),
                'id_cliente' => $client->id,
                'estado' => 'pendiente',
            ]);

            return redirect()->route('employee.facturacion.index')->with('success', 'Factura creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la factura: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Bill $bill)
    {
        $user = Auth::user();
        $redirectToRoute = $user->isClient() ? 'client.facturas.index' : 'employee.facturacion.index';

        if ($user->isClient()) {
            if ($bill->id_cliente !== $user->client->id) {
                return redirect()->route($redirectToRoute)->with('error', 'No tienes permiso para ver esta factura.');
            }
        } elseif (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado a facturas.');
        }

        $bill->load('client');
        return redirect()->route($redirectToRoute)->with('info', 'Los detalles de la factura se muestran en el modal.');
    }

    public function edit(Bill $bill)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para editar facturas.');
        }
        return redirect()->route('employee.facturacion.index')->with('info', 'El formulario de modificación de facturas está en el modal.');
    }

    public function update(Request $request, Bill $bill)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para actualizar facturas.');
        }

        $rules = [
            'fecha' => 'required|date_format:d/m/Y',
            'monto' => 'required|numeric|min:0',
            'cliente' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,abonado,anulado',
        ];
        $request->validate($rules);

        try {
            $client = Client::where('nombre', 'like', $request->input('cliente') . '%')
                ->orWhere('apellido', 'like', $request->input('cliente') . '%')
                ->first();

            if (!$client) {
                return redirect()->back()->with('error', 'Cliente no encontrado. Por favor, introduzca un cliente válido.')->withInput();
            }

            $bill->update([
                'fecha' => Carbon::createFromFormat('d/m/Y', $request->input('fecha')),
                'monto' => $request->input('monto'),
                'id_cliente' => $client->id,
                'estado' => $request->input('estado'),
            ]);

            return redirect()->route('employee.facturacion.index')->with('success', 'Factura actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la factura: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Bill $bill)
    {
        $user = Auth::user();
        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado para eliminar facturas.');
        }

        try {
            $bill->delete();
            return redirect()->route('employee.facturacion.index')->with('success', 'Factura eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la factura: ' . $e->getMessage());
        }
    }
}
