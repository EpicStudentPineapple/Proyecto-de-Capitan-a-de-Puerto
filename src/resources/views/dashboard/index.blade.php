@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1>📊 Dashboard Principal</h1>

<div style="margin: 20px 0;">
    <h2>Estado del Puerto</h2>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>Indicador</th>
            <th>Cantidad</th>
        </tr>
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
    </table>
</div>

<div style="margin: 30px 0;">
    <h2>Accesos Rápidos</h2>
    
    <div>
        <a href="{{ route('dashboard.trafico') }}">
            <button style="padding: 15px; margin: 5px;">
                Ver Tráfico en Tiempo Real
            </button>
        </a>
        
        <a href="{{ route('dashboard.clima') }}">
            <button style="padding: 15px; margin: 5px;">
                Condiciones Climáticas
            </button>
        </a>
        
        @if(Auth::user()->isAdministrador())
            <a href="{{ route('admin.buques.gestion-atraques') }}">
                <button style="padding: 15px; margin: 5px;">
                    Gestionar Atraques
                </button>
            </a>
        @endif
        
        <a href="{{ route('servicios.index') }}">
            <button style="padding: 15px; margin: 5px;">
                Servicios Portuarios
            </button>
        </a>
    </div>
</div>

<div style="margin: 30px 0;">
    <h2>Navegación por Secciones</h2>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        <div style="border: 1px solid #ccc; padding: 20px;">
            <h3>Muelles</h3>
            <p>Gestión de infraestructura portuaria</p>
            <a href="{{ route('muelles.index') }}"><button>Ver Muelles</button></a>
            @if(Auth::user()->isAdministrador())
                <a href="{{ route('admin.muelles.create') }}"><button>Crear Muelle</button></a>
            @endif
        </div>
        
        <div style="border: 1px solid #ccc; padding: 20px;">
            <h3>Buques</h3>
            <p>Control de embarcaciones en puerto</p>
            @if(Auth::user()->isAdministrador())
                <a href="{{ route('admin.buques.index') }}"><button>Ver Todos</button></a>
                <a href="{{ route('admin.buques.create') }}"><button>Registrar Buque</button></a>
            @else
                <a href="{{ route('propietario.buques.index') }}"><button>Ver Mis Buques</button></a>
                <a href="{{ route('propietario.buques.create') }}"><button>Registrar Buque</button></a>
            @endif
        </div>
        
        <div style="border: 1px solid #ccc; padding: 20px;">
            <h3>Servicios</h3>
            <p>Servicios disponibles en el puerto</p>
            <a href="{{ route('servicios.index') }}"><button>Ver Servicios</button></a>
            @if(Auth::user()->isAdministrador())
                <a href="{{ route('admin.servicios.create') }}"><button>Crear Servicio</button></a>
            @endif
        </div>
        
        <div style="border: 1px solid #ccc; padding: 20px;">
            <h3>Pantalanes</h3>
            <p>Amarres para embarcaciones menores</p>
            <a href="{{ route('pantalans.index') }}"><button>Ver Pantalanes</button></a>
            @if(Auth::user()->isAdministrador())
                <a href="{{ route('admin.pantalans.create') }}"><button>Crear Pantalán</button></a>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->isPropietario())
<div style="margin: 30px 0; padding: 20px; background: #e7f3ff; border: 1px solid #b3d9ff;">
    <h3>Mis Buques</h3>
    <p>Tienes acceso a la gestión de tu flota</p>
    <a href="{{ route('propietario.buques.index') }}"><button>Ver Mis Buques</button></a>
</div>
@endif
@endsection