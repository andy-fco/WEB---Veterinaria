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


{{-- NUEVA SECCIÓN AGREGADA PARA LOS GRÁFICOS --}}
    <section class="container mb-5">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold text-center mb-3">Monto Facturado Mensual </h5>
                    <canvas id="monthlyBillsChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold text-center mb-3">Mascotas Registradas por Especie</h5>
                    <canvas id="petsBySpeciesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </section>
    {{-- FIN DE LA NUEVA SECCIÓN DE GRÁFICOS --}}


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


  {{-- SCRIPTS PARA LOS GRÁFICOS --}}
    @push('scripts')
    <script>
        // Datos para el gráfico "Monto Facturado Mensual"
        const billMonths = @json($billMonths); //estos errores hay que ignorarlos, 
                                             // son solo advertencias porque visual peinsa que todo el codigo del archivo es js
        const billAmounts = @json($billAmounts);

        const monthlyBillsCtx = document.getElementById('monthlyBillsChart').getContext('2d');
        new Chart(monthlyBillsCtx, {
            type: 'line',
            data: {
                labels: billMonths,
                datasets: [{
                    label: 'Monto Total Facturado ($)',
                    data: billAmounts,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mes'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + parseFloat(context.parsed.y).toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        });

        // Datos para el gráfico "Mascotas Registradas por Especie"
        const speciesLabels = @json($speciesLabels);
        const speciesCounts = @json($speciesCounts);

        const petsBySpeciesCtx = document.getElementById('petsBySpeciesChart').getContext('2d');
        new Chart(petsBySpeciesCtx, {
            type: 'doughnut',
            data: {
                labels: speciesLabels,
                datasets: [{
                    label: 'Cantidad de Mascotas',
                    data: speciesCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed) {
                                    const total = context.dataset.data.reduce((sum, current) => sum + current, 0);
                                    const percentage = (context.parsed / total * 100).toFixed(1);
                                    label += context.parsed + ' (' + percentage + '%)';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush


    
</x-app-layout>