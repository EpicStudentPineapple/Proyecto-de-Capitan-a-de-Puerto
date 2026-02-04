@extends('layout.app')

@section('title', 'Registro')

@section('content')
<div style="max-width: 500px; margin: 0 auto;">
    <h2>Registro de Usuario</h2>
    
    @if ($errors->any())
        <div style="background-color: #fee; border: 1px solid #fcc; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px; color: #c00;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('register.submit') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label for="name">Nombre Completo o Empresa:</label><br>
            <input type="text" id="name" name="name" required 
                   style="width: 100%; padding: 8px;" 
                   value="{{ old('name') }}">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="email">Correo Electrónico:</label><br>
            <input type="email" id="email" name="email" required 
                   style="width: 100%; padding: 8px;" 
                   value="{{ old('email') }}">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required 
                   style="width: 100%; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="password_confirmation">Confirmar Contraseña:</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required 
                   style="width: 100%; padding: 8px;">
        </div>
        
        <hr>
        
        <h3>Datos del Perfil</h3>
        
        <div style="margin-bottom: 15px;">
            <label for="telefono">Teléfono:</label><br>
            <input type="text" id="telefono" name="telefono" 
                   style="width: 100%; padding: 8px;" 
                   value="{{ old('telefono') }}">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="empresa">Empresa:</label><br>
            <input type="text" id="empresa" name="empresa" 
                   style="width: 100%; padding: 8px;" 
                   value="{{ old('empresa') }}">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="cargo">Cargo:</label><br>
            <input type="text" id="cargo" name="cargo" 
                   style="width: 100%; padding: 8px;" 
                   value="{{ old('cargo') }}">
        </div>
        
        <button type="submit" style="width: 100%; padding: 10px; font-size: 16px;">
            Registrarse
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
    </p>
</div>
@endsection