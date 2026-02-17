@extends('layouts.app')

@section('title', 'Muelles')

@section('content')
<h1>Gestión de Muelles</h1>

<div style="margin: 20px 0;">
    @if(Auth::user()->isAdministrador())
        <a href="{{ route('admin.muelles.create') }}">
            <button>➕ Crear Nuevo Muelle</button>
        </a>
    @endif
</div>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Longitud (m)</th>
            <th>Calado Máx (m)</th>
            <th>Capacidad (ton)</th>
            <th>Estado</th>
            <th>Buque Actual</th>
            <th>Acciones</th>
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
                        <span style="color: red;">🔴 OCUPADO</span>
                    @else
                        <span style="color: green;">🟢 DISPONIBLE</span>
                    @endif
                @else
                    <span style="color: gray;">⚫ NO DISPONIBLE</span>
                @endif
            </td>
            <td>
                @if($muelle->buqueActual)
                    <a href="{{ route('buques.show', $muelle->buqueActual->id) }}">
                        {{ $muelle->buqueActual->nombre }}
                    </a>
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('muelles.show', $muelle->id) }}">
                    <button>👁️ Ver</button>
                </a>
                
                @if(Auth::user()->isAdministrador())
                    <a href="{{ route('admin.muelles.edit', $muelle->id) }}">
                        <button>✏️ Editar</button>
                    </a>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9">No hay muelles registrados</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $muelles->links() }}
</div>
@endsection