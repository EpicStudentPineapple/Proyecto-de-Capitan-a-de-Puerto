@extends('layouts.app')

@section('title', 'Detalle de Muelle')

@section('content')
<div class="muelle-detail-page">
    <div class="page-header-simple">
        <h1>Muelle: {{ $muelle->nombre }}</h1>
        <a href="{{ route('muelles.index') }}" class="link-back">← Volver al listado</a>
    </div>

    <div class="mb-6 flex flex-wrap gap-4">
        @if(Auth::user()->isAdministrador())
            <a href="{{ route('admin.muelles.edit', $muelle->id) }}" class="btn btn-primary">
                ✏️ Editar Muelle
            </a>
            
            <form action="{{ route('admin.muelles.toggle-disponibilidad', $muelle->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-outline">
                    {{ $muelle->disponible ? '🔴 Marcar No Disponible' : '🟢 Marcar Disponible' }}
                </button>
            </form>
            
            @if(!$muelle->estaOcupado())
                <form action="{{ route('admin.muelles.destroy', $muelle->id) }}" method="POST" class="inline" 
                      onsubmit="return confirm('¿Estás seguro de eliminar este muelle?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        🗑️ Eliminar
                    </button>
                </form>
            @endif
        @endif
    </div>

    <div class="card p-6 mb-8">
        <h2 class="text-xl font-bold mb-4 border-b pb-2">Información General</h2>
        <div class="muelle-detail-grid">
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Código</span>
                <span class="muelle-detail-value">{{ $muelle->codigo }}</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Tipo de Muelle</span>
                <span class="muelle-detail-value">{{ ucfirst(str_replace('_', ' ', $muelle->tipo_muelle)) }}</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Longitud</span>
                <span class="muelle-detail-value">{{ $muelle->longitud }} m</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Calado Máximo</span>
                <span class="muelle-detail-value">{{ $muelle->calado_maximo }} m</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Capacidad</span>
                <span class="muelle-detail-value">{{ number_format($muelle->capacidad_toneladas) }} t</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Estado</span>
                <span class="muelle-detail-value">
                    @if($muelle->disponible)
                        @if($muelle->estaOcupado())
                            <span class="badge badge-danger">OCUPADO</span>
                        @else
                            <span class="badge badge-success">DISPONIBLE</span>
                        @endif
                    @else
                        <span class="badge badge-secondary">NO DISPONIBLE</span>
                    @endif
                </span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Grúa</span>
                <span class="muelle-detail-value">{{ $muelle->grua_disponible ? '✅ Sí' : '❌ No' }}</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Energía</span>
                <span class="muelle-detail-value">{{ $muelle->energia_tierra ? '✅ Sí' : '❌ No' }}</span>
            </div>
        </div>
        
        @if($muelle->observaciones)
            <div class="mt-6 pt-4 border-t">
                <span class="muelle-detail-label">Observaciones</span>
                <p class="text-sm mt-1 text-muted">{{ $muelle->observaciones }}</p>
            </div>
        @endif
    </div>

    @if($muelle->buqueActual)
    <div class="card p-6 mb-8 border-l-4 border-red-500">
        <h2 class="text-xl font-bold mb-4 text-red-700">Buque Actualmente Atracado</h2>
        <div class="muelle-detail-grid">
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Nombre</span>
                <span class="muelle-detail-value">
                    @php
                        $routeActual = Auth::user()->isAdministrador() 
                            ? route('admin.buques.show', $muelle->buqueActual->id) 
                            : (Auth::user()->id === $muelle->buqueActual->user_id 
                                ? route('propietario.buques.show', $muelle->buqueActual->id) 
                                : null);
                    @endphp
                    @if($routeActual)
                        <a href="{{ $routeActual }}" class="link-standard font-bold text-lg">
                            {{ $muelle->buqueActual->nombre }}
                        </a>
                    @else
                        <span class="font-bold text-lg">{{ $muelle->buqueActual->nombre }}</span>
                    @endif
                </span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">IMO</span>
                <span class="muelle-detail-value">{{ $muelle->buqueActual->imo }}</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Fecha Atraque</span>
                <span class="muelle-detail-value">{{ $muelle->buqueActual->fecha_atraque->format('d/m/Y H:i') }}</span>
            </div>
            <div class="muelle-detail-item">
                <span class="muelle-detail-label">Salida Prevista</span>
                <span class="muelle-detail-value">{{ $muelle->buqueActual->fecha_salida_prevista->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Pantalanes del Muelle</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.pantalans.por-muelle', $muelle->id) }}" class="btn btn-sm btn-outline">
                    Ver Todos
                </a>
                @if(Auth::user()->isAdministrador())
                    <a href="{{ route('admin.pantalans.create') }}?muelle_id={{ $muelle->id }}" class="btn btn-sm btn-primary">
                        + Añadir
                    </a>
                @endif
            </div>
        </div>

        <div class="card card-table">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Nº Amarre</th>
                            <th>Long. Máx</th>
                            <th>Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($muelle->pantalans as $pantalan)
                        <tr>
                            <td><strong>{{ $pantalan->codigo }}</strong></td>
                            <td>
                                <a href="{{ route('pantalans.show', $pantalan->id) }}" class="link-standard">
                                    {{ $pantalan->nombre }}
                                </a>
                            </td>
                            <td>{{ $pantalan->numero_amarre }}</td>
                            <td>{{ $pantalan->longitud_maxima }} m</td>
                            <td>
                                <span class="badge {{ $pantalan->disponible ? 'badge-success' : 'badge-danger' }}">
                                    {{ $pantalan->disponible ? 'SÍ' : 'NO' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted small italic">No hay pantalanes en este muelle</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mb-10">
        <h2 class="text-xl font-bold mb-4">Historial de Buques (Últimos 10)</h2>
        <div class="card card-table">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Buque</th>
                            <th>IMO</th>
                            <th>Tipo</th>
                            <th>Atraque</th>
                            <th>Salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($muelle->buques as $buque)
                        <tr>
                            <td>
                                @php
                                    $routeHist = Auth::user()->isAdministrador() 
                                        ? route('admin.buques.show', $buque->id) 
                                        : (Auth::user()->id === $buque->user_id 
                                            ? route('propietario.buques.show', $buque->id) 
                                            : null);
                                @endphp
                                @if($routeHist)
                                    <a href="{{ $routeHist }}" class="link-standard">
                                        {{ $buque->nombre }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ $buque->nombre }}</span>
                                @endif
                            </td>
                            <td>{{ $buque->imo }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</td>
                            <td class="small">{{ $buque->fecha_atraque ? $buque->fecha_atraque->format('d/m/Y H:i') : '-' }}</td>
                            <td class="small">{{ $buque->fecha_salida_prevista ? $buque->fecha_salida_prevista->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ ucfirst($buque->estado) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small italic">Sin historial de buques</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection