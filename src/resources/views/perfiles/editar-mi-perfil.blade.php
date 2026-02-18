@extends('layouts.app')

@section('content')
<div class="perfiles-page">
    <div class="profile-header">
        <h1>Editar Mi Perfil</h1>
    </div>
    
    <a href="{{ route('perfil.mi-perfil') }}" class="back-link">
        Volver a mi perfil
    </a>

    @if ($errors->any())
        <div role="alert" aria-live="polite" class="alert alert-danger">
            <strong>Errores en el formulario:</strong>
            <ul style="margin: var(--space-2) 0 0 var(--space-5);">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-section">
        <form action="{{ route('perfil.actualizar-mi-perfil') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nombre" class="form-label">
                    Nombre:
                </label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                    value="{{ old('nombre', $user->name) }}" required>
                @error('nombre')
                    <span class="form-error" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">
                    Teléfono:
                </label>
                <input type="tel" id="telefono" name="telefono" class="form-control"
                    value="{{ old('telefono', $perfil->telefono) }}">
                @error('telefono')
                    <span class="form-error" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="empresa" class="form-label">
                    Empresa:
                </label>
                <input type="text" id="empresa" name="empresa" class="form-control"
                    value="{{ old('empresa', $perfil->empresa) }}">
                @error('empresa')
                    <span class="form-error" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="cargo" class="form-label">
                    Cargo:
                </label>
                <input type="text" id="cargo" name="cargo" class="form-control"
                    value="{{ old('cargo', $perfil->cargo) }}">
                @error('cargo')
                    <span class="form-error" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="licencia_maritima" class="form-label">
                    Licencia Marítima:
                </label>
                <input type="text" id="licencia_maritima" name="licencia_maritima" class="form-control"
                    value="{{ old('licencia_maritima', $perfil->licencia_maritima) }}">
                @error('licencia_maritima')
                    <span class="form-error" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="profile-form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    Actualizar Perfil
                </button>
                
                <a href="{{ route('perfil.mi-perfil') }}" class="btn btn-outline btn-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
