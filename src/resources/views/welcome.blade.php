<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Capitanía de Puerto</title>
</head>
<body>
    <div style="text-align: center; padding: 50px;">
        <h1>Sistema de Gestión Portuaria</h1>
        <h2>Capitanía del Puerto de San Sebastián</h2>
        
        <p>Bienvenido al sistema de gestión y control de tráfico marítimo</p>
        
        <div style="margin-top: 30px;">
            <a href="{{ route('login') }}">
                <button style="padding: 10px 20px; font-size: 16px; margin: 5px;">
                    Iniciar Sesión
                </button>
            </a>
            
            <a href="{{ route('register') }}">
                <button style="padding: 10px 20px; font-size: 16px; margin: 5px;">
                    Registrarse
                </button>
            </a>
        </div>
        
        <div style="margin-top: 50px;">
            <h3>Funcionalidades del Sistema:</h3>
            <ul style="text-align: left; display: inline-block;">
                <li>Gestión de Muelles y Pantalanes</li>
                <li>Control de Buques en Puerto</li>
                <li>Asignación Inteligente de Atraques (Drag & Drop)</li>
                <li>Servicios Portuarios</li>
                <li>Integración con Datos Climáticos (Euskalmet)</li>
                <li>Tráfico en Tiempo Real</li>
                <li>Gestión de Usuarios y Perfiles</li>
            </ul>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #f0f0f0;">
            <h4>Tipos de Usuario:</h4>
            <p><strong>Administrador:</strong> Control total del sistema</p>
            <p><strong>Propietario:</strong> Gestión de su flota</p>
        </div>
    </div>
</body>
</html>