@extends('layouts.app')

@section('content')

<div class="resenas-page">
    <div class="resenas-header">
        <h1>Mis Reseñas</h1>
        <a href="{{ route('resenas.create') }}" class="btn btn-primary">
            Escribir Nueva Reseña
        </a>
    </div>

    @if(session('success'))
        <div role="alert" aria-live="polite" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">
        <table class="table" aria-label="Listado de reseñas">
            <thead>
                <tr>
                    @if(Auth::user()->isAdmin())
                        <th scope="col">Usuario</th>
                    @endif
                    <th scope="col">Tipo / Servicio</th>
                    <th scope="col">Calificación</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Estado</th>
                    @if(Auth::user()->isAdmin())
                        <th scope="col">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($resenas as $resena)
                    <tr>
                        @if(Auth::user()->isAdmin())
                            <td>{{ $resena->user->name }}</td>
                        @endif
                        <td>
                            @if($resena->tipo === 'plataforma')
                                <span class="badge-plataforma">[Sistema]</span>
                            @else
                                <strong>{{ $resena->servicio->nombre ?? 'N/A' }}</strong>
                            @endif
                        </td>
                        <td>
                            <span class="stars" aria-label="{{ $resena->calificacion }} de 5 estrellas">
                                {{ str_repeat('★', $resena->calificacion) }}{{ str_repeat('☆', 5 - $resena->calificacion) }}
                            </span>
                        </td>
                        <td>{{ $resena->comentario }}</td>
                        <td>
                            @if($resena->aprobado)
                                <span class="badge badge-success">Publicada</span>
                            @else
                                <span class="badge badge-warning">En espera</span>
                            @endif
                        </td>
                        @if(Auth::user()->isAdmin())
                            <td>
                                <div class="resena-actions">
                                    @if(!$resena->aprobado)
                                        <form action="{{ route('admin.resenas.aprobar', $resena) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.resenas.destroy', $resena) }}" method="POST"
                                          onsubmit="return confirm('¿Borrar esta reseña?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? 6 : 4 }}" class="text-center text-muted" style="padding: 2.5rem;">
                            <p>No has escrito ninguna reseña todavía.</p>
                            <a href="{{ route('resenas.create') }}" class="btn btn-accent btn-sm" style="margin-top: 0.75rem;">
                                ¡Comparte tu experiencia!
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $resenas->links() }}
    </div>
</div>
@endsection
