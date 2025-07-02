<x-app-layout>


    <header class="bg-soft text-dark text-center py-5 mb-5 shadow-sm">
        <div class="container">
            <h1 class="display-4 fw-bold">Amigos son los amigos</h1>
            <h3 class="lead mt-3">La veterinaria que cuida lo que más amas</h3>
            <a href="#menu" class="btn btn-light btn-lg mt-4 shadow-sm rounded-pill px-4">Comenzar</a>
        </div>
    </header>

    <section id="menu" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Menu</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                        <img src="{{asset('img/clientes-2.jpg')}}" alt="pacientes" class="card-img-top" />
                        <div class="card-body">
                            <h5 class="card-title mt-3">Clientes</h5>
                            <p class="card-text">Cuentas de clientes.</p>

                            <a href="{{route('admin.usuarios')}}" class="btn btn-outline-primary rounded-pill mt-3">Ver
                                usuarios</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                        <img src="{{asset('img/estadisticas.jpg')}}" alt="turnos" class="card-img-top" />
                        <div class="card-body">
                            <h5 class="card-title mt-3">Reportes</h5>
                            <p class="card-text">Mascotas, consultas y turnos.</p>
                            <a href="{{route('admin.reportes')}}" class="btn btn-outline-primary rounded-pill mt-3">Ver
                                reportes</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                        <img src="{{asset('img/empleados.jpg')}}" alt="Empleados" class="card-img-top" />
                        <div class="card-body">
                            <h5 class="card-title mt-3">Empleados</h5>
                            <p class="card-text">Nuestro equipo.</p>
                            <a href="{{route('admin.empleados')}}" class="btn btn-outline-primary rounded-pill mt-3">Ver
                                empleados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="comentarios" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Lo que dicen nuestros usuarios</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4 text-center border-0 shadow-sm h-100 hover-effect">
                        <p class="fst-italic text-muted">
                            "Gracias a Amigos son los amigos, he optimizado la atención de
                            mis pacientes."
                        </p>
                        <h6 class="fw-bold mt-3 mb-0">Laura Gómez</h6>
                        <small class="text-muted">Veterinaria</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 text-center border-0 shadow-sm h-100 hover-effect">
                        <p class="fst-italic text-muted">
                            "Gestionar citas y stock ahora es más sencillo que nunca."
                        </p>
                        <h6 class="fw-bold mt-3 mb-0">Ezequiel López</h6>
                        <small class="text-muted">Veterinario</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 text-center border-0 shadow-sm h-100 hover-effect">
                        <p class="fst-italic text-muted">
                            "Una solución completa, moderna y muy fácil de usar."
                        </p>
                        <h6 class="fw-bold mt-3 mb-0">Martín Pérez</h6>
                        <small class="text-muted">Veterinario Especialista</small>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>