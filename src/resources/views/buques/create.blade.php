@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">{{ isset($buque) ? 'Editar Buque' : 'Registrar Nuevo Buque' }}</h1>
    
    <a href="{{ auth()->user()->isAdmin() ? route('admin.buques.index') : route('propietario.buques.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
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

    <form action="{{ isset($buque) ? (auth()->user()->isAdmin() ? route('admin.buques.update', $buque->id) : route('propietario.buques.update', $buque->id)) : (auth()->user()->isAdmin() ? route('admin.buques.store') : route('propietario.buques.store')) }}" method="POST">
        @csrf
        @if(isset($buque))
            @method('PUT')
        @endif
        
        <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <legend style="padding: 0 10px; font-weight: bold;">Información Básica</legend>
            
            <div style="margin-bottom: 20px;">
                <label for="nombre" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Nombre del Buque: <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="text" id="nombre" name="nombre" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('nombre', $buque->nombre ?? '') }}">
                @error('nombre')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="imo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Número IMO: <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="text" id="imo" name="imo" required aria-required="true" maxlength="10"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('imo', $buque->imo ?? '') }}" placeholder="Ej: IMO1234567">
                @error('imo')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="mmsi" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    MMSI:
                </label>
                <input type="text" id="mmsi" name="mmsi" maxlength="15"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('mmsi', $buque->mmsi ?? '') }}">
                @error('mmsi')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="bandera" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Bandera: <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="text" id="bandera" name="bandera" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('bandera', $buque->bandera ?? '') }}" placeholder="Ej: España">
                @error('bandera')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="tipo_buque" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Tipo de Buque: <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <select id="tipo_buque" name="tipo_buque" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                    <option value="">Seleccionar...</option>
                    <option value="portacontenedores" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'portacontenedores' ? 'selected' : '' }}>Portacontenedores</option>
                    <option value="granelero" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'granelero' ? 'selected' : '' }}>Granelero</option>
                    <option value="petrolero" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'petrolero' ? 'selected' : '' }}>Petrolero</option>
                    <option value="gasero" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'gasero' ? 'selected' : '' }}>Gasero</option>
                    <option value="pesquero" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'pesquero' ? 'selected' : '' }}>Pesquero</option>
                    <option value="ferry" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'ferry' ? 'selected' : '' }}>Ferry</option>
                    <option value="ro-ro" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'ro-ro' ? 'selected' : '' }}>Ro-Ro</option>
                    <option value="carga_general" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'carga_general' ? 'selected' : '' }}>Carga General</option>
                    <option value="crucero" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'crucero' ? 'selected' : '' }}>Crucero</option>
                    <option value="deportivo" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'deportivo' ? 'selected' : '' }}>Deportivo</option>
                    <option value="narcolancha" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'narcolancha' ? 'selected' : '' }}>Narcolancha</option>
                    <option value="remolcador" {{ old('tipo_buque', $buque->tipo_buque ?? '') == 'remolcador' ? 'selected' : '' }}>Remolcador</option>
                </select>
                @error('tipo_buque')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>
        </fieldset>

        <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <legend style="padding: 0 10px; font-weight: bold;">Dimensiones</legend>
            
            <div style="margin-bottom: 20px;">
                <label for="eslora" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Eslora (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="number" step="0.01" id="eslora" name="eslora" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('eslora', $buque->eslora ?? '') }}">
                @error('eslora')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="manga" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Manga (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="number" step="0.01" id="manga" name="manga" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('manga', $buque->manga ?? '') }}">
                @error('manga')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="calado" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Calado (metros): <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="number" step="0.01" id="calado" name="calado" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('calado', $buque->calado ?? '') }}">
                @error('calado')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="tonelaje_bruto" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Tonelaje Bruto: <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <input type="number" id="tonelaje_bruto" name="tonelaje_bruto" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('tonelaje_bruto', $buque->tonelaje_bruto ?? '') }}">
                @error('tonelaje_bruto')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>
        </fieldset>

        <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <legend style="padding: 0 10px; font-weight: bold;">Información Operativa</legend>
            
            @if(auth()->user()->isAdmin())
                <div style="margin-bottom: 20px;">
                    <label for="propietario_id" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        Propietario: <span style="color: #c00;" aria-label="obligatorio">*</span>
                    </label>
                    <select id="propietario_id" name="propietario_id" required aria-required="true"
                        style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                        <option value="">Seleccionar...</option>
                        @foreach($propietarios as $propietario)
                            <option value="{{ $propietario->id }}" {{ old('propietario_id', $buque->propietario_id ?? '') == $propietario->id ? 'selected' : '' }}>
                                {{ $propietario->name }} ({{ $propietario->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('propietario_id')
                        <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <input type="hidden" name="propietario_id" value="{{ auth()->id() }}">
            @endif

            <div style="margin-bottom: 20px;">
                <label for="muelle_id" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Muelle Asignado:
                </label>
                <select id="muelle_id" name="muelle_id"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                    <option value="">Sin asignar</option>
                    @foreach($muelles as $muelle)
                        <option value="{{ $muelle->id }}" {{ old('muelle_id', $buque->muelle_id ?? '') == $muelle->id ? 'selected' : '' }}>
                            {{ $muelle->nombre }} ({{ $muelle->codigo }})
                        </option>
                    @endforeach
                </select>
                @error('muelle_id')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="estado" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Estado: <span style="color: #c00;" aria-label="obligatorio">*</span>
                </label>
                <select id="estado" name="estado" required aria-required="true"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;">
                    <option value="navegando" {{ old('estado', $buque->estado ?? 'navegando') == 'navegando' ? 'selected' : '' }}>Navegando</option>
                    <option value="fondeado" {{ old('estado', $buque->estado ?? '') == 'fondeado' ? 'selected' : '' }}>Fondeado</option>
                    <option value="atracado" {{ old('estado', $buque->estado ?? '') == 'atracado' ? 'selected' : '' }}>Atracado</option>
                    <option value="en_maniobra" {{ old('estado', $buque->estado ?? '') == 'en_maniobra' ? 'selected' : '' }}>En Maniobra</option>
                    <option value="mantenimiento" {{ old('estado', $buque->estado ?? '') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
                @error('estado')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="carga_actual" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Carga Actual (toneladas):
                </label>
                <input type="number" id="carga_actual" name="carga_actual"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('carga_actual', $buque->carga_actual ?? '') }}">
                @error('carga_actual')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="tripulacion" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Tripulación:
                </label>
                <input type="number" id="tripulacion" name="tripulacion"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                    value="{{ old('tripulacion', $buque->tripulacion ?? '') }}">
                @error('tripulacion')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="observaciones" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Observaciones:
                </label>
                <textarea id="observaciones" name="observaciones" rows="4"
                    style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px; font-family: inherit;">{{ old('observaciones', $buque->observaciones ?? '') }}</textarea>
                @error('observaciones')
                    <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>
        </fieldset>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="padding: 12px 24px; font-size: 16px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                {{ isset($buque) ? 'Actualizar Buque' : 'Registrar Buque' }}
            </button>
            
            <a href="{{ auth()->user()->isAdmin() ? route('admin.buques.index') : route('propietario.buques.index') }}" style="text-decoration: none;">
                <button type="button" style="padding: 12px 24px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
            </a>
        </div>
    </form>
</div>
@endsection
