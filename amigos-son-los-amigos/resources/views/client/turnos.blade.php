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
            <h1 class="text-center my-3">Mis turnos</h1>
            <button type="button" id="crear-btn" class="btn btn-primary rounded-pill ms-2 text-center align-self-center"
                data-bs-toggle="modal" data-bs-target="#modal-crear-turno-cliente">
                Nuevo turno
            </button>
        </div>

        <div class="modal fade" id="modal-crear-turno-cliente" tabindex="-1"
            aria-labelledby="crearTurnoClienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearTurnoClienteModalLabel">
                            Solicitar Nuevo Turno
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crear-turno-cliente" action="{{ route('client.turnos.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="fecha" class="form-label">Fecha y Hora</label>
                                    {{-- Ajustado a tipo datetime-local para mejor UX --}}
                                    <input type="datetime-local" name="fecha" class="form-control" required
                                        value="{{ old('fecha') ? \Carbon\Carbon::parse(old('fecha'))->format('Y-m-d\TH:i') : '' }}" />
                                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="mascota" class="form-label">Mascota</label>
                                    <select name="mascota" id="mascota" class="form-select" required>
                                        <option value="">Selecciona tu mascota</option>
                                        @php
                                            // Obtener las mascotas del cliente autenticado
                                            $user = Auth::user();
                                            $pets = $user->client->pets ?? collect();
                                        @endphp
                                        @foreach ($pets as $pet)
                                            <option value="{{ $pet->id }}" {{ old('mascota') == $pet->id ? 'selected' : '' }}>
                                                {{ $pet->nombre }} ({{ $pet->especie }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mascota') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-12">
                                    <label for="motivo" class="form-label">Motivo (opcional)</label>
                                    <input type="text" name="motivo" class="form-control" maxlength="255"
                                        value="{{ old('motivo') }}" />

                                    @error('motivo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="id_empleado" class="form-label">Profesional (Opcional)</label>
                                <select name="id_empleado" id="id_empleado" class="form-select">
                                    <option value="">Cualquier profesional</option>
                                    @php
                                        $employees = App\Models\Employee::all();
                                    @endphp
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('id_empleado') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->nombre }} {{ $employee->apellido }}
                                            ({{ $employee->especialidad }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_empleado') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            <div class="row justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">
                                    Solicitar
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
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $appointment->client->nombre ?? 'N/A' }}
                                {{ $appointment->client->apellido ?? 'N/A' }}
                            </h6>
                            <div class="card-text d-flex gap-3">
                                <h6><b>Fecha:</b>
                                    {{ \Carbon\Carbon::parse($appointment->fecha_turno)->format('d/m/Y H:i') }}</h6>
                                <h6><b>Mascota:</b> {{ $appointment->pet->nombre ?? 'N/A' }}</h6>
                                <h6><b>Profesional:</b> {{ $appointment->employee->nombre ?? 'N/A' }}
                                    {{ $appointment->employee->apellido ?? 'N/A' }}
                                </h6>
                                <h6><b>Estado:</b> {{ ucfirst($appointment->estado) }}</h6>
                            </div>
                            {{-- Si tienes el motivo en el modelo Appointment o una relación --}}
                            @if ($appointment->motivo)
                                <div>
                                    <p><b>Motivo:</b> {{ $appointment->motivo }}</p>
                                </div>
                            @endif


                            {{-- Botones de acción para el cliente --}}
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                @if ($appointment->estado === 'pendiente' || $appointment->estado === 'confirmado')
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#modal-modificar-turno-{{ $appointment->id }}">
                                        Modificar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#modal-cancelar-turno-{{ $appointment->id }}">
                                        Cancelar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-modificar-turno-{{ $appointment->id }}" tabindex="-1"
                        aria-labelledby="modificarTurnoClienteModalLabel-{{ $appointment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5"
                                        id="modificarTurnoClienteModalLabel-{{ $appointment->id }}">
                                        Modificar Turno #{{ $appointment->id }}
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('client.turnos.update', $appointment->id) }}" method="POST">
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
                                                <label for="mascota" class="form-label">Mascota</label>
                                                <select name="mascota" id="mascota" class="form-select" required>
                                                    <option value="">Selecciona tu mascota</option>
                                                    @php
                                                        $user = Auth::user();
                                                        $pets = $user->client->pets ?? collect();
                                                    @endphp
                                                    @foreach ($pets as $pet)
                                                        <option value="{{ $pet->id }}" {{ (old('mascota', $appointment->id_mascota) == $pet->id) ? 'selected' : '' }}>
                                                            {{ $pet->nombre }} ({{ $pet->especie }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('mascota') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_empleado" class="form-label">Profesional (Opcional)</label>
                                            <select name="id_empleado" id="id_empleado" class="form-select">
                                                <option value="">Cualquier profesional</option>
                                                @php
                                                    $employees = App\Models\Employee::all();
                                                @endphp
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ (old('id_empleado', $appointment->id_empleado) == $employee->id) ? 'selected' : '' }}>
                                                        {{ $employee->nombre }} {{ $employee->apellido }}
                                                        ({{ $employee->especialidad }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_empleado') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-12">
                                                <label for="motivo" class="form-label">Motivo (Opcional)</label>
                                                <input type="text" name="motivo" class="form-control" maxlength="255"
                                                    value="{{ old('motivo', $appointment->motivo ?? '') }}" />
                                                @error('motivo') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="row justify-content-end">
                                            <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">
                                                Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-cancelar-turno-{{ $appointment->id }}" tabindex="-1"
                        aria-labelledby="cancelarTurnoClienteModalLabel-{{ $appointment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="cancelarTurnoClienteModalLabel-{{ $appointment->id }}">
                                        Cancelar Turno
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está segur@ que desea cancelar el turno del
                                        {{ \Carbon\Carbon::parse($appointment->fecha_turno)->format('d/m/Y H:i') }} para
                                        {{ $appointment->pet->nombre ?? 'N/A' }}?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        No
                                    </button>
                                    <form action="{{ route('client.turnos.destroy', $appointment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            Sí, Cancelar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center" role="alert">
                        No tienes turnos agendados. ¡Solicita uno ahora!
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</x-app-layout>