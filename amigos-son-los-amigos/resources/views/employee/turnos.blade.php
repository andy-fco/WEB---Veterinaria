<x-app-layout>
    <main class="p-2">
        {{-- Mensajes flash de sesión --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="factura-header" class="d-flex justify-content-between mx-auto">
            <h1 class="text-center my-3">Gestión de Turnos</h1>
            <button type="button" id="crear-btn" class="btn btn-primary rounded-pill ms-2 text-center align-self-center"
                data-bs-toggle="modal" data-bs-target="#modal-crear-turno-empleado">
                Nuevo turno
            </button>
        </div>

        <div class="modal fade" id="modal-crear-turno-empleado" tabindex="-1" aria-labelledby="crearTurnoEmpleadoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearTurnoEmpleadoModalLabel">
                            Nuevo turno
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crear-turno-empleado" action="{{ route('employee.turnos.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="fecha" class="form-label">Fecha y Hora</label>
                                    <input type="datetime-local" name="fecha" class="form-control" required value="{{ old('fecha') ? \Carbon\Carbon::parse(old('fecha'))->format('Y-m-d\TH:i') : '' }}" />
                                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="id_cliente" class="form-label">Cliente</label>
                                    <select name="id_cliente" id="id_cliente" class="form-select" required>
                                        <option value="">Selecciona un cliente</option>
                                        @foreach (App\Models\Client::all() as $client)
                                            <option value="{{ $client->id }}" {{ old('id_cliente') == $client->id ? 'selected' : '' }}>
                                                {{ $client->nombre }} {{ $client->apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_cliente') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="mascota" class="form-label">Mascota</label>
                                    <select name="mascota" id="mascota" class="form-select" required>
                                        <option value="">Selecciona una mascota</option>
                                        {{-- Las mascotas deben filtrarse por el cliente seleccionado (esto requeriría JS)
                                            Por simplicidad, listamos todas las mascotas. Mejorar con JS para filtrar. --}}
                                        @foreach (App\Models\Pet::with('client')->get() as $pet)
                                            <option value="{{ $pet->id }}" {{ old('id_mascota') == $pet->id ? 'selected' : '' }}>
                                                {{ $pet->nombre }} (Dueño: {{ $pet->client->nombre ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_mascota') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="id_empleado" class="form-label">Profesional</label>
                                    <select name="id_empleado" id="id_empleado" class="form-select" required>
                                        <option value="">Selecciona un profesional</option>
                                        @foreach (App\Models\Employee::all() as $employee)
                                            <option value="{{ $employee->id }}" {{ old('id_empleado') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->nombre }} {{ $employee->apellido }} ({{ $employee->especialidad }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_empleado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-12">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="confirmado" {{ old('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                    </select>
                                    @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                             
                            

                            <div class="row justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">
                                    Crear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div id="turnos" class="mx-auto">
                @forelse ($appointments as $appointment)
                    <div class="card turno mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Turno #{{ $appointment->id }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $appointment->client->nombre ?? 'N/A' }} {{ $appointment->client->apellido ?? 'N/A' }}</h6>
                            <div class="card-text d-flex gap-3">
                                <h6><b>Fecha:</b> {{ \Carbon\Carbon::parse($appointment->fecha_turno)->format('d/m/Y H:i') }}</h6>
                                <h6><b>Mascota:</b> {{ $appointment->pet->nombre ?? 'N/A' }}</h6>
                                <h6><b>Profesional:</b> {{ $appointment->employee->nombre ?? 'N/A' }} {{ $appointment->employee->apellido ?? 'N/A' }}</h6>
                                <h6><b>Estado:</b> {{ ucfirst($appointment->estado) }}</h6>
                            </div>
                            {{-- Si tienes el motivo en el modelo Appointment o una relación --}}
                            @if ($appointment->motivo)
                                <div>
                                    <p><b>Motivo:</b> {{ $appointment->motivo }}</p>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#modal-modificar-turno-empleado-{{ $appointment->id }}">
                                    Modificar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#modal-eliminar-turno-empleado-{{ $appointment->id }}">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-modificar-turno-empleado-{{ $appointment->id }}" tabindex="-1" aria-labelledby="modificarTurnoEmpleadoModalLabel-{{ $appointment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modificarTurnoEmpleadoModalLabel-{{ $appointment->id }}">
                                        Modificar Turno #{{ $appointment->id }}
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('employee.turnos.update', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="fecha" class="form-label">Fecha y Hora</label>
                                                <input type="datetime-local" name="fecha" class="form-control" required
                                                    value="{{ old('fecha', \Carbon\Carbon::parse($appointment->fecha_turno)->format('Y-m-d\TH:i')) }}" />
                                                @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="id_cliente" class="form-label">Cliente</label>
                                                <select name="id_cliente" id="id_cliente" class="form-select" required>
                                                    <option value="">Selecciona un cliente</option>
                                                    @foreach (App\Models\Client::all() as $client)
                                                        <option value="{{ $client->id }}" {{ (old('id_cliente', $appointment->id_cliente) == $client->id) ? 'selected' : '' }}>
                                                            {{ $client->nombre }} {{ $client->apellido }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_cliente') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="id_mascota" class="form-label">Mascota</label>
                                                <select name="id_mascota" id="id_mascota" class="form-select" required>
                                                    <option value="">Selecciona una mascota</option>
                                                    @foreach (App\Models\Pet::with('client')->get() as $pet)
                                                        <option value="{{ $pet->id }}" {{ (old('id_mascota', $appointment->id_mascota) == $pet->id) ? 'selected' : '' }}>
                                                            {{ $pet->nombre }} (Dueño: {{ $pet->client->nombre ?? 'N/A' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_mascota') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="id_empleado" class="form-label">Profesional</label>
                                                <select name="id_empleado" id="id_empleado" class="form-select" required>
                                                    <option value="">Selecciona un profesional</option>
                                                    @foreach (App\Models\Employee::all() as $employee)
                                                        <option value="{{ $employee->id }}" {{ (old('id_empleado', $appointment->id_empleado) == $employee->id) ? 'selected' : '' }}>
                                                            {{ $employee->nombre }} {{ $employee->apellido }} ({{ $employee->especialidad }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_empleado') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-12">
                                                <label for="estado" class="form-label">Estado</label>
                                                <select name="estado" id="estado" class="form-select" required>
                                                    <option value="pendiente" {{ (old('estado', $appointment->estado) == 'pendiente') ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="confirmado" {{ (old('estado', $appointment->estado) == 'confirmado') ? 'selected' : '' }}>Confirmado</option>
                                                    <option value="cancelado" {{ (old('estado', $appointment->estado) == 'cancelado') ? 'selected' : '' }}>Cancelado</option>
                                                    <option value="completado" {{ (old('estado', $appointment->estado) == 'completado') ? 'selected' : '' }}>Completado</option>
                                                </select>
                                                @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        {{-- Asumo que 'motivo' es un campo que puede ir en Appointment, si no es así, eliminar --}}
                                        <div class="row">
                                            <div class="mb-3 col-12">
                                                <label for="motivo" class="form-label">Motivo (Opcional)</label>
                                                <input type="text" name="motivo" class="form-control" maxlength="255" value="{{ old('motivo', $appointment->motivo ?? '') }}" />
                                                @error('motivo') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="row justify-content-end">
                                            <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">
                                                Actualizar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-eliminar-turno-empleado-{{ $appointment->id }}" tabindex="-1" aria-labelledby="eliminarTurnoEmpleadoModalLabel-{{ $appointment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="eliminarTurnoEmpleadoModalLabel-{{ $appointment->id }}">
                                        Eliminar Turno
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está segur@ que desea eliminar el turno #{{ $appointment->id }} del cliente {{ $appointment->client->nombre ?? 'N/A' }} {{ $appointment->client->apellido ?? 'N/A' }}?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        No
                                    </button>
                                    <form action="{{ route('employee.turnos.destroy', $appointment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            Sí, Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center" role="alert">
                        No hay turnos registrados en el sistema.
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</x-app-layout>
