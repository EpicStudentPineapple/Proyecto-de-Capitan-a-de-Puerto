<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestorinator3000</title>
</head>
<body>
    <div style="text-aling: center; padding: 50px;">
        <h1>Sistema de Gestión portuaria</h1>
        <h2>Capitanía del Puerto de San Sebastian</h2>

        <p>Bienvenido al sistema de gestión y control de tráfico marítimo</p>

        <div style="margin-top: 30px;">
            <a href="{{ route('login') }}">
                <button style="padding: 10px 20px; font-size: 16px; margin: 5px;">
                    Iniciar sesión
                </button>
            </a>
            <a href="{{ route('register') }}">
                <button style="padding: 10px 20px; font-size: 16px; margin: 5px;">
                    Registrarse
                </button>
            </a>
        </div>

    </div>
</body>
</html>