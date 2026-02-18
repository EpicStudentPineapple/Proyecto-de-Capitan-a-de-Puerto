@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>{{ $buque->nombre }}</h1>
        <div style="display: flex; gap: 10px;">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.buques.edit', $buque->id) }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;">
                    Editar
                </a>
                <form action="{{ route('admin.buques.destroy', $buque->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este buque?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 10px 20px; background: #c00; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>
                </form>
            @else
                <a href="{{ route('propietario.buques.edit', $buque->id) }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;">
                    Editar
                </a>
            @endif
        </div>
    </div>

    <a href="{{ auth()->user()->isAdmin() ? route('admin.buques.index') : route('propietario.buques.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
        ← Volver al listado
    </a>

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información Básica</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Nombre:</dt>
                <dd>{{ $buque->nombre }}</dd>
                
                <dt style="font-weight: bold;">Número IMO:</dt>
                <dd>{{ $buque->imo }}</dd>
                
                <dt style="font-weight: bold;">MMSI:</dt>
                <dd>{{ $buque->mmsi ?? 'N/A' }}</dd>
                
                <dt style="font-weight: bold;">Bandera:</dt>
                <dd>{{ $buque->bandera }}</dd>
                
                <dt style="font-weight: bold;">Tipo de Buque:</dt>
                <dd>{{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</dd>
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Dimensiones</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Eslora:</dt>
                <dd>{{ $buque->eslora }} metros</dd>
                
                <dt style="font-weight: bold;">Manga:</dt>
                <dd>{{ $buque->manga }} metros</dd>
                
                <dt style="font-weight: bold;">Calado:</dt>
                <dd>{{ $buque->calado }} metros</dd>
                
                <dt style="font-weight: bold;">Tonelaje Bruto:</dt>
                <dd>{{ number_format($buque->tonelaje_bruto) }} toneladas</dd>
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información Operativa</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                @if(auth()->user()->isAdmin())
                    <dt style="font-weight: bold;">Propietario:</dt>
                    <dd>{{ $buque->propietario->name ?? 'N/A' }} ({{ $buque->propietario->email ?? '' }})</dd>
                @endif
                
                <dt style="font-weight: bold;">Estado:</dt>
                <dd>
                    <span style="padding: 4px 12px; border-radius: 3px; background: 
                        @if($buque->estado == 'atracado') #efe @elseif($buque->estado == 'navegando') #eef @else #ffe @endif;">
                        {{ ucfirst(str_replace('_', ' ', $buque->estado)) }}
                    </span>
                </dd>
                
                <dt style="font-weight: bold;">Muelle Asignado:</dt>
                <dd>{{ $buque->muelle->nombre ?? 'Sin asignar' }}</dd>
                
                @if($buque->fecha_atraque)
                    <dt style="font-weight: bold;">Fecha de Atraque:</dt>
                    <dd>{{ $buque->fecha_atraque->format('d/m/Y H:i') }}</dd>
                @endif
                
                @if($buque->fecha_salida_prevista)
                    <dt style="font-weight: bold;">Salida Prevista:</dt>
                    <dd>{{ $buque->fecha_salida_prevista->format('d/m/Y H:i') }}</dd>
                @endif
                
                <dt style="font-weight: bold;">Carga Actual:</dt>
                <dd>{{ $buque->carga_actual ? number_format($buque->carga_actual) . ' toneladas' : 'N/A' }}</dd>
                
                <dt style="font-weight: bold;">Tripulación:</dt>
                <dd>{{ $buque->tripulacion ?? 'N/A' }} personas</dd>
            </dl>
        </section>

        @if($buque->observaciones)
            <section style="margin-bottom: 30px;">
                <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Observaciones</h2>
                <p style="white-space: pre-wrap;">{{ $buque->observaciones }}</p>
            </section>
        @endif

        @if($buque->servicios->count() > 0)
            <section>
                <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Servicios Solicitados</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left;">Servicio</th>
                            <th style="padding: 10px; text-align: left;">Fecha Solicitud</th>
                            <th style="padding: 10px; text-align: left;">Estado</th>
                            <th style="padding: 10px; text-align: left;">Precio Total</th>
                            @if(auth()->user()->isAdmin())
                                <th style="padding: 10px; text-align: left;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buque->servicios as $servicio)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">{{ $servicio->nombre }}</td>
                                <td style="padding: 10px;">{{ \Carbon\Carbon::parse($servicio->pivot->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                                <td style="padding: 10px;">
                                    <span style="padding: 4px 8px; border-radius: 3px; font-size: 14px; background: 
                                        @if($servicio->pivot->estado == 'completado') #efe @elseif($servicio->pivot->estado == 'en_proceso') #fef @else #ffe @endif;">
                                        {{ ucfirst(str_replace('_', ' ', $servicio->pivot->estado)) }}
                                    </span>
                                </td>
                                <td style="padding: 10px;">{{ number_format($servicio->pivot->precio_total, 2) }} €</td>
                                @if(auth()->user()->isAdmin())
                                    <td style="padding: 10px;">
                                        @if($servicio->pivot->estado == 'solicitado')
                                            <form action="{{ route('admin.buque-servicio.actualizar-estado', $servicio->pivot->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="estado" value="en_proceso">
                                                <button type="submit" style="padding: 5px 10px; background: #3b82f6; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                                                    Iniciar
                                                </button>
                                            </form>
                                        @elseif($servicio->pivot->estado == 'en_proceso')
                                            <form action="{{ route('admin.buque-servicio.actualizar-estado', $servicio->pivot->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="estado" value="completado">
                                                <button type="submit" style="padding: 5px 10px; background: #10b981; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                                                    Finalizar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    </div>
</div>
@endsection
