<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestorinaitor 3000') }}</title>

        <!-- jQuery UI CSS -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

        <!-- Estilos de la aplicación -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/layouts.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
        <link rel="stylesheet" href="{{ asset('css/buques.css') }}">
        <link rel="stylesheet" href="{{ asset('css/muelles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/pantalans.css') }}">
        <link rel="stylesheet" href="{{ asset('css/servicios.css') }}">
        <link rel="stylesheet" href="{{ asset('css/resenas.css') }}">
        <link rel="stylesheet" href="{{ asset('css/perfiles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


        @stack('styles')
    </head>
    <body>
        <div class="main-layout">
            @include('layouts.navigation')

            @isset($header)
                <div class="page-header">
                    <div class="page-header-inner">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="main-content">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        <!-- jQuery y jQuery UI -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @stack('scripts')
    </body>
</html>