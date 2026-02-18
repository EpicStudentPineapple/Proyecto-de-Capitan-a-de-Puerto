@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Mis Reseñas</h1>
        <a href="{{ route('resenas.create') }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            Escribir Nueva Reseña
        </a>
    </div>

    @if(session('success'))
        <div style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    @if(Auth::user()->isAdmin())
                        <th style="padding: 12px; text-align: left;">Usuario</th>
                    @endif
                    <th style="padding: 12px; text-align: left;">Tipo / Servicio</th>
                    <th style="padding: 12px; text-align: left;">Calificación</th>
                    <th style="padding: 12px; text-align: left;">Comentario</th>
                    <th style="padding: 12px; text-align: left;">Estado</th>
                    @if(Auth::user()->isAdmin())
                        <th style="padding: 12px; text-align: left;">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($resenas as $resena)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        @if(Auth::user()->isAdmin())
                            <td style="padding: 12px;">{{ $resena->user->name }}</td>
                        @endif
                        <td style="padding: 12px;">
                            @if($resena->tipo === 'plataforma')
                                <span style="color: #6366f1; font-weight: bold;">[Sistema]</span>
                            @else
                                <span style="font-weight: bold;">{{ $resena->servicio->nombre ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <span style="color: #fbbf24; font-size: 1.2rem;">
                                {{ str_repeat('★', $resena->calificacion) }}{{ str_repeat('☆', 5 - $resena->calificacion) }}
                            </span>
                        </td>
                        <td style="padding: 12px; color: #4a5568;">{{ $resena->comentario }}</td>
                        <td style="padding: 12px;">
                            @if($resena->aprobado)
                                <span style="background: #def7ec; color: #03543f; padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: bold;">Publicada</span>
                            @else
                                <span style="background: #fdf2f2; color: #9b1c1c; padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: bold;">En espera</span>
                            @endif
                        </td>
                        @if(Auth::user()->isAdmin())
                            <td style="padding: 12px; display: flex; gap: 5px;">
                                @if(!$resena->aprobado)
                                    <form action="{{ route('admin.resenas.aprobar', $resena) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background: #059669; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">Aprobar</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.resenas.destroy', $resena) }}" method="POST" onsubmit="return confirm('¿Borrar esta reseña?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #dc2626; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">Borrar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #718096;">
                            <p>No has escrito ninguna reseña todavía.</p>
                            <a href="{{ route('resenas.create') }}" style="color: #0066cc; text-decoration: underline;">¡Comparte tu experiencia!</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $resenas->links() }}
        </div>
    </div>
</div>
@endsection
