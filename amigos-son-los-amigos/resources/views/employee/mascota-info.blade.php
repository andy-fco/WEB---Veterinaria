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
            <div class="mb-3 d-flex justify-content-between">
                <a id="volver" href="{{route('employee.mascotas.index')}}"><img src="{{asset('img/volver-icon.png')}}"
                        alt="Volver" height="30" width="30" /></a>

                <div>
                    {{-- Botón para Modificar --}}
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modal-modificar-mascota-empleado-{{ $pet->id }}">
                        Modificar
                    </button>

                    <div class="modal fade" id="modal-modificar-mascota-empleado-{{ $pet->id }}" tabindex="-1"
                        aria-labelledby="modificarMascotaEmpleadoModalLabel-{{ $pet->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modificarMascotaEmpleadoModalLabel-{{ $pet->id }}">
                                        Modificar Mascota
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('employee.mascotas.update', $pet->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="especie" class="form-label">Especie</label>
                                                <input type="text" name="especie" class="form-control" maxlength="30"
                                                    value="{{ old('especie', $pet->especie) }}" required />
                                                @error('especie') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" name="nombre" class="form-control" maxlength="30"
                                                    value="{{ old('nombre', $pet->nombre) }}" required />
                                                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-7">
                                                <label for="raza" class="form-label">Raza</label>
                                                <input type="text" name="raza" class="form-control" maxlength="30"
                                                    value="{{ old('raza', $pet->raza) }}" />
                                                @error('raza') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-5">
                                                <label for="fecha-nacimiento" class="form-label">Fecha de
                                                    nacimiento</label>
                                                <input type="date" name="fecha-nacimiento" class="form-control"
                                                    value="{{ old('fecha-nacimiento', $pet->fecha_nacimiento ? \Carbon\Carbon::parse($pet->fecha_nacimiento)->format('Y-m-d') : '') }}" required />
                                                @error('fecha-nacimiento') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-7">
                                                <label for="owner" class="form-label">Dueño</label>
                                                <select name="owner" id="owner" class="form-select" required>
                                                    <option value="">Selecciona un dueño</option>
                                                    @foreach (App\Models\Client::all() as $client)
                                                        <option value="{{ $client->id }}" {{ (old('owner', $pet->cliente_id) == $client->id) ? 'selected' : '' }}>
                                                            {{ $client->nombre }} {{ $client->apellido }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('owner') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="row justify-content-end">
                                            <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">
                                                Modificar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botón para Eliminar --}}
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modal-borrar-mascota-empleado-{{ $pet->id }}">
                        Eliminar
                    </button>

                    <div class="modal fade" id="modal-borrar-mascota-empleado-{{ $pet->id }}" tabindex="-1" aria-labelledby="eliminarMascotaEmpleadoModalLabel-{{ $pet->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="eliminarMascotaEmpleadoModalLabel-{{ $pet->id }}">
                                        Eliminar mascota
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está segur@ que desea eliminar la mascota "{{ $pet->nombre }}" ({{ $pet->especie }}) de {{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        No
                                    </button>
                                    <form action="{{ route('employee.mascotas.destroy', $pet->id) }}" method="POST" style="display:inline;">
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
                </div>
            </div>

            <div id="user-info-header" class="d-flex">
                <img src="{{asset('img/pawprint.png')}}" alt="Ícono de pata" width="50" height="50" />
                <div>
                    <h3 class="ms-3 align-self-center my-0">{{ $pet->nombre }}</h3>
                    <small class="text-muted ms-3 align-self-center">{{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}</small>
                </div>
            </div>
            <div id="user-info-content" class="mt-3 d-flex flex-column flex-md-row gap-3">
                <ul class="no-bullets mt-2 col-md-6">
                    <li><b>Especie:</b> {{ $pet->especie }}</li>
                    <li><b>Raza:</b> {{ $pet->raza ?? 'N/A' }}</li>
                    <li><b>Fecha de nacimiento:</b> {{ \Carbon\Carbon::parse($pet->fecha_nacimiento)->format('d/m/Y') }}</li>
                    <li><b>Dueño:</b> {{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}</li>
                </ul>

                <div class="col-md-6">
                    <h5 class="text-center">Turnos</h5>
                    @forelse ($pet->appointments as $appointment)
                        <p class="text-secondary">Turno el {{ \Carbon\Carbon::parse($appointment->fecha_turno)->format('d/m/Y H:i') }} (Estado: {{ ucfirst($appointment->estado) }})</p>
                    @empty
                        <p class="text-secondary">No se registran turnos próximos para esta mascota.</p>
                    @endforelse

                    <h5 class="text-center">Diagnósticos</h5>
                    @forelse ($pet->consultations as $consultation)
                        <p class="text-secondary">
                            Diagnóstico del {{ \Carbon\Carbon::parse($consultation->fecha)->format('d/m/Y') }} - {{ $consultation->titulo }}
                            <br>Descripción: {{ $consultation->descripcion }}
                            <br>Tratamiento: {{ $consultation->tratamiento }}
                        </p>
                    @empty
                        <p class="text-secondary">No hay diagnósticos disponibles para esta mascota.</p>
                    @endforelse

                    <h5 class="text-center">Vacunas</h5>
                    <ul>
                        @forelse ($pet->vaccines as $vaccine)
                            <li>{{ $vaccine->nombre }} - {{ \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('d/m/Y') }}
                                <div class="d-flex justify-content-end gap-2 mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-modificar-vacuna-{{ $vaccine->id }}">
                                        Modificar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-eliminar-vacuna-{{ $vaccine->id }}">
                                        Eliminar
                                    </button>
                                </div>
                            </li>
 
                            <div class="modal fade" id="modal-modificar-vacuna-{{ $vaccine->id }}" tabindex="-1" aria-labelledby="modificarVacunaModalLabel-{{ $vaccine->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modificarVacunaModalLabel-{{ $vaccine->id }}">
                                                Modificar Vacuna
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('employee.vacunas.update', $vaccine->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_mascota" value="{{ $pet->id }}">
                                                <div class="mb-3">
                                                    <label for="nombre" class="form-label">Nombre de Vacuna</label>
                                                    <input type="text" name="nombre" class="form-control" maxlength="100" required value="{{ old('nombre', $vaccine->nombre) }}" />
                                                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fecha_aplicacion" class="form-label">Fecha de Aplicación</label>
                                                    <input type="date" name="fecha_aplicacion" class="form-control" required value="{{ old('fecha_aplicacion', \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('Y-m-d')) }}" />
                                                    @error('fecha_aplicacion') <span class="text-danger">{{ $message }}</span> @enderror
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

                            <div class="modal fade" id="modal-eliminar-vacuna-{{ $vaccine->id }}" tabindex="-1" aria-labelledby="eliminarVacunaModalLabel-{{ $vaccine->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="eliminarVacunaModalLabel-{{ $vaccine->id }}">
                                                Eliminar Vacuna
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Está segur@ que desea eliminar la vacuna "{{ $vaccine->nombre }}" aplicada el {{ \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('d/m/Y') }}?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                No
                                            </button>
                                            <form action="{{ route('employee.vacunas.destroy', $vaccine->id) }}" method="POST" style="display:inline;">
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
                            <li>No hay vacunas registradas para esta mascota.</li>
                        @endforelse
                    </ul>

                    {{-- Botón para Añadir Vacuna --}}
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-primary rounded-pill btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-crear-vacuna-{{ $pet->id }}">
                            Añadir Vacuna
                        </button>
                    </div>

                    <div class="modal fade" id="modal-crear-vacuna-{{ $pet->id }}" tabindex="-1" aria-labelledby="crearVacunaModalLabel-{{ $pet->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="crearVacunaModalLabel-{{ $pet->id }}">
                                        Registrar Nueva Vacuna
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('employee.vacunas.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_mascota" value="{{ $pet->id }}">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre de Vacuna</label>
                                            <input type="text" name="nombre" class="form-control" maxlength="100" required value="{{ old('nombre') }}" />
                                            @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_aplicacion" class="form-label">Fecha de Aplicación</label>
                                            <input type="date" name="fecha_aplicacion" class="form-control" required value="{{ old('fecha_aplicacion') }}" />
                                            @error('fecha_aplicacion') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="row justify-content-end">
                                            <button type="submit" class="btn btn-primary rounded-pill col-3 mx-2">
                                                Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
