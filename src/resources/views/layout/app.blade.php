<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Capitanía de Puerto')</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    @stack('styles')
</head>
<body>
    <nav>
        <h1>Capitanía de Puerto - San Sebastián</h1>
        
        @auth
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('dashboard.trafico') }}">Tráfico</a></li>
                <li><a href="{{ route('dashboard.clima') }}">Clima</a></li>
                
                <li><a href="{{ route('muelles.index') }}">Muelles</a></li>
                <li><a href="{{ route('buques.index') }}">Buques</a></li>
                <li><a href="{{ route('servicios.index') }}">Servicios</a></li>
                <li><a href="{{ route('pantalans.index') }}">Pantalanes</a></li>
                
                <li><a href="{{ route('buques.gestion-atraques') }}">Gestión Atraques</a></li>
                
                <li><a href="{{ route('perfil.mi-perfil') }}">Mi Perfil</a></li>
                
                @if(Auth::user()->isAdministrador())
                    <li><a href="{{ route('perfiles.index') }}">Gestión Usuarios</a></li>
                @endif
                
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">Cerrar Sesión</button>
                    </form>
                </li>
            </ul>
            
            <p>Usuario: {{ Auth::user()->name }} 
                @if(Auth::user()->perfil)
                    ({{ Auth::user()->perfil->tipo_usuario }})
                @endif
            </p>
        @else
            <ul>
                <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                <li><a href="{{ route('register') }}">Registrarse</a></li>
            </ul>
        @endauth
    </nav>
    
    <hr>

    @if(session('success'))
        <div style="background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;">
            <strong>Errores:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <main style="padding: 20px;">
        @yield('content')
    </main>
    
    <hr>

    <footer style="text-align: center; padding: 20px;">
        <p>&copy; 2026 Capitanía de Puerto de San Sebastián | Proyecto 2DAW3</p>
    </footer>
    
    @stack('scripts')
</body>
</html>