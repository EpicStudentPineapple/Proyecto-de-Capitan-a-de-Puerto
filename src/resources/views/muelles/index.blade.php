@extends('layouts.app')

@section('title', 'Muelles')

@section('content')
<div class="muelles-page">
    <div class="muelles-header">
        <h1>Gestión de Muelles</h1>
        
        @if(Auth::user()->isAdministrador())
            <a href="{{ route('admin.muelles.create') }}" class="btn btn-primary">
                ➕ Crear Nuevo Muelle
            </a>
        @endif
    </div>

    <div class="card card-table">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Longitud (m)</th>
                        <th>Calado (m)</th>
                        <th>Capacidad (t)</th>
                        <th>Estado</th>
                        <th>Buque Actual</th>
                        <th class="table-actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($muelles as $muelle)
                    <tr>
                        <td><strong>{{ $muelle->codigo }}</strong></td>
                        <td>{{ $muelle->nombre }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $muelle->tipo_muelle)) }}</td>
                        <td>{{ $muelle->longitud }}</td>
                        <td>{{ $muelle->calado_maximo }}</td>
                        <td>{{ number_format($muelle->capacidad_toneladas) }}</td>
                        <td>
                            @if($muelle->disponible)
                                @if($muelle->buqueActual)
                                    <span class="badge badge-danger">OCUPADO</span>
                                @else
                                    <span class="badge badge-success">DISPONIBLE</span>
                                @endif
                            @else
                                <span class="badge badge-secondary">NO DISPONIBLE</span>
                            @endif
                        </td>
                        <td>
                            @if($muelle->buqueActual)
                                @php
                                    $route = Auth::user()->isAdministrador() 
                                        ? route('admin.buques.show', $muelle->buqueActual->id) 
                                        : (Auth::user()->id === $muelle->buqueActual->user_id 
                                            ? route('propietario.buques.show', $muelle->buqueActual->id) 
                                            : null);
                                @endphp
                                
                                @if($route)
                                    <a href="{{ $route }}" class="link-standard">
                                        {{ $muelle->buqueActual->nombre }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ $muelle->buqueActual->nombre }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="table-actions-cell">
                            <div class="table-actions">
                                <a href="{{ route('muelles.show', $muelle->id) }}" class="btn btn-sm btn-outline" title="Ver detalle">
                                    👁️ Ver
                                </a>
                                
                                @if(Auth::user()->isAdministrador())
                                    <a href="{{ route('admin.muelles.edit', $muelle->id) }}" class="btn btn-sm btn-outline" title="Editar">
                                        ✏️ Editar
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <p class="text-muted">No hay muelles registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($muelles->hasPages())
        <div class="pagination-wrapper mt-6">
            {{ $muelles->links() }}
        </div>
    @endif
</div>
@endsection