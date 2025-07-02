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

        <h1 class="text-center m-3">Diagnósticos</h1>

        <div id="diagnosticos-list" class="mx-auto">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" id="crear-diagnostico-btn" class="btn btn-primary rounded-pill text-center"
                    data-bs-toggle="modal" data-bs-target="#modal-crear-diagnostico">
                    Nuevo diagnóstico
                </button>
            </div>

            @forelse ($diagnoses as $diagnosis)
                <div class="card diagnostico mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $diagnosis->pet->nombre ?? 'N/A' }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">
                            Diagnóstico #{{ $diagnosis->id }}
                        </h6>
                        <div class="card-text d-flex gap-3">
                            <h6><b>Dueño:</b> {{ $diagnosis->pet->client->nombre ?? 'N/A' }}
                                {{ $diagnosis->pet->client->apellido ?? 'N/A' }}
                            </h6>
                            <h6><b>Fecha:</b> {{ \Carbon\Carbon::parse($diagnosis->fecha)->format('d/m/Y') }}</h6>
                        </div>
                        <p>
                            <b>Diagnóstico:</b> {{ $diagnosis->id }}
                        </p>
                        <p>
                            <b>Descripción:</b> {{ $diagnosis->descripcion ?? 'N/A' }}
                        </p>
                        <p>
                            <b>Tratamiento:</b> {{ $diagnosis->tratamiento ?? 'N/A' }}
                        </p>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#modal-modificar-diagnostico-{{ $diagnosis->id }}">
                                Modificar
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modal-eliminar-diagnostico-{{ $diagnosis->id }}">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-modificar-diagnostico-{{ $diagnosis->id }}" tabindex="-1"
                    aria-labelledby="modificarDiagnosticoModalLabel-{{ $diagnosis->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modificarDiagnosticoModalLabel-{{ $diagnosis->id }}">
                                    Modificar Diagnóstico #{{ $diagnosis->id }}
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('employee.diagnosticos.update', $diagnosis->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="mb-3 col-6">
                                            <label for="Mascota" class="form-label">Mascota</label>
                                            <select name="Mascota" id="Mascota" class="form-select" required>
                                                <option value="">Selecciona una mascota</option>
                                                @foreach (App\Models\Pet::all() as $pet)
                                                    <option value="{{ $pet->id }}" {{ (old('Mascota', $diagnosis->id_mascota) == $pet->id) ? 'selected' : '' }}>
                                                        {{ $pet->nombre }} ({{ $pet->client->nombre ?? 'N/A' }}
                                                        {{ $pet->client->apellido ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('Mascota') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label for="Consulta" class="form-label">Consulta</label>
                                            <input type="text" name="Consulta" class="form-control" maxlength="255"
                                                value="{{ old('Consulta', $diagnosis->titulo) }}" required />
                                            @error('Consulta') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea name="descripcion" id="descripcion"
                                                class="form-control">{{ old('descripcion', $diagnosis->descripcion) }}</textarea>
                                            @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="tratamiento" class="form-label">Tratamiento</label>
                                            <textarea name="tratamiento" id="tratamiento"
                                                class="form-control">{{ old('tratamiento', $diagnosis->tratamiento) }}</textarea>
                                            @error('tratamiento') <span class="text-danger">{{ $message }}</span> @enderror
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

                <div class="modal fade" id="modal-eliminar-diagnostico-{{ $diagnosis->id }}" tabindex="-1"
                    aria-labelledby="eliminarDiagnosticoModalLabel-{{ $diagnosis->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="eliminarDiagnosticoModalLabel-{{ $diagnosis->id }}">
                                    Eliminar Diagnóstico
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está segur@ que desea eliminar el diagnóstico #{{ $diagnosis->id }} de
                                    {{ $diagnosis->pet->nombre ?? 'N/A' }}?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    No
                                </button>
                                <form action="{{ route('employee.diagnosticos.destroy', $diagnosis->id) }}" method="POST"
                                    style="display:inline;">
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
                    No se han registrado diagnósticos.
                </div>
            @endforelse
        </div>

        <div class="modal fade" id="modal-crear-diagnostico" tabindex="-1" aria-labelledby="crearDiagnosticoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearDiagnosticoModalLabel">
                            Nuevo Diagnóstico
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crear-diagnostico" action="{{ route('employee.diagnosticos.store') }}"
                            method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="Mascota" class="form-label">Mascota</label>
                                    <select name="Mascota" id="Mascota" class="form-select" required>
                                        <option value="">Selecciona una mascota</option>
                                        @foreach (App\Models\Pet::with('client')->get() as $pet)
                                            <option value="{{ $pet->id }}" {{ old('Mascota') == $pet->id ? 'selected' : '' }}>
                                                {{ $pet->nombre }} ({{ $pet->client->nombre ?? 'N/A' }}
                                                {{ $pet->client->apellido ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Mascota') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea name="descripcion" id="descripcion"
                                        class="form-control">{{ old('descripcion') }}</textarea>
                                    @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3">
                                    <label for="tratamiento" class="form-label">Tratamiento</label>
                                    <textarea name="tratamiento" id="tratamiento"
                                        class="form-control">{{ old('tratamiento') }}</textarea>
                                    @error('tratamiento') <span class="text-danger">{{ $message }}</span> @enderror
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