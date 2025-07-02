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

        <h1 class="text-center my-3">Facturas</h1>
        <div id="facturas" class="mx-auto">
            @forelse ($bills as $bill)
                <div class="card factura mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Factura #{{ $bill->id }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $bill->client->nombre ?? 'N/A' }} {{ $bill->client->apellido ?? 'N/A' }}</h6>
                        <div class="card-text d-flex gap-3">
                            <h6><b>Fecha:</b> {{ \Carbon\Carbon::parse($bill->fecha)->format('d/m/Y') }}</h6>
                            <h6><b>Monto</b> ${{ number_format($bill->monto, 0, ',', '.') }}</h6>
                            <h6><b>Estado:</b> {{ ucfirst($bill->estado) }}</h6>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center" role="alert">
                    No se han registrado facturas para tus mascotas.
                </div>
            @endforelse
        </div>
    </main>
</x-app-layout>
