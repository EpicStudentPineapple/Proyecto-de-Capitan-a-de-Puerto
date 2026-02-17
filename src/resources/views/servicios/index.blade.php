@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">Servicios Portuarios</h1>
    
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.servicios.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            + Crear Nuevo Servicio
        </a>
    @endif

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Código</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Nombre</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Tipo</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Precio Base</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Unidad</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">24h</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicios as $servicio)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">{{ $servicio->codigo }}</td>
                        <td style="padding: 12px;">{{ $servicio->nombre }}</td>
                        <td style="padding: 12px;">{{ ucfirst(str_replace('_', ' ', $servicio->tipo_servicio)) }}</td>
                        <td style="padding: 12px;">{{ number_format($servicio->precio_base, 2) }} €</td>
                        <td style="padding: 12px;">{{ ucfirst(str_replace('_', ' ', $servicio->unidad_cobro)) }}</td>
                        <td style="padding: 12px;">{{ $servicio->disponible_24h ? '✓' : '✗' }}</td>
                        <td style="padding: 12px;">
                            <a href="{{ route('servicios.show', $servicio->id) }}" style="color: #0066cc; margin-right: 10px;">Ver</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.servicios.edit', $servicio->id) }}" style="color: #0066cc; margin-right: 10px;">Editar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 20px; text-align: center; color: #666;">
                            No hay servicios registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $servicios->links() }}
    </div>
</div>
@endsection
