@extends('layouts.app')

@section('title', 'Dashboard')


@section('content')
<div class="dashboard-page">
    <h1>Dashboard Principal</h1>

    {{-- Estado del Puerto --}}
    <section class="stats-section" aria-labelledby="estado-puerto-title">
        <h2 id="estado-puerto-title">Estado del Puerto</h2>
        <div class="stats-table-wrapper">
            <table class="table" aria-label="Indicadores del estado del puerto">
                <thead>
                    <tr>
                        <th scope="col">Indicador</th>
                        <th scope="col">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Buques Atracados</td>
                        <td><strong>{{ $buquesAtracados }}</strong></td>
                    </tr>
                    <tr>
                        <td>Buques Fondeados</td>
                        <td><strong>{{ $buquesFondeados }}</strong></td>
                    </tr>
                    <tr>
                        <td>Buques Navegando</td>
                        <td><strong>{{ $buquesNavegando }}</strong></td>
                    </tr>
                    <tr>
                        <td>Muelles Disponibles</td>
                        <td><strong>{{ $muellesDisponibles }}</strong></td>
                    </tr>
                    <tr>
                        <td>Muelles Ocupados</td>
                        <td><strong>{{ $muellesOcupados }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Accesos Rápidos --}}
    <section class="quick-access-section" aria-labelledby="accesos-rapidos-title">
        <h2 id="accesos-rapidos-title">Accesos Rápidos</h2>
        <div class="quick-access-grid">
            <a href="{{ route('dashboard.trafico') }}" class="btn btn-primary">
                Ver Tráfico en Tiempo Real
            </a>
            <a href="{{ route('dashboard.clima') }}" class="btn btn-primary">
                Condiciones Climáticas
            </a>
            @if(Auth::user()->isAdministrador())
                <a href="{{ route('admin.buques.gestion-atraques') }}" class="btn btn-outline">
                    Gestionar Atraques
                </a>
            @endif
            <a href="{{ route('servicios.index') }}" class="btn btn-outline">
                Servicios Portuarios
            </a>
        </div>
    </section>

    {{-- Navegación por Secciones --}}
    <section aria-labelledby="secciones-title">
        <h2 id="secciones-title">Navegación por Secciones</h2>
        <div class="sections-grid">
            <div class="section-card">
                <h3>Muelles</h3>
                <p>Gestión de infraestructura portuaria</p>
                <div class="section-card-actions">
                    <a href="{{ route('muelles.index') }}" class="btn btn-accent btn-sm">Ver Muelles</a>
                    @if(Auth::user()->isAdministrador())
                        <a href="{{ route('admin.muelles.create') }}" class="btn btn-outline btn-sm">Crear Muelle</a>
                    @endif
                </div>
            </div>

            <div class="section-card">
                <h3>Buques</h3>
                <p>Control de embarcaciones en puerto</p>
                <div class="section-card-actions">
                    @if(Auth::user()->isAdministrador())
                        <a href="{{ route('admin.buques.index') }}" class="btn btn-accent btn-sm">Ver Todos</a>
                        <a href="{{ route('admin.buques.create') }}" class="btn btn-outline btn-sm">Registrar Buque</a>
                    @else
                        <a href="{{ route('propietario.buques.index') }}" class="btn btn-accent btn-sm">Mis Buques</a>
                        <a href="{{ route('propietario.buques.create') }}" class="btn btn-outline btn-sm">Registrar Buque</a>
                    @endif
                </div>
            </div>

            <div class="section-card">
                <h3>Servicios</h3>
                <p>Servicios disponibles en el puerto</p>
                <div class="section-card-actions">
                    <a href="{{ route('servicios.index') }}" class="btn btn-accent btn-sm">Ver Servicios</a>
                    @if(Auth::user()->isAdministrador())
                        <a href="{{ route('admin.servicios.create') }}" class="btn btn-outline btn-sm">Crear Servicio</a>
                    @endif
                </div>
            </div>

            <div class="section-card">
                <h3>Pantalanes</h3>
                <p>Amarres para embarcaciones menores</p>
                <div class="section-card-actions">
                    <a href="{{ route('pantalans.index') }}" class="btn btn-accent btn-sm">Ver Pantalanes</a>
                    @if(Auth::user()->isAdministrador())
                        <a href="{{ route('admin.pantalans.create') }}" class="btn btn-outline btn-sm">Crear Pantalán</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Bloque de propietario --}}
    @if(Auth::user()->isPropietario())
    <div class="owner-block" role="region" aria-label="Gestión de mi flota">
        <h3>Mis Buques</h3>
        <p>Tienes acceso a la gestión de tu flota</p>
        <a href="{{ route('propietario.buques.index') }}" class="btn btn-accent">Ver Mis Buques</a>
    </div>
    @endif
</div>
@endsection