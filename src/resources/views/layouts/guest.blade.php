<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestorinaitor 3000') }}</title>

        <!-- Estilos de la aplicación -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/layouts.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    </head>
    <body>
        <div class="auth-layout">
            <div class="auth-logo-wrapper">
                <a href="/">
                    <img
                        src="{{ asset('build/assets/Logo.jpg') }}"
                        alt="Gestorinaitor 3000 — Inicio"
                        class="auth-logo"
                    >
                </a>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
