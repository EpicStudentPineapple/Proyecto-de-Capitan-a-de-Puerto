@extends('layouts.app')

@section('title', 'Detalle')

@section('content')
<h1>Detalle: {{ $item->nombre }}</h1>

<p><a href="{{ route('ruta.index') }}">← Volver</a></p>

<div style="margin: 20px 0;">
    <a href="{{ route('ruta.edit', $item->id) }}">
        <button>Editar</button>
    </a>
    
    <form action="{{ route('ruta.destroy', $item->id) }}" 
          method="POST" style="display: inline;" 
          onsubmit="return confirm('¿Seguro?');">
        @csrf
        @method('DELETE')
        <button type="submit">Eliminar</button>
    </form>
</div>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
    <tr>
        <td><strong>Campo:</strong></td>
        <td>{{ $item->campo }}</td>
    </tr>
</table>
@endsection