@extends('layout.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div style="max-width: 400px; margin: 0 auto;">
    <h2>Iniciar Sesión</h2>
    
    @if ($errors->any())
        <div style="background-color: #fee; border: 1px solid #fcc; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px; color: #c00;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        
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
            <label>
                <input type="checkbox" name="remember"> Recordarme
            </label>
        </div>
        
        <button type="submit" style="width: 100%; padding: 10px; font-size: 16px;">
            Iniciar Sesión
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
    </p>
</div>
@endsection