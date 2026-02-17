@extends('layouts.app')

@section('title', 'Tráfico en Tiempo Real')

@section('content')
<h1>Tráfico Marítimo en Tiempo Real</h1>

<p><a href="{{ route('dashboard') }}">← Volver al Dashboard</a></p>

<div style="margin: 20px 0;">
    <h2>Estado de Muelles</h2>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
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
                    @if($muelle->buqueActual)
                        <span style="color: red;">🔴 OCUPADO</span>
                    @else
                        <span style="color: green;">🟢 DISPONIBLE</span>
                    @endif
                </td>
                <td>
                    @if($muelle->buqueActual)
                        <a href="{{ route('buques.show', $muelle->buqueActual->id) }}">
                            {{ $muelle->buqueActual->nombre }}
                        </a>
                        <br>
                        <small>IMO: {{ $muelle->buqueActual->imo }}</small>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No hay muelles registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin: 30px 0;">
    <h2>Buques en Puerto</h2>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
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
                    <a href="{{ route('buques.show', $buque->id) }}">
                        <strong>{{ $buque->nombre }}</strong>
                    </a>
                </td>
                <td>{{ $buque->imo }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</td>
                <td>{{ $buque->bandera }}</td>
                <td>
                    @switch($buque->estado)
                        @case('atracado')
                            <span style="color: green;">⚓ Atracado</span>
                            @break
                        @case('fondeado')
                            <span style="color: orange;">⏳ Fondeado</span>
                            @break
                        @case('en_maniobra')
                            <span style="color: blue;">🔄 En Maniobra</span>
                            @break
                        @default
                            {{ ucfirst($buque->estado) }}
                    @endswitch
                </td>
                <td>
                    @if($buque->muelle)
                        <a href="{{ route('muelles.show', $buque->muelle->id) }}">
                            {{ $buque->muelle->codigo }}
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $buque->propietario->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No hay buques en puerto</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin: 30px 0;">
    <button onclick="location.reload()">🔄 Actualizar Datos</button>
    <a href="{{ route('admin.buques.gestion-atraques') }}">
        <button>Ir a Gestión de Atraques</button>
    </a>
</div>
@endsection