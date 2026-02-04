@extends('layout.app')

@section('title', isset($muelle) ? 'Editar Muelle' : 'Crear Muelle')

@section('content')
<h1>{{ isset($muelle) ? 'Editar Muelle' : 'Crear Nuevo Muelle' }}</h1>

<p><a href="{{ route('muelles.index') }}">← Volver al listado</a></p>

<form action="{{ isset($muelle) ? route('muelles.update', $muelle->id) : route('muelles.store') }}" 
      method="POST" style="max-width: 600px;">
    @csrf
    @if(isset($muelle))
        @method('PUT')
    @endif
    
    <div style="margin-bottom: 15px;">
        <label for="nombre">Nombre del Muelle: *</label><br>
        <input type="text" id="nombre" name="nombre" required 
               style="width: 100%; padding: 8px;" 
               value="{{ old('nombre', $muelle->nombre ?? '') }}">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="codigo">Código: *</label><br>
        <input type="text" id="codigo" name="codigo" required 
               style="width: 100%; padding: 8px;" 
               value="{{ old('codigo', $muelle->codigo ?? '') }}"
               placeholder="Ej: MCA-01">
        <small>Código único de identificación</small>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="tipo_muelle">Tipo de Muelle: *</label><br>
        <select id="tipo_muelle" name="tipo_muelle" required style="width: 100%; padding: 8px;">
            <option value="">Seleccionar...</option>
            <option value="contenedores" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'contenedores' ? 'selected' : '' }}>Contenedores</option>
            <option value="carga_general" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'carga_general' ? 'selected' : '' }}>Carga General</option>
            <option value="graneles" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'graneles' ? 'selected' : '' }}>Graneles</option>
            <option value="pesquero" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'pesquero' ? 'selected' : '' }}>Pesquero</option>
            <option value="pasajeros" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'pasajeros' ? 'selected' : '' }}>Pasajeros</option>
            <option value="ro-ro" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'ro-ro' ? 'selected' : '' }}>Ro-Ro</option>
            <option value="hidrocarburos" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'hidrocarburos' ? 'selected' : '' }}>Hidrocarburos</option>
            <option value="deportivo" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'deportivo' ? 'selected' : '' }}>Deportivo</option>
            <option value="servicios" {{ old('tipo_muelle', $muelle->tipo_muelle ?? '') == 'servicios' ? 'selected' : '' }}>Servicios</option>
        </select>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="longitud">Longitud (metros): *</label><br>
        <input type="number" step="0.01" id="longitud" name="longitud" required 
               style="width: 100%; padding: 8px;" 
               value="{{ old('longitud', $muelle->longitud ?? '') }}">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="calado_maximo">Calado Máximo (metros): *</label><br>
        <input type="number" step="0.01" id="calado_maximo" name="calado_maximo" required 
               style="width: 100%; padding: 8px;" 
               value="{{ old('calado_maximo', $muelle->calado_maximo ?? '') }}">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="capacidad_toneladas">Capacidad (toneladas): *</label><br>
        <input type="number" id="capacidad_toneladas" name="capacidad_toneladas" required 
               style="width: 100%; padding: 8px;" 
               value="{{ old('capacidad_toneladas', $muelle->capacidad_toneladas ?? '') }}">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="disponible" value="1" 
                   {{ old('disponible', $muelle->disponible ?? true) ? 'checked' : '' }}>
            Muelle Disponible
        </label>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="grua_disponible" value="1" 
                   {{ old('grua_disponible', $muelle->grua_disponible ?? false) ? 'checked' : '' }}>
            Grúa Disponible
        </label>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="energia_tierra" value="1" 
                   {{ old('energia_tierra', $muelle->energia_tierra ?? false) ? 'checked' : '' }}>
            Energía de Tierra
        </label>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="observaciones">Observaciones:</label><br>
        <textarea id="observaciones" name="observaciones" rows="4" 
                  style="width: 100%; padding: 8px;">{{ old('observaciones', $muelle->observaciones ?? '') }}</textarea>
    </div>
    
    <button type="submit" style="padding: 10px 20px; font-size: 16px;">
        {{ isset($muelle) ? 'Actualizar Muelle' : 'Crear Muelle' }}
    </button>
    
    <a href="{{ route('muelles.index') }}">
        <button type="button" style="padding: 10px 20px; font-size: 16px;">
            ❌ Cancelar
        </button>
    </a>
</form>
@endsection