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
                            <img src="{{asset('img/mascotas-2.jpg')}}" alt="Mascotas" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title mt-3">Mascotas</h5>
                                <p class="card-text">Gestión de mascotas</p>

                                <a href="{{route('employee.mascotas.index')}}"
                                    class="btn btn-outline-employee rounded-pill mt-3">Ver
                                    mascotas</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                            <img src="{{asset('img/diagnostico-empleados.jpg')}}" alt="turnos" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title mt-3">Diagnósticos</h5>
                                <p class="card-text">Consultas y diagnósticos</p>
                                <a href="{{ route('employee.diagnosticos.index') }}"
                                    class="btn btn-outline-employee rounded-pill mt-3">Ver
                                    diagnósticos</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 hover-effect text-center">
                            <img src="{{asset('img/facturas-2.jpg')}}" alt="Empleados" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title mt-3">Facturas</h5>
                                <p class="card-text">Gestión de facturación</p>
                                <a href="{{route('employee.facturacion.index')}}"
                                    class="btn btn-outline-employee rounded-pill mt-3">Ver
                                    facturas</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row col-lg-8 mx-auto">
                    <div class="card shadow-sm border-0 hover-effect mt-5">
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Turnos</h5>
                                <p class="card-text">Panel de gestión de turnos.</p>
                            </div>

                            <a href="{{route('employee.turnos.index')}}"
                                class="btn btn-outline-employee rounded-pill align-self-center">Ver
                                turnos</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
