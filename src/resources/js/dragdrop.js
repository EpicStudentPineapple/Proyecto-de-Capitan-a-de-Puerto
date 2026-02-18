/**
 * dragdrop.js
 * Módulo de Drag & Drop para la Gestión de Atraques
 * Usa jQuery UI: draggable / droppable
 */

const GestionAtraques = (() => {

  // ── Estado ──────────────────────────────────────────────────────────────────
  let draggedBuque = null;

  // ── Helpers ─────────────────────────────────────────────────────────────────
  function showToast(msg, tipo = 'info') {
    const colors = {
      success: '#2d6a4f',
      error:   '#c0392b',
      info:    '#1a3a5c',
      warn:    '#b35900',
    };
    const toast = $(`
      <div class="ga-toast" style="
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        padding: 12px 20px; border-radius: 4px; color: #fff; font-size: 14px;
        background: ${colors[tipo] ?? colors.info};
        box-shadow: 0 4px 16px rgba(0,0,0,.18);
        opacity: 0; transition: opacity .25s ease;
        max-width: 340px; line-height: 1.45;
      ">${msg}</div>
    `);
    $('body').append(toast);
    setTimeout(() => toast.css('opacity', 1), 10);
    setTimeout(() => {
      toast.css('opacity', 0);
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  }

  function confirmar(msg) {
    return new Promise(resolve => {
      // Modal minimalista
      const overlay = $(`
        <div id="ga-confirm-overlay" style="
          position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:10000;
          display:flex;align-items:center;justify-content:center;">
          <div style="
            background:#fff;border-radius:6px;padding:28px 32px;max-width:400px;
            box-shadow:0 8px 32px rgba(0,0,0,.22);font-family:inherit;">
            <p style="margin:0 0 24px;font-size:15px;color:#1a1a2e;line-height:1.5">${msg}</p>
            <div style="display:flex;gap:12px;justify-content:flex-end">
              <button id="ga-cancel"
                style="padding:8px 20px;border:1px solid #bbb;border-radius:4px;background:#fff;cursor:pointer;font-size:14px">
                Cancelar
              </button>
              <button id="ga-confirm"
                style="padding:8px 20px;border:none;border-radius:4px;background:#1a3a5c;color:#fff;cursor:pointer;font-size:14px">
                Confirmar
              </button>
            </div>
          </div>
        </div>
      `);
      $('body').append(overlay);
      $('#ga-confirm').on('click', () => { overlay.remove(); resolve(true); });
      $('#ga-cancel').on('click',  () => { overlay.remove(); resolve(false); });
    });
  }

  function setLoading(muelleEl, on) {
    if (on) {
      muelleEl.addClass('ga-loading');
      muelleEl.find('.ga-muelle-status').html(
        '<span style="color:#888;font-size:13px">Procesando…</span>'
      );
    } else {
      muelleEl.removeClass('ga-loading');
    }
  }

  // ── Validaciones locales ─────────────────────────────────────────────────────
  function validarCompatibilidad(buqueEl, muelleEl) {
    const calado   = parseFloat(buqueEl.data('calado'));
    const eslora   = parseFloat(buqueEl.data('eslora'));
    const calaMax  = parseFloat(muelleEl.data('calado-max'));
    const longitud = parseFloat(muelleEl.data('longitud'));
    const ocupado  = muelleEl.data('ocupado') == 1;

    if (ocupado) {
      return { ok: false, msg: 'El muelle ya está ocupado.' };
    }
    if (calado > calaMax) {
      return { ok: false, msg: `Calado del buque (${calado} m) supera el máximo del muelle (${calaMax} m).` };
    }
    if (eslora > longitud) {
      return { ok: false, msg: `Eslora del buque (${eslora} m) supera la longitud del muelle (${longitud} m).` };
    }
    return { ok: true };
  }

  // ── AJAX ─────────────────────────────────────────────────────────────────────
  async function asignarMuelle(buqueId, muelleId) {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const res = await fetch(`/buques/${buqueId}/asignar-muelle`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ muelle_id: muelleId }),
    });
    if (!res.ok) {
      const data = await res.json().catch(() => ({}));
      throw new Error(data.message ?? `Error ${res.status}`);
    }
    return res.json();
  }

  // ── Inicialización ───────────────────────────────────────────────────────────
  function init() {
    // Buques arrastrables
    $('.buque-draggable').draggable({
      revert:    'invalid',
      helper:    'clone',
      cursor:    'grabbing',
      opacity:   0.82,
      zIndex:    1000,
      start(event, ui) {
        draggedBuque = $(this);
        $(this).addClass('ga-dragging');
        // Estilo del helper clonado
        ui.helper.css({
          'box-shadow': '0 8px 24px rgba(0,0,0,.22)',
          'transform':  'rotate(1.5deg)',
          'pointer-events': 'none',
        });
      },
      stop() {
        draggedBuque = null;
        $('.buque-draggable').removeClass('ga-dragging');
        $('.muelle-droppable').removeClass('ga-hover-valid ga-hover-invalid');
      },
    });

    // Retroalimentación visual mientras se arrastra encima de muelles
    $('.buque-draggable').on('drag', function(event) {
      const $b = $(this);
      $('.muelle-droppable').each(function() {
        const valid = validarCompatibilidad($b, $(this));
        // Highlight dinámico no implementado aquí
        // (requeriría detección de posición); se hace en over/out del droppable
      });
    });

    // Muelles como zonas de destino
    $('.muelle-droppable').droppable({
      accept: '.buque-draggable',
      tolerance: 'pointer',

      over(event, ui) {
        const chk = validarCompatibilidad(ui.draggable, $(this));
        $(this).addClass(chk.ok ? 'ga-hover-valid' : 'ga-hover-invalid');
      },
      out() {
        $(this).removeClass('ga-hover-valid ga-hover-invalid');
      },

      async drop(event, ui) {
        const $muelle = $(this);
        const $buque  = ui.draggable;
        $muelle.removeClass('ga-hover-valid ga-hover-invalid');

        const chk = validarCompatibilidad($buque, $muelle);
        if (!chk.ok) {
          showToast(chk.msg, 'error');
          return;
        }

        const nombre  = $buque.find('strong').first().text();
        const mNombre = $muelle.find('.ga-muelle-nombre').text();
        const ok = await confirmar(
          `¿Atracar <strong>${nombre}</strong> en el muelle <strong>${mNombre}</strong>?`
        );
        if (!ok) return;

        const buqueId  = $buque.data('buque-id');
        const muelleId = $muelle.data('muelle-id');

        setLoading($muelle, true);
        try {
          const data = await asignarMuelle(buqueId, muelleId);
          showToast(data.message ?? 'Buque atracado correctamente.', 'success');
          // Pequeña pausa antes de recargar para que el usuario vea el toast
          setTimeout(() => location.reload(), 1200);
        } catch (err) {
          setLoading($muelle, false);
          showToast(err.message, 'error');
        }
      },
    });
  }

  // ── API pública ──────────────────────────────────────────────────────────────
  return { init };

})();

// Lanzar cuando el DOM esté listo
$(document).ready(() => GestionAtraques.init());