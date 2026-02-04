@extends('layout.app')

@section('title', 'Detalle de Muelle')

@section('content')
<h1>Detalle del Muelle: {{ $muelle->nombre }}</h1>

<p><a href="{{ route('muelles.index') }}">← Volver al listado</a></p>

<div style="margin: 20px 0;">
    @if(Auth::user()->isAdministrador())
        <a href="{{ route('muelles.edit', $muelle->id) }}">
            <button>Editar Muelle</button>
        </a>
        
        <form action="{{ route('muelles.toggle-disponibilidad', $muelle->id) }}" 
              method="POST" style="display: inline;">
            @csrf
            <button type="submit">
                {{ $muelle->disponible ? '🔴 Marcar No Disponible' : '🟢 Marcar Disponible' }}
            </button>
        </form>
        
        @if(!$muelle->estaOcupado())
            <form action="{{ route('muelles.destroy', $muelle->id) }}" 
                  method="POST" style="display: inline;" 
                  onsubmit="return confirm('¿Estás seguro de eliminar este muelle?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: red; color: white;">
                    🗑️ Eliminar Muelle
                </button>
            </form>
        @endif
    @endif
</div>

<h2>Información General</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
    <tr>
        <td><strong>Código:</strong></td>
        <td>{{ $muelle->codigo }}</td>
    </tr>
    <tr>
        <td><strong>Nombre:</strong></td>
        <td>{{ $muelle->nombre }}</td>
    </tr>
    <tr>
        <td><strong>Tipo:</strong></td>
        <td>{{ ucfirst(str_replace('_', ' ', $muelle->tipo_muelle)) }}</td>
    </tr>
    <tr>
        <td><strong>Longitud:</strong></td>
        <td>{{ $muelle->longitud }} metros</td>
    </tr>
    <tr>
        <td><strong>Calado Máximo:</strong></td>
        <td>{{ $muelle->calado_maximo }} metros</td>
    </tr>
    <tr>
        <td><strong>Capacidad:</strong></td>
        <td>{{ number_format($muelle->capacidad_toneladas) }} toneladas</td>
    </tr>
    <tr>
        <td><strong>Estado:</strong></td>
        <td>
            @if($muelle->disponible)
                @if($muelle->estaOcupado())
                    <span style="color: red;">🔴 OCUPADO</span>
                @else
                    <span style="color: green;">🟢 DISPONIBLE</span>
                @endif
            @else
                <span style="color: gray;">⚫ NO DISPONIBLE</span>
            @endif
        </td>
    </tr>
    <tr>
        <td><strong>Grúa:</strong></td>
        <td>{{ $muelle->grua_disponible ? '✅ Sí' : '❌ No' }}</td>
    </tr>
    <tr>
        <td><strong>Energía de Tierra:</strong></td>
        <td>{{ $muelle->energia_tierra ? '✅ Sí' : '❌ No' }}</td>
    </tr>
    @if($muelle->observaciones)
    <tr>
        <td><strong>Observaciones:</strong></td>
        <td>{{ $muelle->observaciones }}</td>
    </tr>
    @endif
</table>

@if($muelle->buqueActual)
<h2>Buque Actualmente Atracado</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
    <tr>
        <td><strong>Nombre:</strong></td>
        <td>
            <a href="{{ route('buques.show', $muelle->buqueActual->id) }}">
                {{ $muelle->buqueActual->nombre }}
            </a>
        </td>
    </tr>
    <tr>
        <td><strong>IMO:</strong></td>
        <td>{{ $muelle->buqueActual->imo }}</td>
    </tr>
    <tr>
        <td><strong>Tipo:</strong></td>
        <td>{{ ucfirst(str_replace('_', ' ', $muelle->buqueActual->tipo_buque)) }}</td>
    </tr>
    <tr>
        <td><strong>Fecha Atraque:</strong></td>
        <td>{{ $muelle->buqueActual->fecha_atraque->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <td><strong>Salida Prevista:</strong></td>
        <td>{{ $muelle->buqueActual->fecha_salida_prevista->format('d/m/Y H:i') }}</td>
    </tr>
</table>
@endif

<h2>Historial de Buques (Últimos 10)</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>Buque</th>
            <th>IMO</th>
            <th>Tipo</th>
            <th>Fecha Atraque</th>
            <th>Fecha Salida</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($muelle->buques as $buque)
        <tr>
            <td>
                <a href="{{ route('buques.show', $buque->id) }}">
                    {{ $buque->nombre }}
                </a>
            </td>
            <td>{{ $buque->imo }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</td>
            <td>{{ $buque->fecha_atraque ? $buque->fecha_atraque->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ $buque->fecha_salida_prevista ? $buque->fecha_salida_prevista->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ ucfirst($buque->estado) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Sin historial de buques</td>
        </tr>
        @endforelse
    </tbody>
</table>

<h2>Pantalanes del Muelle</h2>

<p>
    <a href="{{ route('pantalans.por-muelle', $muelle->id) }}">
        <button>Ver Pantalanes de este Muelle</button>
    </a>
    @if(Auth::user()->isAdministrador())
        <a href="{{ route('pantalans.create') }}?muelle_id={{ $muelle->id }}">
            <button>Añadir Pantalán</button>
        </a>
    @endif
</p>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
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
            <td>{{ $pantalan->codigo }}</td>
            <td>
                <a href="{{ route('pantalans.show', $pantalan->id) }}">
                    {{ $pantalan->nombre }}
                </a>
            </td>
            <td>{{ $pantalan->numero_amarre }}</td>
            <td>{{ $pantalan->longitud_maxima }} m</td>
            <td>{{ $pantalan->disponible ? '🟢 Sí' : '🔴 No' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No hay pantalanes en este muelle</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection