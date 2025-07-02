<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::with('user')->get();
        return view('admin.empleados', compact('employees'));
    }

    public function create()
    {
        return redirect()->route('admin.empleados')->with('info', 'El formulario de creación de empleados está en el modal de la página de empleados.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:30',
            'apellido' => 'required|string|max:30',
            'correo' => 'required|string|email|max:50|unique:users,email',
            'especialidad' => 'required|string|max:30',
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
                'rol_id' => 2, 
            ]);

            Employee::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'especialidad' => $request->especialidad,
                'user_id' => $user->id,
            ]);

            return redirect()->route('admin.empleados')->with('success', 'Empleado creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el empleado: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Employee $employee)
    {
        $employee->load('user'); 
        return view('admin.empleado-info', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('admin.empleado-info', compact('employee'))->with('info', 'El formulario de modificación está en el modal de la página de información del empleado.');
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nombre' => 'required|string|max:30',
            'apellido' => 'required|string|max:30',
            'correo' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users', 'email')->ignore($employee->user->id),
            ],
            'especialidad' => 'required|string|max:30',
            'username' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'name')->ignore($employee->user->id),
            ],
            'contra' => 'nullable|string|min:8|max:30',
        ], [
            'correo.unique' => 'Ya existe un usuario con este correo electrónico.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'contra.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            $user = $employee->user;

            $user->name = $request->username;
            $user->email = $request->correo;
            if ($request->filled('contra')) {
                $user->password = Hash::make($request->contra);
            }
            $user->save();

            $employee->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'especialidad' => $request->especialidad,
            ]);

            return redirect()->route('admin.empleados')->with('success', 'Empleado y usuario actualizados exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el empleado y usuario: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            $user = $employee->user;

            if ($user) {
                $user->delete(); 
            } else {
                $employee->delete(); 
            }

            return redirect()->route('admin.empleados')->with('success', 'Empleado y usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el empleado y usuario: ' . $e->getMessage());
        }
    }
}
