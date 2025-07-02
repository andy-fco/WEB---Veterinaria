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

        <div id="panel-mascotas-empleado" class="text-center mx-auto mt-5">
            <h1 class="mb-5">Panel de Gestión de Mascotas</h1>
            <div class="d-flex justify-content-end">
                <button type="button" id="crear-mascota-empleado-btn" class="btn btn-primary rounded-pill ms-2 text-center"
                    data-bs-toggle="modal" data-bs-target="#modal-crear-mascota-empleado">
                    Crear Mascota
                </button>
            </div>
        </div>

        <div id="pet-list-employee" class="mx-auto my-4">
            @forelse ($pets as $pet)
                <div class="listed-pet">
                    <a href="{{ route('employee.mascotas.show', $pet->id) }}">
                        <span class="badge text-bg-info me-3">{{ $pet->especie }}</span> {{ $pet->nombre }} | {{ $pet->client->nombre ?? 'N/A' }} {{ $pet->client->apellido ?? 'N/A' }}
                    </a>
                </div>
            @empty
                <div class="alert alert-info text-center" role="alert">
                    No se han registrado mascotas en el sistema.
                </div>
            @endforelse
        </div>

        <div class="modal fade" id="modal-crear-mascota-empleado" tabindex="-1" aria-labelledby="crearMascotaEmpleadoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearMascotaEmpleadoModalLabel">
                            Registrar Nueva Mascota
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crear-mascota-empleado" action="{{ route('employee.mascotas.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" maxlength="50" required value="{{ old('nombre') }}" />
                                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="especie" class="form-label">Especie</label>
                                    <input type="text" name="especie" class="form-control" maxlength="50" required value="{{ old('especie') }}" />
                                    @error('especie') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-7">
                                    <label for="raza" class="form-label">Raza (Opcional)</label>
                                    <input type="text" name="raza" class="form-control" maxlength="50" value="{{ old('raza') }}" />
                                    @error('raza') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-5">
                                    <label for="fecha-nacimiento" class="form-label">Fecha de nacimiento</label>
                                    <input type="date" name="fecha-nacimiento" class="form-control" required value="{{ old('fecha-nacimiento') }}" />
                                    @error('fecha-nacimiento') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-7">
                                    <label for="owner" class="form-label">Dueño</label>
                                    {{-- Este 'owner' en el Blade se mapea a 'cliente_id' en el controlador.
                                        Es un select para que el empleado elija un cliente existente. --}}
                                    <select name="owner" id="owner" class="form-select" required>
                                        <option value="">Selecciona un dueño</option>
                                        @foreach (App\Models\Client::all() as $client)
                                            <option value="{{ $client->id }}" {{ old('owner') == $client->id ? 'selected' : '' }}>
                                                {{ $client->nombre }} {{ $client->apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('owner') <span class="text-danger">{{ $message }}</span> @enderror
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
    </main>
</x-app-layout>
