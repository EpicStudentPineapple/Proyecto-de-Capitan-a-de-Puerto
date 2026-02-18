/**
 * public/js/euskalmet.js
 * Módulo de integración con la API de Euskalmet a través del proxy Laravel.
 *
 * Flujo:
 *  1. El JS realiza un fetch al endpoint proxy /api/euskalmet/prediccion
 *     (definido en routes/api.php).
 *  2. El controlador Laravel firma la petición con RSA-SHA256 usando la
 *     clave privada (guardada en .env / servidor, nunca expuesta al cliente)
 *     y retorna los datos normalizados en JSON.
 *  3. Este módulo renderiza el widget y lo refresca cada 5 minutos.
 *
 * ──────────────────────────────────────────────────────────────────────────
 * Uso:
 *   Euskalmet.init('euskalmet-widget');   // inicializa y lanza el ciclo
 *   Euskalmet.destroy();                  // detiene el ciclo de refresco
 * ──────────────────────────────────────────────────────────────────────────
 */

window.Euskalmet = (() => {

    /* ── Configuración ────────────────────────────────────────────────── */
    const CONFIG = {
        /**
         * Endpoint proxy en Laravel (routes/api.php).
         * Este endpoint firma la petición y devuelve los datos en JSON.
         */
        proxyEndpoint: '/api/euskalmet/prediccion',

        /** Intervalo de refresco: 5 minutos */
        intervalo: 5 * 60 * 1000,
    };

    /* ── Estado interno ───────────────────────────────────────────────── */
    let timerHandle      = null;
    let ultimaActualizacion = null;

    /* ── Utilidades ───────────────────────────────────────────────────── */
    function horaLocal(fecha) {
        if (!fecha) return '—';
        return new Date(fecha).toLocaleTimeString('es-ES', {
            hour:   '2-digit',
            minute: '2-digit',
        });
    }

    /**
     * Lee el token CSRF del meta-tag de Laravel.
     * Necesario para peticiones POST/PATCH; aquí se incluye por compatibilidad
     * aunque el proxy usa GET.
     */
    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    }

    /* ── Petición al proxy ────────────────────────────────────────────── */
    async function fetchDesdeProxy() {
        const resp = await fetch(CONFIG.proxyEndpoint, {
            method: 'GET',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
        });

        if (!resp.ok) {
            const texto = await resp.text().catch(() => '');
            throw new Error(`El proxy respondió ${resp.status}: ${texto.slice(0, 120)}`);
        }

        return resp.json();
    }

    /* ── Normalización de datos ───────────────────────────────────────── */
    /**
     * Acepta tanto la respuesta ya normalizada del proxy propio
     * como el esquema crudo de la API de Euskalmet.
     *
     * La API Open Data Euskadi devuelve un objeto con una lista "forecast"
     * o "dias"; siempre interesa el día de índice 1 (mañana).
     */
    function normalizar(raw) {
        if (!raw) throw new Error('Respuesta vacía del proxy');

        /* Si el proxy ya normaliza el objeto, respetamos esa estructura */
        if (raw.temperatura !== undefined || raw.tempMax !== undefined) {
            return {
                fecha:          raw.fecha          ?? null,
                tempMax:        raw.temperatura    ?? raw.tempMax   ?? null,
                tempMin:        raw.tempMin         ?? null,
                precipitacion:  raw.precipitacion   ?? null,
                viento:         raw.viento          ?? null,
                vientoDireccion:raw.vientoDireccion ?? null,
                estadoCielo:    raw.estadoCielo     ?? raw.descripcion ?? null,
                humedadMax:     raw.humedadMax      ?? null,
                alturaOlas:     raw.alturaOlas      ?? null,
                actualizadoEn:  raw.actualizadoEn   ?? raw.fechaGeneracion ?? null,
            };
        }

        /* Esquema crudo: intentamos resolver el día "mañana" (índice 1) */
        const dias = raw?.forecast ?? raw?.dias ?? raw?.prediccion ?? [];
        const dia  = Array.isArray(dias) && dias.length > 1 ? dias[1] : (dias[0] ?? raw);

        return {
            fecha:          dia?.fecha                         ?? raw?.fecha          ?? null,
            tempMax:        dia?.temperatura?.maxima           ?? dia?.tMax           ?? null,
            tempMin:        dia?.temperatura?.minima           ?? dia?.tMin           ?? null,
            precipitacion:  dia?.precipitacion?.valor          ?? dia?.lluvia         ?? null,
            viento:         dia?.viento?.velocidad             ?? dia?.vientoKmh      ?? null,
            vientoDireccion:dia?.viento?.direccion             ?? null,
            estadoCielo:    dia?.estadoCielo?.descripcion      ?? dia?.descripcion    ?? null,
            humedadMax:     dia?.humedad?.maxima               ?? null,
            alturaOlas:     dia?.oleaje?.altura                ?? null,
            actualizadoEn:  raw?.actualizadoEn                ?? raw?.fechaGeneracion ?? null,
        };
    }

    /* ── Icono meteorológico (solo los incluidos en el widget) ────────── */
    function iconoCielo(desc) {
        if (!desc) return '';
        const d = desc.toLowerCase();
        if (d.includes('despejado') || d.includes('soleado')) return '☀';
        if (d.includes('nube') || d.includes('nublado'))      return '☁';
        if (d.includes('lluvia') || d.includes('lluvioso'))   return '🌧';
        if (d.includes('tormenta'))                           return '⛈';
        if (d.includes('nieve'))                              return '❄';
        if (d.includes('niebla'))                             return '🌫';
        return '';
    }

    /* ── Renderizado ──────────────────────────────────────────────────── */
    function renderCargando(el) {
        el.className = 'em-widget em-widget--cargando';
        el.innerHTML = `
            <div class="em-spinner-wrap">
                <span class="em-spinner"></span>
                <span class="em-cargando-txt">Cargando datos meteorológicos&hellip;</span>
            </div>
        `;
    }

    function renderError(el, mensaje) {
        el.className = 'em-widget em-widget--error';
        el.innerHTML = `
            <div class="em-cabecera">
                <span class="em-titulo">El tiempo — Irun</span>
            </div>
            <p class="em-error-msg">${mensaje}</p>
            <div class="em-pie">Euskalmet &middot; sin datos disponibles</div>
        `;
    }

    function renderDatos(el, datos) {
        ultimaActualizacion = new Date();
        const d      = normalizar(datos);
        const icono  = iconoCielo(d.estadoCielo);

        /* Temperatura */
        let tempHtml;
        if (d.tempMax !== null) {
            const max = Math.round(d.tempMax);
            const min = d.tempMin !== null
                ? `<span class="em-temp-min">${Math.round(d.tempMin)}&deg;</span>`
                : '';
            tempHtml = `<span class="em-temp">${max}&deg;</span>${min}`;
        } else {
            tempHtml = `<span class="em-nd">—</span>`;
        }

        /* Detalles adicionales */
        const filas = [
            d.estadoCielo
                ? `<span>${icono ? icono + ' ' : ''}${d.estadoCielo}</span>`
                : null,
            d.viento !== null
                ? `<span>Viento: ${d.viento}&nbsp;km/h${d.vientoDireccion ? ' ' + d.vientoDireccion : ''}</span>`
                : null,
            d.precipitacion !== null
                ? `<span>Precipitación: ${d.precipitacion}&nbsp;mm</span>`
                : null,
            d.humedadMax !== null
                ? `<span>Humedad máx.: ${d.humedadMax}&nbsp;%</span>`
                : null,
            d.alturaOlas !== null
                ? `<span>Olas: ${d.alturaOlas}&nbsp;m</span>`
                : null,
        ].filter(Boolean).join('');

        el.className = 'em-widget';
        el.innerHTML = `
            <div class="em-cabecera">
                <span class="em-titulo">Mañana &mdash; Irun</span>
                <span class="em-hora">Actualizado: ${horaLocal(ultimaActualizacion)}</span>
            </div>
            <div class="em-cuerpo">
                <div class="em-temp-wrap">${tempHtml}</div>
                <div class="em-detalles">
                    ${filas || '<span class="em-nd">Sin datos detallados</span>'}
                </div>
            </div>
            <div class="em-pie">Fuente: Euskalmet &middot; Open Data Euskadi</div>
        `;
    }

    /* ── Ciclo de actualización ───────────────────────────────────────── */
    async function actualizar(contenedorId) {
        const el = document.getElementById(contenedorId);
        if (!el) {
            console.warn(`[Euskalmet] No existe el elemento #${contenedorId}`);
            return;
        }

        try {
            const datos = await fetchDesdeProxy();
            renderDatos(el, datos);
        } catch (err) {
            console.warn('[Euskalmet] Error al obtener datos:', err.message);
            renderError(el, 'No se pudieron obtener los datos meteorológicos. Reintentando en 5 min.');
        }
    }

    /* ── API pública ──────────────────────────────────────────────────── */
    function init(contenedorId = 'euskalmet-widget') {
        const el = document.getElementById(contenedorId);
        if (el) renderCargando(el);

        actualizar(contenedorId);

        if (timerHandle) clearInterval(timerHandle);
        timerHandle = setInterval(() => actualizar(contenedorId), CONFIG.intervalo);
    }

    function destroy() {
        if (timerHandle) clearInterval(timerHandle);
        timerHandle = null;
    }

    return { init, destroy, actualizar };

})();