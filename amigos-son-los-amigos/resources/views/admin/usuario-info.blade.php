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
                {{-- Back button to the client list --}}
                <a id="volver" href="{{ route('admin.usuarios') }}"><img src="{{ asset('img/volver-icon.png') }}"
                        alt="Volver" height="30" width="30" /></a>

                <div>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modificar-cliente-{{ $client->id }}">
                        Modificar
                    </button>

                    <div class="modal fade" id="modificar-cliente-{{ $client->id }}" tabindex="-1" aria-labelledby="modificarClienteModalLabel-{{ $client->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modificarClienteModalLabel-{{ $client->id }}">Modificar usuario</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.users.update', $client->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" name="nombre" class="form-control" maxlength="30"
                                                    value="{{ old('nombre', $client->nombre) }}" required />
                                                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="apellido" class="form-label">Apellido</label>
                                                <input type="text" name="apellido" class="form-control" maxlength="30"
                                                    value="{{ old('apellido', $client->apellido) }}" required />
                                                @error('apellido') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="correo" class="form-label">Correo electrónico</label>
                                                <input type="email" name="correo" class="form-control" maxlength="50"
                                                    value="{{ old('correo', $client->user->email ?? '') }}" required />
                                                @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-7">
                                                <label for="direccion" class="form-label">Dirección</label>
                                                <input type="text" name="direccion" class="form-control" maxlength="100"
                                                    value="{{ old('direccion', $client->direccion) }}" required />
                                                @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="telefono" class="form-label">Teléfono</label>
                                                <input type="text" name="telefono" class="form-control" maxlength="20"
                                                    value="{{ old('telefono', $client->telefono) }}" required />
                                                @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="username" class="form-label">Nombre de usuario</label>
                                                <input type="text" name="username" class="form-control" maxlength="30"
                                                    value="{{ old('username', $client->user->name ?? '') }}" required />
                                                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="contra" class="form-label">Contraseña</label>
                                                <input type="password" name="contra" class="form-control" maxlength="30"
                                                    minlength="8" /> 
                                                <small class="text-muted">Dejar en blanco para no cambiar la contraseña.</small>
                                                @error('contra') <span class="text-danger">{{ $message }}</span> @enderror
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
                        data-bs-target="#borrar-usuario-{{ $client->id }}">
                        Eliminar
                    </button>
                    <div class="modal fade" id="borrar-usuario-{{ $client->id }}" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel-{{ $client->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="eliminarUsuarioModalLabel-{{ $client->id }}">
                                        Eliminar usuario
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está segur@ que desea eliminar al usuario "{{ $client->nombre }} {{ $client->apellido }}"?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        No
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $client->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE') {{-- IMPORTANT: Use DELETE method for deletion --}}
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
                <img src="{{ asset('img/user-icon.png') }}" alt="Ícono de usuario" width="50" height="50" />
                <div>
                    <h3 class="ms-3 align-self-center my-0">{{ $client->nombre }} {{ $client->apellido }}</h3>
                    <small class="text-muted ms-3 align-self-center">{{ $client->user->email ?? 'Sin Correo' }}</small>
                </div>
            </div>
            <div id="user-info-content" class="mt-3 d-flex flex-column flex-md-row gap-3">
                <ul class="no-bullets mt-2 col-md-6">
                    <li><b>Correo electrónico:</b> {{ $client->user->email ?? 'N/A' }}</li>
                    <li><b>Dirección:</b> {{ $client->direccion ?? 'N/A' }}</li>
                    <li><b>Teléfono:</b> {{ $client->telefono ?? 'N/A' }}</li>
                    <li><b>Nombre de Usuario:</b> {{ $client->user->name ?? 'N/A' }}</li>
                    <li><b>Contraseña:</b> ********</li> {{-- Password is never displayed for security --}}
                </ul>

                <div class="col-md-6">
                    <h5 class="text-center">Mascotas</h5>
                    <ul>
                        @forelse ($client->pets as $pet)
                            <li>{{ $pet->nombre }} [{{ $pet->especie }}]</li>
                        @empty
                            <li>No hay mascotas registradas para este cliente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
