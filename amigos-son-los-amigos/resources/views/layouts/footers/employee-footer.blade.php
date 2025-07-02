<footer class="bg-soft text-dark bg-naranja py-4 mt-5 shadow-sm">
    <div class="container">
        <div class="d-flex mb-3 gap-5">
            <a href="{{ route('employee.dashboard') }}" class="text-white text-decoration-none footer-link">
                <h5>Inicio</h5>
            </a>
            <a href="{{route('employee.mascotas.index')}}" class="text-white text-decoration-none footer-link">
                <h5>Mascotas</h5>
            </a>
            <a href="{{ route('employee.diagnosticos.index') }}" class="text-white text-decoration-none footer-link">
                <h5>Diagnósticos</h5>
            </a>
            <a href="{{route('employee.facturacion.index')}}" class="text-white text-decoration-none footer-link">
                <h5>Facturas</h5>
            </a>
            <a href="{{route('employee.turnos.index')}}" class="text-white text-decoration-none footer-link">
                <h5>Turnos</h5>
            </a>
        </div>
        <p class="mt-2 fw-bold text-center">
            Amigos son los amigos &copy; 2025
        </p>
    </div>
</footer>