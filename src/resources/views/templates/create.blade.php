@extends('layouts.app')

@section('title', isset($item) ? 'Editar' : 'Crear')

@section('content')
<h1>{{ isset($item) ? 'Editar' : 'Crear Nuevo' }}</h1>

<form action="{{ isset($item) ? route('ruta.update', $item->id) : route('ruta.store') }}" 
      method="POST" style="max-width: 600px;">
    @csrf
    @if(isset($item))
        @method('PUT')
    @endif
    
    <div style="margin-bottom: 15px;">
        <label for="campo">Campo: *</label><br>
        <input type="text" id="campo" name="campo" required 
               style="width: 100%; padding: 8px;" 
               value="{{ old('campo', $item->campo ?? '') }}">
    </div>
    
    <!-- Más campos -->
    
    <button type="submit" style="padding: 10px 20px;">
        {{ isset($item) ? 'Actualizar' : 'Crear' }}
    </button>
    
    <a href="{{ route('ruta.index') }}">
        <button type="button">Cancelar</button>
    </a>
</form>
@endsection