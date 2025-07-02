<nav class="navbar navbar-expand-lg navbar-dark user shadow-sm bg-naranja">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('employee.dashboard') }}">Amigos son los amigos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('employee.mascotas.index')}}">Mascotas</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" aria-current="page"
                        href="{{ route('employee.diagnosticos.index') }}">Diagnósticos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('employee.facturacion.index')}}">Facturas</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('employee.turnos.index')}}">Turnos</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{asset('img/user-icon.png')}}" alt="Avatar" class="rounded-circle me-2" width="40"
                            height="40" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!--<li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>-->
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Cerrar Sesión </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>