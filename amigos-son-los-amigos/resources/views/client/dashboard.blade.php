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

        <header class="bg-soft text-dark text-center py-5 mb-5 shadow-sm">
            <div class="container">
                <h1 class="display-4 fw-bold">Amigos son los amigos</h1>
                <h3 class="lead mt-3">La veterinaria que cuida lo que más amas</h3>
                <a href="#menu" class="btn btn-light btn-lg mt-4 shadow-sm rounded-pill px-4">Comenzar</a>
            </div>
        </header>

        <section id="menu" class="py-5 bg-white">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                            <img src="{{asset('img/mascotas-2.jpg')}}" alt="pacientes" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title mt-3">Mascotas</h5>
                                <p class="card-text">Mis mascotas.</p>

                                <a href="{{ route('client.mascotas.index') }}" class="btn btn-outline-user rounded-pill mt-3">Ver
                                    mascotas</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                            <img src="{{asset('img/turnos.jpg')}}" alt="turnos" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title mt-3">Turnos</h5>
                                <p class="card-text">Citas y horarios.</p>
                                <a href="{{ route('client.turnos.index') }}" class="btn btn-outline-user rounded-pill mt-3">Ver
                                    turnos</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                            <img src="{{asset('img/facturas-2.jpg')}}" alt="Empleados" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title mt-3">Facturas</h5>
                                <p class="card-text">Pagos y deudas.</p>
                                <a href="{{route('client.facturas.index')}}" class="btn btn-outline-user rounded-pill mt-3">Ver
                                    facturas</a>
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
                                "Agendar y organizar citas ahora es más sencillo que nunca."
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
    </main>
</x-app-layout>
