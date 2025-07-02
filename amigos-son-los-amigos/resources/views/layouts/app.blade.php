<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-light">
        @auth
            @if (Auth::user()->isAdmin())
                @include('layouts.navs.admin-nav')
            @elseif (Auth::user()->isEmployee())
                @include('layouts.navs.employee-nav')
            @elseif (Auth::user()->isClient())
                @include('layouts.navs.client-nav')
            @else

                @include('layouts.navigation')
            @endif
        @endauth

        @if (isset($header))
            <header class="bg-white shadow-sm">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endif



        <main class="py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </div>
    @auth
        @if (Auth::user()->isAdmin())
            @include('layouts.footers.admin-footer')
        @elseif (Auth::user()->isEmployee())
            @include('layouts.footers.employee-footer')
        @elseif (Auth::user()->isClient())
            @include('layouts.footers.client-footer')
        @else

        @endif
    @endauth
</body>

</html>