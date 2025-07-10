<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\DiagnosisController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get(
    '/dashboard',
    function () {
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user && $user->isEmployee()) {
            return redirect()->route('employee.dashboard');
        } elseif ($user && $user->isClient()) {
            return redirect()->route('client.dashboard');
        }

        return view('dashboard');
    }
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grupo de Rutas para Clientes 
Route::middleware(['auth', 'role:cliente'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');

    // Rutas para la gestión de mascotas por parte del cliente
    Route::get('/mascotas', [PetController::class, 'index'])->name('mascotas.index');
    Route::get('/mascotas/{pet}', [PetController::class, 'show'])->name('mascotas.show');
    Route::post('/mascotas', [PetController::class, 'store'])->name('mascotas.store');
    Route::put('/mascotas/{pet}', [PetController::class, 'update'])->name('mascotas.update');
    Route::delete('/mascotas/{pet}', [PetController::class, 'destroy'])->name('mascotas.destroy');

    // Rutas para la gestión de turnos por parte del cliente
    Route::get('/turnos', [AppointmentController::class, 'index'])->name('turnos.index');
    Route::post('/turnos', [AppointmentController::class, 'store'])->name('turnos.store');
    Route::get('/turnos/{appointment}', [AppointmentController::class, 'show'])->name('turnos.show');
    Route::put('/turnos/{appointment}', [AppointmentController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{appointment}', [AppointmentController::class, 'destroy'])->name('turnos.destroy');

    // Rutas para la gestión de facturas por parte del cliente
    Route::get('/facturas', [BillController::class, 'index'])->name('facturas.index');
    Route::get('/facturas/{bill}', [BillController::class, 'show'])->name('facturas.show');

});

// Grupo de Rutas para Empleados
Route::middleware(['auth', 'role:empleado'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    })->name('dashboard');

    // Rutas para la gestión de mascotas por parte del empleado
    Route::get('/mascotas', [PetController::class, 'index'])->name('mascotas.index');
    Route::get('/mascotas/{pet}', [PetController::class, 'show'])->name('mascotas.show');
    Route::post('/mascotas', [PetController::class, 'store'])->name('mascotas.store');
    Route::put('/mascotas/{pet}', [PetController::class, 'update'])->name('mascotas.update');
    Route::delete('/mascotas/{pet}', [PetController::class, 'destroy'])->name('mascotas.destroy');

    // Rutas para la gestión de turnos por parte del empleado
    Route::get('/turnos', [AppointmentController::class, 'index'])->name('turnos.index');
    Route::post('/turnos', [AppointmentController::class, 'store'])->name('turnos.store');
    Route::get('/turnos/{appointment}', [AppointmentController::class, 'show'])->name('turnos.show');
    Route::put('/turnos/{appointment}', [AppointmentController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{appointment}', [AppointmentController::class, 'destroy'])->name('turnos.destroy');

    // Rutas para la gestión de diagnósticos
    Route::get('/diagnosticos', [DiagnosisController::class, 'index'])->name('diagnosticos.index');
    Route::post('/diagnosticos', [DiagnosisController::class, 'store'])->name('diagnosticos.store');
    Route::get('/diagnosticos/{diagnosis}', [DiagnosisController::class, 'show'])->name('diagnosticos.show');
    Route::put('/diagnosticos/{diagnosis}', [DiagnosisController::class, 'update'])->name('diagnosticos.update');
    Route::delete('/diagnosticos/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('diagnosticos.destroy');

    // Rutas para la gestión de facturación por parte del empleado
    Route::get('/facturacion', [BillController::class, 'index'])->name('facturacion.index');
    Route::post('/facturacion', [BillController::class, 'store'])->name('facturacion.store');
    Route::get('/facturacion/{bill}', [BillController::class, 'show'])->name('facturacion.show');
    Route::put('/facturacion/{bill}', [BillController::class, 'update'])->name('facturacion.update');
    Route::delete('/facturacion/{bill}', [BillController::class, 'destroy'])->name('facturacion.destroy');

    // Rutas para la gestión de vacunas (generalmente asociadas a mascotas)
    Route::post('/vacunas', [VaccineController::class, 'store'])->name('vacunas.store');
    Route::get('/vacunas/{vaccine}', [VaccineController::class, 'show'])->name('vacunas.show');
    Route::put('/vacunas/{vaccine}', [VaccineController::class, 'update'])->name('vacunas.update');
    Route::delete('/vacunas/{vaccine}', [VaccineController::class, 'destroy'])->name('vacunas.destroy');
});

// Grupo de Rutas para Administradores
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rutas para ClientController
    Route::get('/usuarios', [ClientController::class, 'index'])->name('usuarios');
    Route::get('/usuario-info/{client}', [ClientController::class, 'show'])->name('usuario-info');
    Route::post('/usuarios', [ClientController::class, 'store'])->name('users.store');
    Route::put('/usuarios/{client}', [ClientController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{client}', [ClientController::class, 'destroy'])->name('users.destroy');

    // Rutas para EmployeeController
    Route::get('/empleados', [EmployeeController::class, 'index'])->name('empleados');
    Route::get('/empleado-info/{employee}', [EmployeeController::class, 'show'])->name('empleado-info');
    Route::post('/empleados', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/empleados/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/empleados/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Rutas para Reportes (sin controlador CRUD, vista directa)
    /*  Route::get('/reportes', function () {
          return view('admin.reportes');
      })->name('reportes');        aca cambie la ruta para que muestre directamente la funcion showReports 
                                   como para que actualice bien los datos antes de mostrar la vista*/
    Route::get('/reportes', [AdministratorController::class, 'showReports'])->name('reportes');
});


require __DIR__ . '/auth.php';
