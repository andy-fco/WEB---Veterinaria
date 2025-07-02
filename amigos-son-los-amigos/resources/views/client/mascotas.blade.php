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

        <h1 class="text-center my-3">Mis mascotas</h1>
        <div id="mascotas" class="mx-auto">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#modal-crear-mascota-cliente">
                    Registrar Mascota
                </button>
            </div>

            @forelse ($pets as $pet)
                <a href="{{ route('client.mascotas.show', $pet->id) }}">
                    <div class="card mascota mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $pet->nombre }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $pet->especie }}</h6>
                            <div class="card-text d-flex gap-3">
                                <h6><b>Especie:</b> {{ $pet->especie }}</h6>
                                <h6><b>Raza:</b> {{ $pet->raza ?? 'N/A' }}</h6>
                                <h6><b>Fecha de nacimiento:</b> {{ \Carbon\Carbon::parse($pet->fecha_nacimiento)->format('d/m/Y') }}</h6>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="alert alert-info text-center" role="alert">
                    Aún no tienes mascotas registradas. ¡Registra una ahora!
                </div>
            @endforelse
        </div>
    </main>

    <div class="modal fade" id="modal-crear-mascota-cliente" tabindex="-1" aria-labelledby="crearMascotaClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crearMascotaClienteModalLabel">
                        Registrar Nueva Mascota
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('client.mascotas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" maxlength="50" required value="{{ old('nombre') }}" />
                            @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="especie" class="form-label">Especie</label>
                            <input type="text" name="especie" class="form-control" maxlength="50" required value="{{ old('especie') }}" />
                            @error('especie') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="raza" class="form-label">Raza (Opcional)</label>
                            <input type="text" name="raza" class="form-control" maxlength="50" value="{{ old('raza') }}" />
                            @error('raza') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="fecha-nacimiento" class="form-label">Fecha de Nacimiento (Opcional)</label>
                            <input type="date" name="fecha-nacimiento" class="form-control" value="{{ old('fecha-nacimiento') }}" />
                            @error('fecha-nacimiento') <span class="text-danger">{{ $message }}</span> @enderror
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
</x-app-layout>
