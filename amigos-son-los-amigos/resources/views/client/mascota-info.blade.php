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
                <a id="volver" href="{{route('client.mascotas.index')}}"><img src="{{asset('img/volver-icon.png')}}"
                        alt="Volver" height="30" width="30" /></a>

                <div>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modal-modificar-mascota-{{ $pet->id }}">
                        Modificar
                    </button>

                    <div class="modal fade" id="modal-modificar-mascota-{{ $pet->id }}" tabindex="-1"
                        aria-labelledby="modificarMascotaModalLabel-{{ $pet->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modificarMascotaModalLabel-{{ $pet->id }}">
                                        Modificar Mascota
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('client.mascotas.update', $pet->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="especie" class="form-label">Especie</label>
                                                <input type="text" name="especie" class="form-control" maxlength="30"
                                                    value="{{ old('especie', $pet->especie) }}" required />
                                                @error('especie') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" name="nombre" class="form-control" maxlength="30"
                                                    value="{{ old('nombre', $pet->nombre) }}" required />
                                                @error('nombre') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-7">
                                                <label for="raza" class="form-label">Raza</label>
                                                <input type="text" name="raza" class="form-control" maxlength="30"
                                                    value="{{ old('raza', $pet->raza) }}" />
                                                @error('raza') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-5">
                                                <label for="fecha-nacimiento" class="form-label">Fecha de
                                                    nacimiento</label>
                                                <input type="date" name="fecha-nacimiento" class="form-control"
                                                    value="{{ old('fecha-nacimiento', $pet->fecha_nacimiento ? \Carbon\Carbon::parse($pet->fecha_nacimiento)->format('Y-m-d') : '') }}"
                                                    required />
                                                @error('fecha-nacimiento') <span
                                                class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-7">
                                                <label for="owner" class="form-label">Dueño</label>
                                                <input type="text" name="owner" class="form-control" maxlength="50"
                                                    value="{{ old('owner', $pet->client->nombre . ' ' . $pet->client->apellido) }}"
                                                    disabled /> {{-- El cliente no puede cambiar el dueño --}}
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

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modal-borrar-mascota-{{ $pet->id }}">
                        Eliminar
                    </button>
                    <div class="modal fade" id="modal-borrar-mascota-{{ $pet->id }}" tabindex="-1"
                        aria-labelledby="eliminarMascotaModalLabel-{{ $pet->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="eliminarMascotaModalLabel-{{ $pet->id }}">
                                        Eliminar mascota
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está segur@ que desea eliminar la mascota "{{ $pet->nombre }}"?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        No
                                    </button>
                                    <form action="{{ route('client.mascotas.destroy', $pet->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            Sí
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
                    <small class="text-muted ms-3 align-self-center">{{ $pet->client->nombre ?? 'N/A' }}
                        {{ $pet->client->apellido ?? 'N/A' }}</small>
                </div>
            </div>
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
                            {{ ucfirst($appointment->estado) }})
                        </p>
                    @empty
                        <p class="text-secondary">No se registran turnos próximos para esta mascota.</p>
                    @endforelse

                    <h5 class="text-center">Diagnósticos</h5>
                    @forelse ($pet->diagnoses as $diagnosis)
                        <p class="text-secondary">
                            Diagnóstico del {{ \Carbon\Carbon::parse($diagnosis->fecha)->format('d/m/Y') }} -
                            {{ $diagnosis->titulo }}
                            <br>Descripción: {{ $diagnosis->descripcion }}
                            <br>Tratamiento: {{ $diagnosis->tratamiento }}
                        </p>
                    @empty
                        <p class="text-secondary">No hay diagnósticos disponibles para esta mascota.</p>
                    @endforelse

                    <h5 class="text-center">Vacunas</h5>
                    <ul>
                        @forelse ($pet->vaccines as $vaccine)
                            <li>{{ $vaccine->nombre }} -
                                {{ \Carbon\Carbon::parse($vaccine->fecha_aplicacion)->format('d/m/Y') }}
                            </li>
                        @empty
                            <li>No hay vacunas registradas para esta mascota.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>