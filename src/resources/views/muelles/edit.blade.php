@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">Editar Muelle</h1>
    
    <a href="{{ route('muelles.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
        ← Volver al listado
    </a>

    @if ($errors->any())
        <div role="alert" aria-live="polite" style="background: #fee; border: 1px solid #c00; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <strong style="color: #c00;">Errores en el formulario:</strong>
            <ul style="margin: 10px 0 0 20px; color: #c00;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.muelles.update', $muelle->id) }}" method="POST" style="max-width: 600px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label for="nombre" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Nombre del Muelle: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input 
                type="text" 
                id="nombre" 
                name="nombre" 
                required 
                aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('nombre', $muelle->nombre) }}"
            >
            @error('nombre')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="codigo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Código: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input 
                type="text" 
                id="codigo" 
                name="codigo" 
                required 
                aria-required="true"
                aria-describedby="codigo-help"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('codigo', $muelle->codigo) }}"
                placeholder="Ej: MCA-01"
            >
            <small id="codigo-help" style="display: block; margin-top: 5px; color: #666;">
                Código único de identificación
            </small>
            @error('codigo')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="tipo_muelle" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Tipo de Muelle: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <select 
                id="tipo_muelle" 
                name="tipo_muelle" 
                required 
                aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
            >
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
            @error('tipo_muelle')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="longitud" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Longitud (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input 
                type="number" 
                step="0.01" 
                id="longitud" 
                name="longitud" 
                required 
                aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('longitud', $muelle->longitud) }}"
            >
            @error('longitud')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="calado_maximo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Calado Máximo (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input 
                type="number" 
                step="0.01" 
                id="calado_maximo" 
                name="calado_maximo" 
                required 
                aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('calado_maximo', $muelle->calado_maximo) }}"
            >
            @error('calado_maximo')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="capacidad_toneladas" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Capacidad (toneladas): <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input 
                type="number" 
                id="capacidad_toneladas" 
                name="capacidad_toneladas" 
                required 
                aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('capacidad_toneladas', $muelle->capacidad_toneladas) }}"
            >
            @error('capacidad_toneladas')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <legend style="padding: 0 10px; font-weight: bold;">Características</legend>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input 
                        type="checkbox" 
                        name="disponible" 
                        value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('disponible', $muelle->disponible) ? 'checked' : '' }}
                    >
                    <span>Muelle Disponible</span>
                </label>
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input 
                        type="checkbox" 
                        name="grua_disponible" 
                        value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('grua_disponible', $muelle->grua_disponible) ? 'checked' : '' }}
                    >
                    <span>Grúa Disponible</span>
                </label>
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input 
                        type="checkbox" 
                        name="energia_tierra" 
                        value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('energia_tierra', $muelle->energia_tierra) ? 'checked' : '' }}
                    >
                    <span>Energía de Tierra</span>
                </label>
            </div>
        </fieldset>
        
        <div style="margin-bottom: 20px;">
            <label for="observaciones" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Observaciones:
            </label>
            <textarea 
                id="observaciones" 
                name="observaciones" 
                rows="4" 
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px; font-family: inherit;"
            >{{ old('observaciones', $muelle->observaciones) }}</textarea>
            @error('observaciones')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button 
                type="submit" 
                style="padding: 12px 24px; font-size: 16px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;"
            >
                Actualizar Muelle
            </button>
            
            <a href="{{ route('muelles.index') }}" style="text-decoration: none;">
                <button 
                    type="button" 
                    style="padding: 12px 24px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer;"
                >
                    Cancelar
                </button>
            </a>
        </div>
    </form>
</div>
@endsection
