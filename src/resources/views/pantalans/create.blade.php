@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">{{ isset($pantalan) ? 'Editar Pantalán' : 'Crear Nuevo Pantalán' }}</h1>
    
    <a href="{{ route('pantalans.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
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

    <form action="{{ isset($pantalan) ? route('admin.pantalans.update', $pantalan->id) : route('admin.pantalans.store') }}" method="POST">
        @csrf
        @if(isset($pantalan))
            @method('PUT')
        @endif
        
        <div style="margin-bottom: 20px;">
            <label for="muelle_id" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Muelle: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <select id="muelle_id" name="muelle_id" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                <option value="">Seleccionar...</option>
                @foreach($muelles as $muelle)
                    <option value="{{ $muelle->id }}" {{ old('muelle_id', $pantalan->muelle_id ?? '') == $muelle->id ? 'selected' : '' }}>
                        {{ $muelle->nombre }} ({{ $muelle->codigo }})
                    </option>
                @endforeach
            </select>
            @error('muelle_id')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="nombre" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Nombre: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="text" id="nombre" name="nombre" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('nombre', $pantalan->nombre ?? '') }}">
            @error('nombre')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="codigo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Código: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="text" id="codigo" name="codigo" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('codigo', $pantalan->codigo ?? '') }}" placeholder="Ej: PAN-001">
            @error('codigo')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="numero_amarre" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Número de Amarre: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="number" id="numero_amarre" name="numero_amarre" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('numero_amarre', $pantalan->numero_amarre ?? '') }}">
            @error('numero_amarre')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="tipo_amarre" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Tipo de Amarre: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <select id="tipo_amarre" name="tipo_amarre" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                <option value="finger" {{ old('tipo_amarre', $pantalan->tipo_amarre ?? 'lateral') == 'finger' ? 'selected' : '' }}>Finger</option>
                <option value="lateral" {{ old('tipo_amarre', $pantalan->tipo_amarre ?? 'lateral') == 'lateral' ? 'selected' : '' }}>Lateral</option>
                <option value="muerto" {{ old('tipo_amarre', $pantalan->tipo_amarre ?? '') == 'muerto' ? 'selected' : '' }}>Muerto</option>
                <option value="boya" {{ old('tipo_amarre', $pantalan->tipo_amarre ?? '') == 'boya' ? 'selected' : '' }}>Boya</option>
            </select>
            @error('tipo_amarre')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="longitud_maxima" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Longitud Máxima (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="number" step="0.01" id="longitud_maxima" name="longitud_maxima" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('longitud_maxima', $pantalan->longitud_maxima ?? '') }}">
            @error('longitud_maxima')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="manga_maxima" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Manga Máxima (metros):
            </label>
            <input type="number" step="0.01" id="manga_maxima" name="manga_maxima"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('manga_maxima', $pantalan->manga_maxima ?? '') }}">
            @error('manga_maxima')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="calado_maximo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Calado Máximo (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="number" step="0.01" id="calado_maximo" name="calado_maximo" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('calado_maximo', $pantalan->calado_maximo ?? '') }}">
            @error('calado_maximo')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="amperaje" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Amperaje:
            </label>
            <input type="number" id="amperaje" name="amperaje"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('amperaje', $pantalan->amperaje ?? '') }}">
            @error('amperaje')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="precio_dia" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Precio por Día (€):
            </label>
            <input type="number" step="0.01" id="precio_dia" name="precio_dia"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('precio_dia', $pantalan->precio_dia ?? '') }}">
            @error('precio_dia')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <legend style="padding: 0 10px; font-weight: bold;">Servicios Disponibles</legend>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="agua_disponible" value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('agua_disponible', $pantalan->agua_disponible ?? true) ? 'checked' : '' }}>
                    <span>Agua Disponible</span>
                </label>
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="electricidad_disponible" value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('electricidad_disponible', $pantalan->electricidad_disponible ?? true) ? 'checked' : '' }}>
                    <span>Electricidad Disponible</span>
                </label>
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="wifi_disponible" value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('wifi_disponible', $pantalan->wifi_disponible ?? false) ? 'checked' : '' }}>
                    <span>WiFi Disponible</span>
                </label>
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="disponible" value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('disponible', $pantalan->disponible ?? true) ? 'checked' : '' }}>
                    <span>Pantalán Disponible</span>
                </label>
            </div>
        </fieldset>

        <div style="margin-bottom: 20px;">
            <label for="observaciones" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Observaciones:
            </label>
            <textarea id="observaciones" name="observaciones" rows="4"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px; font-family: inherit;">{{ old('observaciones', $pantalan->observaciones ?? '') }}</textarea>
            @error('observaciones')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="padding: 12px 24px; font-size: 16px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                {{ isset($pantalan) ? 'Actualizar Pantalán' : 'Crear Pantalán' }}
            </button>
            
            <a href="{{ route('pantalans.index') }}" style="text-decoration: none;">
                <button type="button" style="padding: 12px 24px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
            </a>
        </div>
    </form>
</div>
@endsection
