<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Eliminar o comentar la fuente de Bunny si no la necesitas para tu diseño --}}
    {{--
    <link rel="preconnect" href="https://fonts.bunny.net"> --}}
    {{--
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

    {{-- Carga de Bootstrap CSS desde CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Tu archivo CSS principal de Vite. Mantenlo si contiene tus estilos personalizados --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Si tienes un archivo CSS personalizado para tu diseño que no está en app.css, descomenta y usa: --}}
    {{--
    <link rel="stylesheet" href="{{ asset('css/mi-estilo-personalizado.css') }}"> --}}

</head>

{{-- Elimina las clases Tailwind del body si quieres que Bootstrap tome el control total --}}
{{-- Mantengo las clases de Bootstrap que ya tenías --}}

<body class="bg-light d-flex flex-column justify-content-center align-items-center min-vh-100 fondo-login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5"> {{-- Columnas para centrar y limitar ancho del formulario --}}



                <div class="card shadow-sm border-0 rounded-lg p-4"> {{-- Estilo de tarjeta Bootstrap --}}
                    {{ $slot }} {{-- Aquí es donde se inyecta el contenido de login.blade.php --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Carga de Bootstrap JS al final del body --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eGbHAHp41fJTl_nLwz+ALEwIH"
        crossorigin="anonymous">
        </script>
</body>

</html>