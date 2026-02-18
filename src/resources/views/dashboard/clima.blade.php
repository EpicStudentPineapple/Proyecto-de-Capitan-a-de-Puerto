@extends('layouts.app')

@section('title', 'Condiciones Climáticas — Donostia')

{{-- ══════════════════════════════════════════════════════════════════════
     CSS del widget Euskalmet
     Archivo: public/css/euskalmet.css
     ══════════════════════════════════════════════════════════════════════ --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/euskalmet.css') }}">
<style>
/* ── Página de clima ─────────────────────────────────────────────────── */
.clima-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 1.5rem;
}

.clima-page h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1e3a5f;
    margin-bottom: .25rem;
}

.clima-page .subtitulo {
    color: #64748b;
    font-size: .95rem;
    margin-bottom: 1.75rem;
}

/* ── Grid principal ──────────────────────────────────────────────────── */
.clima-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

@media (max-width: 700px) {
    .clima-grid { grid-template-columns: 1fr; }
}

/* ── Tarjeta genérica ────────────────────────────────────────────────── */
.clima-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

.clima-card h2 {
    font-size: 1rem;
    font-weight: 600;
    color: #1e3a5f;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

/* ── Tabla de condiciones ────────────────────────────────────────────── */
.condiciones-tabla {
    width: 100%;
    border-collapse: collapse;
    font-size: .9rem;
}

.condiciones-tabla td {
    padding: .55rem .75rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.condiciones-tabla td:first-child {
    color: #64748b;
    font-weight: 500;
    width: 45%;
}

.condiciones-tabla td:last-child {
    font-weight: 600;
    color: #1e293b;
}

/* ── Estado de maniobras ─────────────────────────────────────────────── */
.maniobras-tabla {
    width: 100%;
    border-collapse: collapse;
    font-size: .88rem;
}

.maniobras-tabla thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    padding: .55rem .75rem;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
}

.maniobras-tabla tbody td {
    padding: .6rem .75rem;
    border-bottom: 1px solid #f1f5f9;
}

.badge-apto   { background: #dcfce7; color: #166534; border-radius: 6px; padding: 2px 8px; font-size: .8rem; font-weight: 700; }
.badge-noApto { background: #fee2e2; color: #991b1b; border-radius: 6px; padding: 2px 8px; font-size: .8rem; font-weight: 700; }

/* ── Banner resumen ──────────────────────────────────────────────────── */
.banner-optimo { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
.banner-alerta { background: #fef9c3; border: 1px solid #fde047; color: #854d0e; }
.banner-peligro{ background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

.banner-maniobras {
    border-radius: 10px;
    padding: .9rem 1.25rem;
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-top: 1rem;
}

/* ── Widget Euskalmet (full-width) ───────────────────────────────────── */
.clima-widget-wrap {
    grid-column: 1 / -1;
}

/* ── Botón refrescar ─────────────────────────────────────────────────── */
.btn-refresh {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: #1e3a5f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: .6rem 1.25rem;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
    margin-top: 1.25rem;
}

.btn-refresh:hover { background: #2d5a8f; }

/* ── Skeleton / loading ──────────────────────────────────────────────── */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
    border-radius: 6px;
    height: 1rem;
    margin: .35rem 0;
}

@keyframes shimmer {
    0%   { background-position: -200% 0; }
    100% { background-position:  200% 0; }
}
</style>
@endpush

@section('content')
<div class="clima-page">

    {{-- ── Encabezado ─────────────────────────────────────────────────── --}}
    <h1>🌤 Condiciones Climáticas</h1>
    <p class="subtitulo">
        Pronóstico en tiempo real para <strong>Donostia – San Sebastián</strong>
        &mdash; fuente: <a href="https://www.euskalmet.euskadi.eus" target="_blank" rel="noopener">Euskalmet</a>
    </p>

    <div class="clima-grid">

        {{-- ── Widget oficial Euskalmet (predicción de mañana) ────────── --}}
        <div class="clima-card clima-widget-wrap">
            <h2>📡 Predicción meteorológica</h2>

            {{--
                euskalmet-widget: el JS de public/js/euskalmet.js llama al
                endpoint proxy GET /api/euskalmet/prediccion, que firma con
                RSA-SHA256 y devuelve el JSON de Euskalmet normalizado.
            --}}
            <div id="euskalmet-widget"
                 class="em-widget em-widget--cargando"
                 aria-live="polite"
                 aria-label="Pronóstico meteorológico Donostia">
                <div class="em-spinner-wrap">
                    <span class="em-spinner"></span>
                    <span class="em-cargando-txt">Cargando datos meteorológicos&hellip;</span>
                </div>
            </div>
        </div>

        {{-- ── Condiciones actuales (pobladas desde la API vía JS) ─────── --}}
        <div class="clima-card">
            <h2>🌊 Condiciones Actuales</h2>
            <table class="condiciones-tabla" id="tabla-condiciones">
                <tbody>
                    <tr>
                        <td>🌡 Temperatura máx.</td>
                        <td id="val-tempMax"><span class="skeleton" style="width:60px;display:inline-block"></span></td>
                    </tr>
                    <tr>
                        <td>🌡 Temperatura mín.</td>
                        <td id="val-tempMin"><span class="skeleton" style="width:60px;display:inline-block"></span></td>
                    </tr>
                    <tr>
                        <td>💨 Viento</td>
                        <td id="val-viento"><span class="skeleton" style="width:90px;display:inline-block"></span></td>
                    </tr>
                    <tr>
                        <td>🌊 Altura de olas</td>
                        <td id="val-olas"><span class="skeleton" style="width:60px;display:inline-block"></span></td>
                    </tr>
                    <tr>
                        <td>💧 Precipitación</td>
                        <td id="val-lluvia"><span class="skeleton" style="width:50px;display:inline-block"></span></td>
                    </tr>
                    <tr>
                        <td>💦 Humedad máx.</td>
                        <td id="val-humedad"><span class="skeleton" style="width:50px;display:inline-block"></span></td>
                    </tr>
                    <tr>
                        <td>☁ Estado del cielo</td>
                        <td id="val-cielo"><span class="skeleton" style="width:100px;display:inline-block"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ── Condiciones para maniobras ──────────────────────────────── --}}
        <div class="clima-card">
            <h2>⚓ Aptitud para Maniobras de Atraque</h2>

            <table class="maniobras-tabla" id="tabla-maniobras">
                <thead>
                    <tr>
                        <th>Condición</th>
                        <th>Valor actual</th>
                        <th>Límite seguro</th>
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

    </div>{{-- /.clima-grid --}}

    <button class="btn-refresh" onclick="refrescarClima()">
        🔄 Actualizar datos
    </button>

    <p style="margin-top:.75rem; color:#94a3b8; font-size:.8rem;">
        Los datos se actualizan automáticamente cada 5 minutos.
    </p>

</div>{{-- /.clima-page --}}
@endsection


{{-- ══════════════════════════════════════════════════════════════════════
     JS del widget + lógica de la página
     ══════════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script src="{{ asset('js/euskalmet.js') }}"></script>
<script>
/**
 * clima.blade.php — lógica de integración con la API de Euskalmet.
 *
 * 1. Euskalmet.init() arranca el widget de pronóstico (euskalmet.js).
 * 2. fetchCondiciones() llama al proxy /api/euskalmet/prediccion y rellena
 *    la tabla de condiciones actuales y el panel de maniobras.
 */

/* ── Constantes de límites para maniobras ────────────────────────────── */
const LIMITES = {
    olas:   2.0,   // metros
    viento: 72,    // km/h  (≈ 40 nudos)
    lluvia: 20,    // mm
};

/* ── Inicialización ──────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    // Widget de predicción (euskalmet.js)
    Euskalmet.init('euskalmet-widget');

    // Tabla de condiciones + panel de maniobras
    fetchCondiciones();
});

/* ── Petición al proxy Euskalmet ─────────────────────────────────────── */
async function fetchCondiciones() {
    try {
        const resp = await fetch('/api/euskalmet/prediccion', {
            method: 'GET',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
        });

        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

        const datos = await resp.json();

        if (datos.error) throw new Error(datos.error);

        rellenarCondiciones(datos);
        evaluarManiobras(datos);

    } catch (err) {
        console.warn('[Clima] Error al obtener datos:', err.message);
        mostrarErrorCondiciones();
    }
}

/* ── Rellena la tabla de condiciones ─────────────────────────────────── */
function rellenarCondiciones(d) {
    set('val-tempMax', d.temperatura  != null ? `${d.temperatura} °C`                            : '—');
    set('val-tempMin', d.tempMin      != null ? `${d.tempMin} °C`                                : '—');
    set('val-viento',  d.viento       != null ? `${d.viento} km/h${d.vientoDireccion ? ' · ' + d.vientoDireccion : ''}` : '—');
    set('val-olas',    d.alturaOlas   != null ? `${d.alturaOlas} m`                              : '—');
    set('val-lluvia',  d.precipitacion!= null ? `${d.precipitacion} mm`                          : '—');
    set('val-humedad', d.humedadMax   != null ? `${d.humedadMax} %`                              : '—');
    set('val-cielo',   d.estadoCielo  ?? '—');
}

/* ── Evalúa aptitud para maniobras ───────────────────────────────────── */
function evaluarManiobras(d) {
    const olas   = d.alturaOlas    ?? null;
    const viento = d.viento        ?? null;
    const lluvia = d.precipitacion ?? null;

    const aptoOlas   = olas   !== null ? olas   < LIMITES.olas   : null;
    const aptoViento = viento !== null ? viento < LIMITES.viento : null;
    const aptoLluvia = lluvia !== null ? lluvia < LIMITES.lluvia : null;

    set('m-olas',   olas   !== null ? `${olas} m`    : '—');
    set('m-viento', viento !== null ? `${viento} km/h` : '—');
    set('m-lluvia', lluvia !== null ? `${lluvia} mm`  : '—');

    badge('m-olas-badge',   aptoOlas);
    badge('m-viento-badge', aptoViento);
    badge('m-lluvia-badge', aptoLluvia);

    // Banner global
    const todas = [aptoOlas, aptoViento, aptoLluvia].filter(v => v !== null);
    const banner = document.getElementById('banner-maniobras');

    if (todas.length === 0) {
        banner.className = 'banner-maniobras banner-alerta';
        banner.textContent = '⚠ No hay datos suficientes para evaluar las condiciones.';
        return;
    }

    const todoApto = todas.every(Boolean);
    const algoMalo = todas.some(v => !v);

    if (todoApto) {
        banner.className = 'banner-maniobras banner-optimo';
        banner.textContent = '✅ Condiciones ÓPTIMAS para maniobras de atraque';
    } else if (algoMalo) {
        banner.className = 'banner-maniobras banner-peligro';
        banner.textContent = '🚫 Condiciones DESFAVORABLES — consultar con el práctico';
    }
}

/* ── Error en la carga ───────────────────────────────────────────────── */
function mostrarErrorCondiciones() {
    ['val-tempMax','val-tempMin','val-viento','val-olas',
     'val-lluvia','val-humedad','val-cielo'].forEach(id => set(id, '—'));

    const banner = document.getElementById('banner-maniobras');
    banner.className = 'banner-maniobras banner-alerta';
    banner.textContent = '⚠ No se pudieron obtener los datos meteorológicos.';
}

/* ── Refresco manual ─────────────────────────────────────────────────── */
function refrescarClima() {
    Euskalmet.actualizar('euskalmet-widget');
    fetchCondiciones();
}

/* ── Helpers ─────────────────────────────────────────────────────────── */
function set(id, texto) {
    const el = document.getElementById(id);
    if (el) el.textContent = texto;
}

function badge(id, apto) {
    const el = document.getElementById(id);
    if (!el) return;
    if (apto === null) { el.textContent = '—'; return; }
    el.innerHTML = apto
        ? '<span class="badge-apto">✅ APTO</span>'
        : '<span class="badge-noApto">🚫 NO APTO</span>';
}
</script>
@endpush