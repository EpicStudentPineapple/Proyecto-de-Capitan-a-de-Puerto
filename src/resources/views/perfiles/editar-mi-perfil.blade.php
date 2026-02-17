@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">Editar Mi Perfil</h1>
    
    <a href="{{ route('perfil.mi-perfil') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
        ← Volver a mi perfil
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

    <form action="{{ route('perfil.actualizar-mi-perfil') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label for="telefono" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Teléfono:
            </label>
            <input type="tel" id="telefono" name="telefono"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('telefono', $perfil->telefono) }}">
            @error('telefono')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="empresa" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Empresa:
            </label>
            <input type="text" id="empresa" name="empresa"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('empresa', $perfil->empresa) }}">
            @error('empresa')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="cargo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Cargo:
            </label>
            <input type="text" id="cargo" name="cargo"
                style="width: 100%; padding: 10px; border: 1px solid #999; border-radius: 4px; font-size: 16px;"
                value="{{ old('cargo', $perfil->cargo) }}">
            @error('cargo')
                <span role="alert" style="color: #c00; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="padding: 12px 24px; font-size: 16px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Actualizar Perfil
            </button>
            
            <a href="{{ route('perfil.mi-perfil') }}" style="text-decoration: none;">
                <button type="button" style="padding: 12px 24px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
            </a>
        </div>
    </form>
</div>
@endsection
