<x-app-layout>
    <main class="p-2">
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

        <div id="user-info" class="mx-auto mt-2 p-3">

            {{-- Botones superiores --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('employee.mascotas.index') }}">
                    <img src="{{ asset('img/volver-icon.png') }}" alt="Volver" height="30" width="30" />
                </a>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modal-modificar-mascota-empleado-{{ $pet->id }}">
                        Modificar
                    </button>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modal-borrar-mascota-empleado-{{ $pet->id }}">
                        Eliminar
                    </button>
                </div>
            </div>

            {{-- Header --}}
            <div id="user-info-header" class="d-flex">
                <img src="{{ asset('img/pawprint.png') }}" alt="Ícono de pata" width="50" height="50" />
                <div>
                    <h3 class="ms-3 align-self-center my-0">{{ $pet->nombre }}</h3>
                    <small class="text-muted ms-3 align-self-center">
                        {{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}
                    </small>
                </div>
            </div>

            {{-- Contenido --}}
            <div id="user-info-content" class="mt-3 d-flex flex-column flex-md-row gap-3">
                <ul class="no-bullets mt-2 col-md-6">
                    <li><b>Especie:</b> {{ $pet->especie }}</li>
                    <li><b>Raza:</b> {{ $pet->raza ?? 'N/A' }}</li>
                    <li><b>Fecha de nacimiento:</b> {{ \Carbon\Carbon::parse($pet->fecha_nacimiento)->format('d/m/Y') }}
                    </li>
                    <li><b>Dueño:</b> {{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}</li>
                </ul>

                <div class="col-md-6">
                    <h5 class="text-center">Turnos</h5>
                    @forelse ($pet->appointments as $appointment)
                        <p class="text-secondary">Turno el
                            {{ \Carbon\Carbon::parse($appointment->fecha_turno)->format('d/m/Y H:i') }} (Estado:
                            {{ ucfirst($appointment->estado) }})</p>
                    @empty
                        <p class="text-secondary">No se registran turnos próximos para esta mascota.</p>
                    @endforelse

                    <h5 class="text-center">Diagnósticos</h5>
                    @forelse ($pet->diagnoses as $diagnoses)
                        <p class="text-secondary">
                            Diagnóstico<br>
                            Descripción: {{ $diagnoses->descripcion }}<br>
                            Tratamiento: {{ $diagnoses->tratamiento }}
                        </p>
                    @empty
                        <p class="text-secondary">No hay diagnósticos disponibles para esta mascota.</p>
                    @endforelse

                    <h5 class="text-center">Vacunas</h5>
                    <ul>
                        @forelse ($pet->vaccines as $vaccine)
                            <li>{{ $vaccine->nombre }} -
                                {{ \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('d/m/Y') }}
                                <div class="d-flex justify-content-end gap-2 mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#modal-modificar-vacuna-{{ $vaccine->id }}">
                                        Modificar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#modal-eliminar-vacuna-{{ $vaccine->id }}">
                                        Eliminar
                                    </button>
                                </div>
                            </li>

                            {{-- Modal Modificar Vacuna --}}
                            <div class="modal fade" id="modal-modificar-vacuna-{{ $vaccine->id }}" tabindex="-1"
                                aria-labelledby="modificarVacunaModalLabel-{{ $vaccine->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Modificar Vacuna</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('employee.vacunas.update', $vaccine->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_mascota" value="{{ $pet->id }}">
                                                <div class="mb-3">
                                                    <label class="form-label">Nombre de Vacuna</label>
                                                    <input type="text" name="nombre" class="form-control" required
                                                        maxlength="100" value="{{ old('nombre', $vaccine->nombre) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha de Aplicación</label>
                                                    <input type="date" name="fecha_aplicacion" class="form-control" required
                                                        value="{{ old('fecha_aplicacion', \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('Y-m-d')) }}">
                                                </div>
                                                <div class="row justify-content-end">
                                                    <button type="submit"
                                                        class="btn btn-primary rounded-pill col-3 mx-2">Actualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Eliminar Vacuna --}}
                            <div class="modal fade" id="modal-eliminar-vacuna-{{ $vaccine->id }}" tabindex="-1"
                                aria-labelledby="eliminarVacunaModalLabel-{{ $vaccine->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Eliminar Vacuna</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Está segur@ que desea eliminar la vacuna "{{ $vaccine->nombre }}" aplicada
                                                el {{ \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('d/m/Y') }}?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">No</button>
                                            <form action="{{ route('employee.vacunas.destroy', $vaccine->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <li>No hay vacunas registradas para esta mascota.</li>
                        @endforelse
                    </ul>

                    {{-- Botón Añadir Vacuna --}}
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-primary rounded-pill btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-crear-vacuna-{{ $pet->id }}">
                            Añadir Vacuna
                        </button>
                    </div>

                    {{-- Modal Crear Vacuna --}}
                    <div class="modal fade" id="modal-crear-vacuna-{{ $pet->id }}" tabindex="-1"
                        aria-labelledby="crearVacunaModalLabel-{{ $pet->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Registrar Nueva Vacuna</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('employee.vacunas.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_mascota" value="{{ $pet->id }}">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre de Vacuna</label>
                                            <input type="text" name="nombre" class="form-control" required
                                                maxlength="100" value="{{ old('nombre') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha de Aplicación</label>
                                            <input type="date" name="fecha_aplicacion" class="form-control" required
                                                value="{{ old('fecha_aplicacion') }}">
                                        </div>
                                        <div class="row justify-content-end">
                                            <button type="submit"
                                                class="btn btn-primary rounded-pill col-3 mx-2">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- col-md-6 --}}
            </div> {{-- user-info-content --}}
        </div> {{-- user-info --}}

        {{-- Modal Modificar Mascota --}}
        <div class="modal fade" id="modal-modificar-mascota-empleado-{{ $pet->id }}" tabindex="-1"
            aria-labelledby="modificarMascotaEmpleadoModalLabel-{{ $pet->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Modificar Mascota</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('employee.mascotas.update', $pet->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label class="form-label">Especie</label>
                                    <input type="text" name="especie" class="form-control" required maxlength="30"
                                        value="{{ old('especie', $pet->especie) }}">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" required maxlength="30"
                                        value="{{ old('nombre', $pet->nombre) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-7">
                                    <label class="form-label">Raza</label>
                                    <input type="text" name="raza" class="form-control" maxlength="30"
                                        value="{{ old('raza', $pet->raza) }}">
                                </div>
                                <div class="mb-3 col-5">
                                    <label class="form-label">Fecha de nacimiento</label>
                                    <input type="date" name="fecha-nacimiento" class="form-control" required
                                        value="{{ old('fecha-nacimiento', \Carbon\Carbon::parse($pet->fecha_nacimiento)->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dueño</label>
                                <select name="owner" class="form-select" required>
                                    <option value="">Selecciona un dueño</option>
                                    @foreach (App\Models\Client::all() as $client)
                                        <option value="{{ $client->id }}" {{ (old('owner', $pet->cliente_id) == $client->id) ? 'selected' : '' }}>
                                            {{ $client->nombre }} {{ $client->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">Modificar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Eliminar Mascota --}}
        <div class="modal fade" id="modal-borrar-mascota-empleado-{{ $pet->id }}" tabindex="-1"
            aria-labelledby="eliminarMascotaEmpleadoModalLabel-{{ $pet->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Eliminar mascota</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está segur@ que desea eliminar la mascota "{{ $pet->nombre }}" ({{ $pet->especie }}) de
                            {{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <form action="{{ route('employee.mascotas.destroy', $pet->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
</x-app-layout>