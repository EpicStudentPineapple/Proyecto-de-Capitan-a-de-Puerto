@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">{{ isset($servicio) ? 'Editar Servicio' : 'Crear Nuevo Servicio' }}</h1>
    
    <a href="{{ route('servicios.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
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

    <form action="{{ isset($servicio) ? route('admin.servicios.update', $servicio->id) : route('admin.servicios.store') }}" method="POST">
        @csrf
        @if(isset($servicio))
            @method('PUT')
        @endif
        
        <div style="margin-bottom: 20px;">
            <label for="nombre" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Nombre del Servicio: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="text" id="nombre" name="nombre" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('nombre', $servicio->nombre ?? '') }}">
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
                value="{{ old('codigo', $servicio->codigo ?? '') }}" placeholder="Ej: SRV-001">
            @error('codigo')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="tipo_servicio" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Tipo de Servicio: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <select id="tipo_servicio" name="tipo_servicio" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                <option value="">Seleccionar...</option>
                <option value="practicaje" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'practicaje' ? 'selected' : '' }}>Practicaje</option>
                <option value="remolque" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'remolque' ? 'selected' : '' }}>Remolque</option>
                <option value="amarre" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'amarre' ? 'selected' : '' }}>Amarre</option>
                <option value="suministro_combustible" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'suministro_combustible' ? 'selected' : '' }}>Suministro Combustible</option>
                <option value="suministro_agua" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'suministro_agua' ? 'selected' : '' }}>Suministro Agua</option>
                <option value="suministro_electrico" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'suministro_electrico' ? 'selected' : '' }}>Suministro Eléctrico</option>
                <option value="retirada_residuos" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'retirada_residuos' ? 'selected' : '' }}>Retirada Residuos</option>
                <option value="limpieza" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'limpieza' ? 'selected' : '' }}>Limpieza</option>
                <option value="reparaciones" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'reparaciones' ? 'selected' : '' }}>Reparaciones</option>
                <option value="aprovisionamiento" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'aprovisionamiento' ? 'selected' : '' }}>Aprovisionamiento</option>
                <option value="inspeccion_aduana" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'inspeccion_aduana' ? 'selected' : '' }}>Inspección Aduana</option>
                <option value="sanidad_portuaria" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'sanidad_portuaria' ? 'selected' : '' }}>Sanidad Portuaria</option>
                <option value="seguridad" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'seguridad' ? 'selected' : '' }}>Seguridad</option>
                <option value="otros" {{ old('tipo_servicio', $servicio->tipo_servicio ?? '') == 'otros' ? 'selected' : '' }}>Otros</option>
            </select>
            @error('tipo_servicio')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="descripcion" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Descripción:
            </label>
            <textarea id="descripcion" name="descripcion" rows="4"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px; font-family: inherit;">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="precio_base" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Precio Base (€): <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <input type="number" step="0.01" id="precio_base" name="precio_base" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('precio_base', $servicio->precio_base ?? '') }}">
            @error('precio_base')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="unidad_cobro" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Unidad de Cobro: <span style="color: #c00;" aria-label="obligatorio">*</span>
            </label>
            <select id="unidad_cobro" name="unidad_cobro" required aria-required="true"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                <option value="fijo" {{ old('unidad_cobro', $servicio->unidad_cobro ?? 'fijo') == 'fijo' ? 'selected' : '' }}>Fijo</option>
                <option value="por_hora" {{ old('unidad_cobro', $servicio->unidad_cobro ?? '') == 'por_hora' ? 'selected' : '' }}>Por Hora</option>
                <option value="por_tonelada" {{ old('unidad_cobro', $servicio->unidad_cobro ?? '') == 'por_tonelada' ? 'selected' : '' }}>Por Tonelada</option>
                <option value="por_metro" {{ old('unidad_cobro', $servicio->unidad_cobro ?? '') == 'por_metro' ? 'selected' : '' }}>Por Metro</option>
                <option value="por_servicio" {{ old('unidad_cobro', $servicio->unidad_cobro ?? '') == 'por_servicio' ? 'selected' : '' }}>Por Servicio</option>
            </select>
            @error('unidad_cobro')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="tiempo_estimado_minutos" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Tiempo Estimado (minutos):
            </label>
            <input type="number" id="tiempo_estimado_minutos" name="tiempo_estimado_minutos"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('tiempo_estimado_minutos', $servicio->tiempo_estimado_minutos ?? '') }}">
            @error('tiempo_estimado_minutos')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="proveedor" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Proveedor:
            </label>
            <input type="text" id="proveedor" name="proveedor"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('proveedor', $servicio->proveedor ?? '') }}">
            @error('proveedor')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="telefono_contacto" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Teléfono de Contacto:
            </label>
            <input type="tel" id="telefono_contacto" name="telefono_contacto"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('telefono_contacto', $servicio->telefono_contacto ?? '') }}">
            @error('telefono_contacto')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <legend style="padding: 0 10px; font-weight: bold;">Opciones</legend>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="disponible_24h" value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('disponible_24h', $servicio->disponible_24h ?? false) ? 'checked' : '' }}>
                    <span>Disponible 24 horas</span>
                </label>
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="requiere_reserva" value="1" 
                        style="width: 20px; height: 20px; margin-right: 10px;"
                        {{ old('requiere_reserva', $servicio->requiere_reserva ?? true) ? 'checked' : '' }}>
                    <span>Requiere Reserva</span>
                </label>
            </div>
        </fieldset>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="padding: 12px 24px; font-size: 16px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                {{ isset($servicio) ? 'Actualizar Servicio' : 'Crear Servicio' }}
            </button>
            
            <a href="{{ route('servicios.index') }}" style="text-decoration: none;">
                <button type="button" style="padding: 12px 24px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
            </a>
        </div>
    </form>
</div>
@endsection
