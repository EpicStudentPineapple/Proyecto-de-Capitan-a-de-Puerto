@extends('layouts.app')

@section('title', 'Condiciones Climáticas')

@section('content')
<h1>Condiciones Climáticas - Euskalmet</h1>

<p><a href="{{ route('dashboard') }}">← Volver al Dashboard</a></p>

<div style="margin: 20px 0; padding: 20px; background: #e3f2fd; border: 1px solid #90caf9;">
    <h2>Integración API Euskalmet</h2>
    <p>Esta sección mostrará datos en tiempo real del servicio meteorológico vasco.</p>
    <p><strong>Datos a mostrar:</strong></p>
    <ul>
        <li>Altura de olas</li>
        <li>Velocidad y dirección del viento</li>
        <li>Temperatura</li>
        <li>Visibilidad</li>
        <li>Estado de la mar</li>
        <li>Mareas (pleamar y bajamar)</li>
    </ul>
</div>

<div style="margin: 30px 0;">
    <h2>Condiciones Actuales en San Sebastián</h2>
    
    <table border="1" cellpadding="15" cellspacing="0" style="width: 100%;">
        <tr>
            <td><strong>Temperatura:</strong></td>
            <td>18°C</td>
        </tr>
        <tr>
            <td><strong>Viento:</strong></td>
            <td>15 nudos | Dirección: NE</td>
        </tr>
        <tr>
            <td><strong>Altura de Olas:</strong></td>
            <td>1.2 metros</td>
        </tr>
        <tr>
            <td><strong>Visibilidad:</strong></td>
            <td>10 km (Excelente)</td>
        </tr>
        <tr>
            <td><strong>Estado del Mar:</strong></td>
            <td>Mar en Calma</td>
        </tr>
        <tr>
            <td><strong>Precipitación:</strong></td>
            <td>0 mm</td>
        </tr>
    </table>
</div>

<div style="margin: 30px 0;">
    <h2>Predicción de Mareas</h2>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Altura</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>06:30</td>
                <td>🔼 Pleamar</td>
                <td>3.8 metros</td>
            </tr>
            <tr>
                <td>12:45</td>
                <td>🔽 Bajamar</td>
                <td>1.2 metros</td>
            </tr>
            <tr>
                <td>18:55</td>
                <td>🔼 Pleamar</td>
                <td>4.1 metros</td>
            </tr>
            <tr>
                <td>00:30 (mañana)</td>
                <td>🔽 Bajamar</td>
                <td>0.9 metros</td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin: 30px 0;">
    <h2>Condiciones para Maniobras</h2>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>Condición</th>
                <th>Valor Actual</th>
                <th>Límite Seguro</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Altura de Ola</td>
                <td>1.2 m</td>
                <td>&lt; 2.0 m</td>
                <td style="background: #c8e6c9;">✅ APTO</td>
            </tr>
            <tr>
                <td>Viento</td>
                <td>15 nudos</td>
                <td>&lt; 40 nudos</td>
                <td style="background: #c8e6c9;">✅ APTO</td>
            </tr>
            <tr>
                <td>Visibilidad</td>
                <td>10 km</td>
                <td>&gt; 1 km</td>
                <td style="background: #c8e6c9;">✅ APTO</td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top: 20px; padding: 15px; background: #c8e6c9; border: 1px solid #81c784;">
        <strong>✅ Condiciones ÓPTIMAS para maniobras de atraque</strong>
    </div>
</div>

<div style="margin: 30px 0;">
    <h2>🔗 Integración con API</h2>
    <p>Para implementar la integración real con Euskalmet:</p>
    <ol>
        <li>Obtener API Key de <a href="https://www.euskalmet.euskadi.eus" target="_blank">Euskalmet</a></li>
        <li>Configurar endpoint en el controlador</li>
        <li>Procesar datos JSON de la API</li>
        <li>Actualizar esta vista con datos reales</li>
    </ol>
    
    <p><strong>Endpoint de ejemplo:</strong></p>
    <code style="background: #f5f5f5; padding: 10px; display: block;">
        GET /api/clima/actual
    </code>
</div>

<div style="margin: 30px 0;">
    <button onclick="location.reload()">🔄 Actualizar Datos Climáticos</button>
</div>
@endsection