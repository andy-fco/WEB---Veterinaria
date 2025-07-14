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

        <div id="factura-header" class="d-flex justify-content-between mx-auto">
            <h1 class="text-center my-3">Facturas</h1>
            <button type="button" id="crear-btn" class="btn btn-primary rounded-pill ms-2 text-center align-self-center"
                data-bs-toggle="modal" data-bs-target="#modal-crear-factura-empleado">
                Nueva factura
            </button>
        </div>

        <div class="modal fade" id="modal-crear-factura-empleado" tabindex="-1"
            aria-labelledby="crearFacturaEmpleadoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearFacturaEmpleadoModalLabel">
                            Nueva factura
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crear-factura-empleado" action="{{ route('employee.facturacion.store') }}"
                            method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" class="form-control" required
                                        value="{{ old('fecha') }}" />
                                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="monto" class="form-label">Monto</label>
                                    <input type="number" name="monto" class="form-control" step="0.01" min="0" required
                                        value="{{ old('monto') }}" />
                                    @error('monto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-12">
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <select name="cliente" id="cliente" class="form-select" required>
                                        <option value="">Selecciona un cliente</option>
                                        @foreach (App\Models\Client::all() as $client)
                                            <option value="{{ $client->id }}" {{ old('cliente') == $client->id ? 'selected' : '' }}>
                                                {{ $client->nombre }} {{ $client->apellido }} (ID: {{ $client->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cliente') <span class="text-danger">{{ $message }}</span> @enderror
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

        <div id="facturas-list" class="mx-auto">
            @forelse ($bills as $bill)
                <div class="card factura mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Factura #{{ $bill->id }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $bill->client->nombre ?? 'N/A' }}
                            {{ $bill->client->apellido ?? 'N/A' }}
                        </h6>
                        <div class="card-text d-flex gap-3">
                            <h6><b>Fecha:</b> {{ \Carbon\Carbon::parse($bill->fecha)->format('d/m/Y') }}</h6>
                            <h6><b>Monto</b> ${{ number_format($bill->monto_total, 0, ',', '.') }}</h6>
                            <h6><b>Estado:</b> {{ ucfirst($bill->estado) }}</h6>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#modal-modificar-factura-{{ $bill->id }}">
                                Modificar
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modal-eliminar-factura-{{ $bill->id }}">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-modificar-factura-{{ $bill->id }}" tabindex="-1"
                    aria-labelledby="modificarFacturaEmpleadoModalLabel-{{ $bill->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modificarFacturaEmpleadoModalLabel-{{ $bill->id }}">
                                    Modificar Factura #{{ $bill->id }}
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('employee.facturacion.update', $bill->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="mb-3 col-6">
                                            <label for="fecha" class="form-label">Fecha</label>
                                            <input type="date" name="fecha" class="form-control" required
                                                value="{{ old('fecha', \Carbon\Carbon::parse($bill->fecha)->format('Y-m-d')) }}" />
                                            @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label for="monto" class="form-label">Monto</label>
                                            <input type="number" name="monto" class="form-control" step="0.01" min="0"
                                                required value="{{ old('monto', $bill->monto) }}" />
                                            @error('monto') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label for="cliente" class="form-label">Cliente</label>
                                            <select name="cliente" id="cliente" class="form-select" required>
                                                <option value="">Selecciona un cliente</option>
                                                @foreach (App\Models\Client::all() as $client)
                                                    <option value="{{ $client->id }}" {{ (old('cliente', $bill->id_cliente) == $client->id) ? 'selected' : '' }}>
                                                        {{ $client->nombre }} {{ $client->apellido }} (ID: {{ $client->id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('cliente') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select name="estado" id="estado" class="form-select" required>
                                            <option value="pendiente" {{ (old('estado', $bill->estado) == 'pendiente') ? 'selected' : '' }}>Pendiente</option>
                                            <option value="abonado" {{ (old('estado', $bill->estado) == 'abonado') ? 'selected' : '' }}>Abonado</option>
                                            <option value="anulado" {{ (old('estado', $bill->estado) == 'anulado') ? 'selected' : '' }}>Anulado</option>
                                        </select>
                                        @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
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

                <div class="modal fade" id="modal-eliminar-factura-{{ $bill->id }}" tabindex="-1"
                    aria-labelledby="eliminarFacturaEmpleadoModalLabel-{{ $bill->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="eliminarFacturaEmpleadoModalLabel-{{ $bill->id }}">
                                    Eliminar Factura
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está segur@ que desea eliminar la factura #{{ $bill->id }} del cliente
                                    {{ $bill->client->nombre ?? 'N/A' }}?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    No
                                </button>
                                <form action="{{ route('employee.facturacion.destroy', $bill->id) }}" method="POST"
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
                    No se han registrado facturas en el sistema.
                </div>
            @endforelse
        </div>
    </main>
</x-app-layout>