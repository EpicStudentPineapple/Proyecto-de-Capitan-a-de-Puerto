@extends('layouts.app')

@section('title', 'Editar Muelle')

@section('content')
<div class="muelle-form-page">
    <div class="page-header-simple">
        <h1>Editar Muelle: {{ $muelle->nombre }}</h1>
        <a href="{{ route('muelles.index') }}" class="link-back">← Volver al listado</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-6">
            <p><strong>Corrija los siguientes errores:</strong></p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="muelle-form-card card">
        <form action="{{ route('admin.muelles.update', $muelle->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Muelle *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required 
                           value="{{ old('nombre', $muelle->nombre) }}">
                    @error('nombre') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="codigo" class="form-label">Código *</label>
                    <input type="text" id="codigo" name="codigo" class="form-control" required 
                           value="{{ old('codigo', $muelle->codigo) }}">
                    <small class="form-hint">Código único de identificación</small>
                    @error('codigo') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="tipo_muelle" class="form-label">Tipo de Muelle *</label>
                    <select id="tipo_muelle" name="tipo_muelle" class="form-control" required>
                        <option value="">Seleccionar...</option>
                        <option value="contenedores" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'contenedores' ? 'selected' : '' }}>Contenedores</option>
                        <option value="carga_general" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'carga_general' ? 'selected' : '' }}>Carga General</option>
                        <option value="graneles" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'graneles' ? 'selected' : '' }}>Graneles</option>
                        <option value="pesquero" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'pesquero' ? 'selected' : '' }}>Pesquero</option>
                        <option value="pasajeros" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'pasajeros' ? 'selected' : '' }}>Pasajeros</option>
                        <option value="ro-ro" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'ro-ro' ? 'selected' : '' }}>Ro-Ro</option>
                        <option value="hidrocarburos" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'hidrocarburos' ? 'selected' : '' }}>Hidrocarburos</option>
                        <option value="deportivo" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'deportivo' ? 'selected' : '' }}>Deportivo</option>
                        <option value="servicios" {{ old('tipo_muelle', $muelle->tipo_muelle) == 'servicios' ? 'selected' : '' }}>Servicios</option>
                    </select>
                    @error('tipo_muelle') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="capacidad_toneladas" class="form-label">Capacidad (toneladas) *</label>
                    <input type="number" id="capacidad_toneladas" name="capacidad_toneladas" class="form-control" required 
                           value="{{ old('capacidad_toneladas', $muelle->capacidad_toneladas) }}">
                    @error('capacidad_toneladas') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="longitud" class="form-label">Longitud (metros) *</label>
                    <input type="number" step="0.01" id="longitud" name="longitud" class="form-control" required 
                           value="{{ old('longitud', $muelle->longitud) }}">
                    @error('longitud') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label for="calado_maximo" class="form-label">Calado Máximo (metros) *</label>
                    <input type="number" step="0.01" id="calado_maximo" name="calado_maximo" class="form-control" required 
                           value="{{ old('calado_maximo', $muelle->calado_maximo) }}">
                    @error('calado_maximo') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Características y Disponibilidad</label>
                <div class="checkbox-group-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="disponible" value="1" {{ old('disponible', $muelle->disponible) ? 'checked' : '' }}>
                        <span>Muelle Disponible</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="grua_disponible" value="1" {{ old('grua_disponible', $muelle->grua_disponible) ? 'checked' : '' }}>
                        <span>Grúa Disponible</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="energia_tierra" value="1" {{ old('energia_tierra', $muelle->energia_tierra) ? 'checked' : '' }}>
                        <span>Energía de Tierra</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="3" class="form-control">{{ old('observaciones', $muelle->observaciones) }}</textarea>
                @error('observaciones') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Actualizar Muelle
                </button>
                <a href="{{ route('muelles.index') }}" class="btn btn-outline">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
