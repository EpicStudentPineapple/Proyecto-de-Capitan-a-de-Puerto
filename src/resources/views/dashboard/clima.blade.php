@extends('layouts.app')

@section('title', 'Clima')@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════════════
   Estilos Modernizados - Dashboard Marítimo (Ajustado)
   ══════════════════════════════════════════════════════════════════════ */

:root {
    --primary: #0f172a;
    --secondary: #334155;
    --accent: #3b82f6;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --bg-page: #f8fafc;
    --card-bg: #ffffff;
}

.clima-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--space-4); /* Móvil base */
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background-color: var(--bg-page);
}

/* ── Encabezado ──────────────────────────────────────────────────────── */
.clima-page h1 {
    font-size: 1.8rem; /* Móvil base */
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 0.25rem;
    letter-spacing: -0.025em;
}

.clima-page .subtitulo {
    color: var(--secondary);
    margin-bottom: 2.5rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ── Grid principal ──────────────────────────────────────────────────── */
.clima-grid {
    display: grid;
    grid-template-columns: 1fr; /* Móvil base */
    gap: 1.5rem;
    margin-bottom: 3rem;
}

/* ── Tarjetas Estilizadas ────────────────────────────────────────────── */
.clima-card {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 1.5rem; /* Ajuste móvil */
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.clima-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.clima-card h2 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* ── Widget Clima Actual ────────────────────────────────────────────── */
.clima-actual {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.clima-icon {
    font-size: 4rem; /* Móvil base */
    margin: 1rem 0;
    filter: drop-shadow(0 10px 8px rgba(0,0,0,0.1));
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.temp-principal {
    font-size: 3.5rem; /* Móvil base */
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.temp-principal sup {
    font-size: 1.5rem;
    color: var(--accent);
}

.estado-cielo {
    font-weight: 600;
    color: var(--secondary);
    background: #f1f5f9;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    display: inline-block;
    margin-bottom: 2rem;
}

.detalles-actuales {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    border-top: 1px solid #f1f5f9;
    padding-top: 1.5rem;
}

.detalle-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
}

.detalle-valor {
    font-size: 1.1rem;
    color: var(--primary);
    font-weight: 700;
}

/* ── Tablas de Datos ─────────────────────────────────────────────────── */
.condiciones-tabla, .maniobras-tabla {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.75rem;
}

.condiciones-tabla td {
    padding: 0.75rem;
    background: #f8fafc;
}

.condiciones-tabla td:first-child {
    border-radius: 12px 0 0 12px;
    font-weight: 500;
    color: var(--secondary);
}

.condiciones-tabla td:last-child {
    border-radius: 0 12px 12px 0;
    text-align: right;
    font-weight: 700;
    color: var(--primary);
}

/* ── Badges y Banners ────────────────────────────────────────────────── */
.badge {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.05em;
}

.badge-seguro { background: #ecfdf5; color: #059669; }
.badge-peligro { background: #fef2f2; color: #dc2626; }

.banner-maniobras {
    margin-top: 2rem;
    padding: 1rem;
    border-radius: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
}

.banner-seguro { background: var(--success); color: white; }
.banner-peligro { background: var(--danger); color: white; }
.banner-alerta { background: var(--warning); color: white; }

/* ── Botón Actualizar ────────────────────────────────────────────────── */
.btn-refresh {
    background: var(--primary);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: none;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 auto;
    transition: all 0.2s;
}

.btn-refresh:hover {
    background: var(--accent);
    transform: scale(1.05);
}

/* ── Skeleton ───────────────────────────────────────────────────────── */
.skeleton {
    background: #e2e8f0;
    border-radius: 4px;
    position: relative;
    overflow: hidden;
    height: 1.2em;
}

.skeleton::after {
    content: "";
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* ── Pantallas Grandes (Desktop) ────────────────────────────── */
@media (min-width: 1024px) {
    .clima-page {
        padding: 3rem 1.5rem;
    }

    .clima-page h1 {
        font-size: 2.5rem;
    }

    .temp-principal {
        font-size: 4.5rem;
    }

    .clima-grid {
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    }

    .clima-card {
        padding: 2rem;
    }

    .clima-icon {
        font-size: 5rem;
    }
}

</style>
@endpush
@section('content')
<div class="clima-page">

    {{-- Encabezado --}}
    <h1>🌤 Condiciones Climáticas</h1>
    <p class="subtitulo">
        Pronóstico en tiempo real para <strong>Donostia – San Sebastián</strong>
        &mdash; fuente: <a href="https://open-meteo.com" target="_blank" rel="noopener">OpenMeteo</a>
    </p>

    <div class="clima-grid">

        {{-- Clima actual --}}
        <div class="clima-card clima-actual">
            <h2>📡 Condiciones Actuales</h2>
            
            <div class="clima-icon" id="clima-icon">🌤</div>
            
            <div class="temp-principal" id="temp-actual">
                <span class="skeleton" style="width: 80px;"></span>
            </div>
            
            <div class="estado-cielo" id="estado-cielo">
                <span class="skeleton" style="width: 150px;"></span>
            </div>
            
            <div class="detalles-actuales">
                <div class="detalle-item">
                    <span class="detalle-label">Sensación térmica</span>
                    <span class="detalle-valor" id="sensacion"><span class="skeleton" style="width: 60px;"></span></span>
                </div>
                <div class="detalle-item">
                    <span class="detalle-label">Humedad</span>
                    <span class="detalle-valor" id="humedad"><span class="skeleton" style="width: 50px;"></span></span>
                </div>
                <div class="detalle-item">
                    <span class="detalle-label">Viento</span>
                    <span class="detalle-valor" id="viento-actual"><span class="skeleton" style="width: 70px;"></span></span>
                </div>
                <div class="detalle-item">
                    <span class="detalle-label">Precipitación</span>
                    <span class="detalle-valor" id="precip-actual"><span class="skeleton" style="width: 50px;"></span></span>
                </div>
            </div>
        </div>

        {{-- Condiciones para maniobras --}}
        <div class="clima-card">
            <h2>⚓ Aptitud para Maniobras de Atraque</h2>
            
            <table class="maniobras-tabla">
                <thead>
                    <tr>
                        <th>Condición</th>
                        <th>Valor</th>
                        <th>Límite</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Altura de ola</td>
                        <td id="m-olas">—</td>
                        <td>&lt; 2.0 m</td>
                        <td id="m-olas-badge">—</td>
                    </tr>
                    <tr>
                        <td>Viento</td>
                        <td id="m-viento">—</td>
                        <td>&lt; 72 km/h</td>
                        <td id="m-viento-badge">—</td>
                    </tr>
                    <tr>
                        <td>Precipitación</td>
                        <td id="m-lluvia">—</td>
                        <td>&lt; 20 mm</td>
                        <td id="m-lluvia-badge">—</td>
                    </tr>
                </tbody>
            </table>

            <div id="banner-maniobras" class="banner-maniobras banner-alerta">
                ⏳ Calculando condiciones&hellip;
            </div>
        </div>

        {{-- Predicción de mañana --}}
        <div class="clima-card">
            <h2>📅 Predicción para Mañana</h2>
            <table class="condiciones-tabla">
                <tbody>
                    <tr>
                        <td>🌡 Temperatura máx.</td>
                        <td id="temp-max"><span class="skeleton" style="width: 60px;"></span></td>
                    </tr>
                    <tr>
                        <td>🌡 Temperatura mín.</td>
                        <td id="temp-min"><span class="skeleton" style="width: 60px;"></span></td>
                    </tr>
                    <tr>
                        <td>💨 Viento máximo</td>
                        <td id="viento-max"><span class="skeleton" style="width: 80px;"></span></td>
                    </tr>
                    <tr>
                        <td>💧 Precipitación</td>
                        <td id="precip-manana"><span class="skeleton" style="width: 60px;"></span></td>
                    </tr>
                    <tr>
                        <td>☁ Estado del cielo</td>
                        <td id="cielo-manana"><span class="skeleton" style="width: 120px;"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>{{-- /.clima-grid --}}

    <button class="btn-refresh" onclick="refrescarClima()">
        🔄 Actualizar datos
    </button>

    <p class="info-adicional">
        Los datos se actualizan automáticamente cada 5 minutos.
    </p>

</div>{{-- /.clima-page --}}
@endsection

@push('scripts')
<script>
/**
 * Script de clima - Integración con OpenMeteo API
 */

/* ── Constantes ──────────────────────────────────────────────────────── */
const LIMITES = {
    olas:   2.0,   // metros
    viento: 72,    // km/h
    lluvia: 20,    // mm
};

const ICONOS_CLIMA = {
    0: '☀️',   // Despejado
    1: '🌤',   // Principalmente despejado
    2: '⛅',   // Parcialmente nublado
    3: '☁️',   // Nublado
    45: '🌫',  // Niebla
    48: '🌫',  // Niebla con escarcha
    51: '🌦',  // Llovizna ligera
    53: '🌧',  // Llovizna moderada
    55: '🌧',  // Llovizna intensa
    61: '🌦',  // Lluvia ligera
    63: '🌧',  // Lluvia moderada
    65: '🌧',  // Lluvia intensa
    71: '🌨',  // Nevada ligera
    73: '❄️',  // Nevada moderada
    75: '❄️',  // Nevada intensa
    80: '🌦',  // Chubascos ligeros
    81: '⛈',   // Chubascos moderados
    82: '⛈',   // Chubascos violentos
    95: '⛈',   // Tormenta
    96: '⛈',   // Tormenta con granizo
    99: '⛈',   // Tormenta con granizo intenso
};

let intervalId = null;

/* ── Inicialización ──────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    cargarDatosClima();
    
    // Actualizar cada 5 minutos
    intervalId = setInterval(cargarDatosClima, 5 * 60 * 1000);
});

/* ── Función principal ───────────────────────────────────────────────── */
async function cargarDatosClima() {
    try {
        const response = await fetch('/api/clima/prediccion', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const datos = await response.json();

        if (datos.error) {
            throw new Error(datos.error);
        }

        mostrarDatosActuales(datos.actual);
        mostrarPrediccionManana(datos.manana);
        evaluarManiobras(datos.actual, datos.navegacion);

    } catch (error) {
        console.error('[Clima] Error:', error);
        mostrarError();
    }
}

/* ── Mostrar datos actuales ──────────────────────────────────────────── */
function mostrarDatosActuales(actual) {
    const icono = ICONOS_CLIMA[actual.codigo_clima] || '🌤';
    
    document.getElementById('clima-icon').textContent = icono;
    document.getElementById('temp-actual').innerHTML = `${actual.temperatura}<sup>°C</sup>`;
    document.getElementById('estado-cielo').textContent = actual.estado_cielo;
    document.getElementById('sensacion').textContent = `${actual.sensacion_termica}°C`;
    document.getElementById('humedad').textContent = `${actual.humedad}%`;
    document.getElementById('viento-actual').textContent = `${actual.viento} km/h ${actual.viento_direccion}`;
    document.getElementById('precip-actual').textContent = `${actual.precipitacion} mm`;
}

/* ── Mostrar predicción de mañana ────────────────────────────────────── */
function mostrarPrediccionManana(manana) {
    document.getElementById('temp-max').textContent = `${manana.temperatura_max}°C`;
    document.getElementById('temp-min').textContent = `${manana.temperatura_min}°C`;
    document.getElementById('viento-max').textContent = `${manana.viento_max} km/h ${manana.viento_direccion}`;
    document.getElementById('precip-manana').textContent = `${manana.precipitacion} mm`;
    document.getElementById('cielo-manana').textContent = manana.estado_cielo;
}

/* ── Evaluar condiciones para maniobras ──────────────────────────────── */
/* ── Evaluar condiciones para maniobras ACTUALES ────────────────────── */
function evaluarManiobras(actual, navegacion) {
    // Extraemos datos actuales y de navegación (olas)
    const olas = navegacion.altura_olas;
    const viento = actual.viento; // Ahora usa el viento de este momento
    const lluvia = actual.precipitacion; // Ahora usa la lluvia de este momento

    const aptoOlas = olas < LIMITES.olas;
    const aptoViento = viento < LIMITES.viento;
    const aptoLluvia = lluvia < LIMITES.lluvia;

    // Actualizar valores en la tabla
    document.getElementById('m-olas').textContent = `${olas} m`;
    document.getElementById('m-viento').textContent = `${viento} km/h`;
    document.getElementById('m-lluvia').textContent = `${lluvia} mm`;

    // Actualizar Badges (verde/rojo)
    mostrarBadge('m-olas-badge', aptoOlas);
    mostrarBadge('m-viento-badge', aptoViento);
    mostrarBadge('m-lluvia-badge', aptoLluvia);

    // Lógica del Banner Global
    const banner = document.getElementById('banner-maniobras');
    const todasAptas = aptoOlas && aptoViento && aptoLluvia;

    if (todasAptas) {
        banner.className = 'banner-maniobras banner-seguro';
        banner.innerHTML = '✅ <strong>OPERATIVO:</strong> Condiciones SEGURAS para atraque actual';
    } else {
        banner.className = 'banner-maniobras banner-peligro';
        banner.innerHTML = '⚠️ <strong>PRECAUCIÓN:</strong> Condiciones NO APTAS para maniobras en este momento';
    }
}

/* ── Mostrar badge de estado ─────────────────────────────────────────── */
function mostrarBadge(elementId, esSeguro) {
    const elemento = document.getElementById(elementId);
    if (esSeguro) {
        elemento.innerHTML = '<span class="badge badge-seguro">SEGURO</span>';
    } else {
        elemento.innerHTML = '<span class="badge badge-peligro">PELIGRO</span>';
    }
}

/* ── Mostrar error ───────────────────────────────────────────────────── */
function mostrarError() {
    const banner = document.getElementById('banner-maniobras');
    banner.className = 'banner-maniobras banner-alerta';
    banner.textContent = '⚠️ No se pudieron cargar los datos meteorológicos. Reintentando...';
}

/* ── Función de refresco manual ──────────────────────────────────────── */
function refrescarClima() {
    cargarDatosClima();
}

/* ── Limpiar intervalo al salir ──────────────────────────────────────── */
window.addEventListener('beforeunload', () => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>
@endpush