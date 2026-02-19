@extends('layouts.app')

@section('title', 'Tráfico en Tiempo Real')

@section('content')
<div class="dashboard-page">
    <a href="{{ route('dashboard') }}" class="back-link">Volver al Dashboard</a>

    <div class="page-title-section flex justify-between items-center mb-6">
        <h1>Tráfico Marítimo en Tiempo Real</h1>
        <button class="btn btn-outline btn-sm" onclick="location.reload()">
            🔄 Actualizar Datos
        </button>
    </div>

    <!-- ── Sección: Estado de Muelles ────────────────────────────────── -->
    <div class="quick-access-section">
        <h2>Estado de Muelles</h2>
        
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Buque Atracado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($muelles as $muelle)
                    <tr>
                        <td><strong>{{ $muelle->codigo }}</strong></td>
                        <td>{{ $muelle->nombre }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $muelle->tipo_muelle)) }}</td>
                        <td>
                            @if(!$muelle->disponible)
                                <span class="badge badge-neutral">Fuera de Servicio</span>
                            @elseif($muelle->buqueActual)
                                <span class="badge badge-danger">Ocupado</span>
                            @else
                                <span class="badge badge-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            @if($muelle->buqueActual)
                                @php
                                    $rutaBuque = Auth::user()->isAdmin() 
                                        ? route('admin.buques.show', $muelle->buqueActual->id)
                                        : route('propietario.buques.show', $muelle->buqueActual->id);
                                @endphp
                                <a href="{{ $rutaBuque }}" class="text-accent font-medium">
                                    {{ $muelle->buqueActual->nombre }}
                                </a>
                                <br>
                                <small class="text-muted">IMO: {{ $muelle->buqueActual->imo }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay muelles registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── Sección: Buques en Puerto ─────────────────────────────────── -->
    <div class="quick-access-section mt-12">
        <h2>Buques en Puerto</h2>
        
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>IMO</th>
                        <th>Tipo</th>
                        <th>Bandera</th>
                        <th>Estado</th>
                        <th>Muelle</th>
                        <th>Propietario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buques as $buque)
                    <tr>
                        <td>
                            @php
                                $rutaBuque = Auth::user()->isAdmin() 
                                    ? route('admin.buques.show', $buque->id)
                                    : route('propietario.buques.show', $buque->id);
                            @endphp
                            <a href="{{ $rutaBuque }}" class="text-accent font-semibold">
                                {{ $buque->nombre }}
                            </a>
                        </td>
                        <td>{{ $buque->imo }}</td>
                        <td class="text-sm">{{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</td>
                        <td>{{ $buque->bandera }}</td>
                        <td>
                            @switch($buque->estado)
                                @case('atracado')
                                    <span class="badge badge-success">⚓ Atracado</span>
                                    @break
                                @case('fondeado')
                                    <span class="badge badge-warning">⏳ Fondeado</span>
                                    @break
                                @case('en_maniobra')
                                    <span class="badge badge-info">🔄 En Maniobra</span>
                                    @break
                                @default
                                    <span class="badge badge-neutral">{{ ucfirst($buque->estado) }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($buque->muelle)
                                @php
                                    $rutaMuelle = Auth::user()->isAdmin() 
                                        ? route('admin.muelles.show', $buque->muelle->id)
                                        : route('muelles.show', $buque->muelle->id);
                                @endphp
                                <a href="{{ $rutaMuelle }}" class="font-medium">
                                    {{ $buque->muelle->codigo }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-sm">{{ $buque->propietario->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay buques en puerto</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
    <div class="mt-8">
        <a href="{{ route('admin.buques.gestion-atraques') }}" class="btn btn-primary">
            Ir a Gestión de Atraques
        </a>
    </div>
    @endif
</div>
@endsection