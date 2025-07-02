<x-app-layout>
    <header class="py-4 text-center bg-primary text-white mb-5">
        <div class="container">
            <h1 class="fw-bold">Reportes y Estadísticas</h1>
            <p class="lead">
                Visualizá el estado actual de la clínica en tiempo real.
            </p>
        </div>
    </header>

    <section class="container mb-5">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold">Pacientes activos</h5>
                    <h2 class="text-primary">120</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold">Turnos agendados</h5>
                    <h2 class="text-success">35</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold">Empleados activos</h5>
                    <h2 class="text-info">6</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold text-center mb-3">Facturas de este mes</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Factura Número 29
                            <span class="badge bg-primary rounded-pill">$5.400</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Factura Número 30
                            <span class="badge bg-primary rounded-pill">$13.250</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Factura Número 31
                            <span class="badge bg-primary rounded-pill">$8.700</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Factura Número 32
                            <span class="badge bg-primary rounded-pill">$23.900</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold text-center mb-3">Turnos de esta semana</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Lunes - Juan Pérez
                            <span class="badge bg-success rounded-pill">10:00 hs</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Martes - Luciana Ortiz
                            <span class="badge bg-success rounded-pill">14:30 hs</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Jueves - Javier Ruiz
                            <span class="badge bg-success rounded-pill">09:00 hs</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>