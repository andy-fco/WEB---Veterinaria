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

        <div id="employee-info" class="mx-auto mt-2 p-3">
            <div class="mb-3 d-flex justify-content-between">
                <a id="volver" href="{{route('admin.empleados')}}"><img src="{{asset('img/volver-icon.png')}}"
                        alt="Volver" height="30" width="30" /></a>

                <div>

                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#modificar-empleado-{{ $employee->id }}">
                        Modificar
                    </button>

                    <div class="modal fade" id="modificar-empleado-{{ $employee->id }}" tabindex="-1" aria-labelledby="modificarEmpleadoModalLabel-{{ $employee->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modificarEmpleadoModalLabel-{{ $employee->id }}">Modificar empleado</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" name="nombre" class="form-control" maxlength="30"
                                                    value="{{ old('nombre', $employee->nombre) }}" required />
                                                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="apellido" class="form-label">Apellido</label>
                                                <input type="text" name="apellido" class="form-control" maxlength="30"
                                                    value="{{ old('apellido', $employee->apellido) }}" required />
                                                @error('apellido') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="correo" class="form-label">Correo electrónico</label>
                                                <input type="email" name="correo" class="form-control" maxlength="50"
                                                    value="{{ old('correo', $employee->user->email ?? '') }}" required />
                                                @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-12">
                                                <label for="especialidad" class="form-label">Especialidad</label>
                                                <input type="text" name="especialidad" class="form-control"
                                                    maxlength="30" value="{{ old('especialidad', $employee->especialidad) }}" required />
                                                @error('especialidad') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-6">
                                                <label for="username" class="form-label">Nombre de usuario</label>
                                                <input type="text" name="username" class="form-control" maxlength="30"
                                                    value="{{ old('username', $employee->user->name ?? '') }}" required />
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
                        data-bs-target="#borrar-empleado-{{ $employee->id }}">
                        Eliminar
                    </button>
                    <div class="modal fade" id="borrar-empleado-{{ $employee->id }}" tabindex="-1" aria-labelledby="eliminarEmpleadoModalLabel-{{ $employee->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="eliminarEmpleadoModalLabel-{{ $employee->id }}">
                                        Eliminar empleado
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está segur@ que desea eliminar al empleado "{{ $employee->nombre }} {{ $employee->apellido }}"?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        No
                                    </button>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
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

            <div id="employee-info-header" class="d-flex">
                <img src="{{asset('img/user-icon.png')}}" alt="Ícono de usuario" width="50" height="50" />
                <div>
                    <h3 class="ms-3 align-self-center my-0">{{ $employee->nombre }} {{ $employee->apellido }}</h3>
                    <small class="text-muted ms-3 align-self-center">{{ $employee->user->email ?? 'Sin Correo' }}</small>
                </div>
            </div>
            <div id="employee-info-content" class="mt-3 d-flex flex-column flex-md-row gap-3">
                <ul class="no-bullets mt-2 col-md-6">
                    <li><b>Correo electrónico:</b> {{ $employee->user->email ?? 'N/A' }}</li>
                    <li><b>Especialidad:</b> {{ $employee->especialidad ?? 'N/A' }}</li>
                    <li><b>Nombre de Usuario:</b> {{ $employee->user->name ?? 'N/A' }}</li>
                    <li><b>Contraseña:</b> ********</li>
                </ul>
                {{-- Aquí podrías añadir más información específica del empleado si la tienes,
                     como turnos asignados, etc. --}}
            </div>
        </div>
    </main>
</x-app-layout>
