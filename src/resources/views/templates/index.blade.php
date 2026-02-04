@extends('layouts.app')

@section('title', 'Título')

@section('content')
<h1>Título</h1>

<div style="margin: 20px 0;">
    @if(Auth::user()->isOperador())
        <a href="{{ route('ruta.create') }}">
            <button>Crear Nuevo</button>
        </a>
    @endif
</div>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>Columna 1</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
        <tr>
            <td>{{ $item->campo }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="X">No hay registros</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $items->links() }}
</div>
@endsection