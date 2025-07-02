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

        <div id="panel-clientes" class="text-center mx-auto mt-5">
            <h1 class="mb-5">Panel de Gestión de Clientes</h1>
            <div class="d-flex">
                <form class="d-flex col-10" action="{{ route('admin.usuarios') }}" method="GET">
                    @csrf
                    <input type="text" name="search" class="form-control" maxlength="30"
                        placeholder="Buscar clientes" />
                    <button type="submit" id="search-btn-admin" class="btn btn-primary rounded-pill ms-2">
                        Buscar
                    </button>
                </form>
                <button type="button" id="crear-btn" class="btn btn-primary rounded-pill ms-2 text-center"
                    data-bs-toggle="modal" data-bs-target="#modal-crear-cliente">
                    Crear
                </button>
            </div>
        </div>

        <div id="client-list" class="mx-auto my-4">
            @forelse ($clients as $client)
                <div class="listed-client">
                    {{-- Link to view client details (admin.usuario-info route expects a client ID) --}}
                    <a href="{{ route('admin.usuario-info', $client->id) }}">
                        @if ($client->user && $client->user->role)
                            <span class="badge text-bg-client">Cliente</span>
                        @else
                            <span class="badge text-bg-secondary">Sin Rol</span>
                        @endif
                        {{ $client->nombre }} {{ $client->apellido }} |
                        {{ $client->user->email ?? 'Sin Correo' }}
                    </a>
                </div>
            @empty
                <div class="alert alert-info text-center" role="alert">No hay clientes registrados.</div>
            @endforelse
        </div>

        <div class="modal fade" id="modal-crear-cliente" tabindex="-1" aria-labelledby="crearClienteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearClienteModalLabel">
                            Crear usuario
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crear" action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" maxlength="30" required
                                        value="{{ old('nombre') }}" />
                                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" name="apellido" class="form-control" maxlength="30" required
                                        value="{{ old('apellido') }}" />
                                    @error('apellido') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo electrónico</label>
                                    <input type="email" name="correo" class="form-control" maxlength="50" required
                                        value="{{ old('correo') }}" />
                                    @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-7">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" name="direccion" class="form-control" maxlength="100"
                                        value="{{ old('direccion') }}" />
                                    @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-5">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" name="telefono" class="form-control" maxlength="20"
                                        value="{{ old('telefono') }}" />
                                    @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="username" class="form-label">Nombre de usuario</label>
                                    <input type="text" name="username" class="form-control" maxlength="30" required
                                        value="{{ old('username') }}" />
                                    @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="contra" class="form-label">Contraseña</label>
                                    <input type="password" name="contra" class="form-control" maxlength="30"
                                        minlength="8" required />
                                    @error('contra') <span class="text-danger">{{ $message }}</span> @enderror
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