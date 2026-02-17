@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">Buques</h1>
    
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.buques.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            + Registrar Nuevo Buque
        </a>
    @else
        <a href="{{ route('propietario.buques.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            + Registrar Nuevo Buque
        </a>
    @endif

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div role="alert" aria-live="polite" style="background: #fee; border: 1px solid #c00; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #c00;">
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Nombre</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">IMO</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Tipo</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Estado</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Muelle</th>
                    @if(auth()->user()->isAdmin())
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Propietario</th>
                    @endif
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buques as $buque)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">{{ $buque->nombre }}</td>
                        <td style="padding: 12px;">{{ $buque->imo }}</td>
                        <td style="padding: 12px;">{{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 3px; font-size: 14px; background: 
                                @if($buque->estado == 'atracado') #efe @elseif($buque->estado == 'navegando') #eef @else #ffe @endif;">
                                {{ ucfirst(str_replace('_', ' ', $buque->estado)) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $buque->muelle->nombre ?? 'N/A' }}</td>
                        @if(auth()->user()->isAdmin())
                            <td style="padding: 12px;">{{ $buque->propietario->name ?? 'N/A' }}</td>
                        @endif
                        <td style="padding: 12px;">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.buques.show', $buque->id) }}" style="color: #0066cc; margin-right: 10px;">Ver</a>
                                <a href="{{ route('admin.buques.edit', $buque->id) }}" style="color: #0066cc; margin-right: 10px;">Editar</a>
                            @else
                                <a href="{{ route('propietario.buques.show', $buque->id) }}" style="color: #0066cc; margin-right: 10px;">Ver</a>
                                <a href="{{ route('propietario.buques.edit', $buque->id) }}" style="color: #0066cc; margin-right: 10px;">Editar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? '7' : '6' }}" style="padding: 20px; text-align: center; color: #666;">
                            No hay buques registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $buques->links() }}
    </div>
</div>
@endsection
