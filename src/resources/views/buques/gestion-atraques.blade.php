@extends('layouts.app')

@section('title', 'Gestión de Atraques')


@section('content')
<div class="buques-page">
    <h1>Gestión de Atraques</h1>
    <a href="{{ route('dashboard') }}" class="back-link">Volver al Dashboard</a>

    <div class="ops-manual" role="note" aria-label="Manual de operaciones">
        <strong>Manual de Operaciones:</strong>
        <ul>
            <li><strong>Fondeo → Atraque:</strong> Arrastra un buque azul a un slot verde discontinuo.</li>
            <li><strong>Desatraque:</strong> Arrastra un buque rojo de su slot hacia la zona de «Buques Fondeados».</li>
            <li><strong>Validación:</strong> El sistema comprueba eslora, manga y calado contra los límites del pantalán específico.</li>
        </ul>
    </div>

    <div class="atraques-layout">

        {{-- BUQUES FONDEADOS --}}
        <div class="fondeadero-section">
            <h2>⚓ Buques Fondeados</h2>
            <div id="buques-fondeados"
                 class="fondeadero-droppable"
                 role="region"
                 aria-label="Zona de fondeadero — zona de destino para desatracar buques">
                @forelse($buquesFondeados as $buque)
                    <div class="buque-draggable"
                         data-buque-id="{{ $buque->id }}"
                         data-calado="{{ $buque->calado }}"
                         data-eslora="{{ $buque->eslora }}"
                         data-manga="{{ $buque->manga }}"
                         tabindex="0"
                         role="listitem"
                         aria-label="Buque {{ $buque->nombre }}, IMO {{ $buque->imo }}, eslora {{ $buque->eslora }}m, manga {{ $buque->manga }}m, calado {{ $buque->calado }}m">
                        <div class="buque-nombre">🚢 {{ $buque->nombre }}</div>
                        <div class="buque-info">
                            <span><strong>IMO:</strong> {{ $buque->imo }}</span>
                            <span><strong>Dim:</strong> {{ $buque->eslora }}m × {{ $buque->manga }}m</span>
                            <span><strong>Calado:</strong> {{ $buque->calado }}m</span>
                        </div>
                    </div>
                @empty
                    <div class="fondeadero-empty" aria-live="polite">No hay buques en fondeadero</div>
                @endforelse
            </div>
        </div>

        {{-- MUELLES Y PANTALANES --}}
        <div>
            <h2>🏢 Muelles y Slots (Pantalanes)</h2>

            @foreach($muelles as $muelle)
                <div class="muelle-container">
                    <div class="muelle-header">
                        <h3>Muelle: {{ $muelle->nombre }} ({{ $muelle->codigo }})</h3>
                        <span class="muelle-meta">
                            {{ ucfirst($muelle->tipo_muelle) }} | Calado máx. muelle: {{ $muelle->calado_maximo }}m
                        </span>
                    </div>

                    <div class="pantalanes-grid">
                        @foreach($muelle->pantalans as $pantalan)
                            <div class="pantalan-slot {{ $pantalan->buqueActual ? 'slot-ocupado' : 'slot-disponible' }}"
                                 data-pantalan-id="{{ $pantalan->id }}"
                                 data-muelle-id="{{ $muelle->id }}"
                                 data-calado-max="{{ $pantalan->calado_maximo }}"
                                 data-eslora-max="{{ $pantalan->longitud_maxima }}"
                                 data-manga-max="{{ $pantalan->manga_maxima }}"
                                 role="region"
                                 aria-label="Slot {{ $pantalan->codigo }}, {{ $pantalan->buqueActual ? 'ocupado por ' . $pantalan->buqueActual->nombre : 'disponible' }}">

                                <div class="slot-codigo">Slot: {{ $pantalan->codigo }}</div>
                                <div class="slot-dims">
                                    L: {{ $pantalan->longitud_maxima }}m |
                                    M: {{ $pantalan->manga_maxima ?? 'N/A' }}m |
                                    C: {{ $pantalan->calado_maximo }}m
                                </div>

                                @if($pantalan->buqueActual)
                                    <div class="buque-atracado-draggable"
                                         data-buque-id="{{ $pantalan->buqueActual->id }}"
                                         tabindex="0"
                                         role="listitem"
                                         aria-label="Buque atracado: {{ $pantalan->buqueActual->nombre }}">
                                        <div class="buque-atracado-nombre">📍 {{ $pantalan->buqueActual->nombre }}</div>
                                        <div class="buque-atracado-imo">IMO: {{ $pantalan->buqueActual->imo }}</div>
                                    </div>
                                @else
                                    <div class="slot-disponible-label" aria-hidden="true">Disponible</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if (!$.ui) {
        console.error('❌ ERROR: jQuery UI no está cargado.');
        return;
    }

    // Inicializar Draggables (Fondeados)
    $('.buque-draggable').draggable({
        revert: 'invalid',
        helper: 'clone',
        cursor: 'move',
        opacity: 0.8,
        zIndex: 10000,
        appendTo: 'body',
        start: function() { $(this).css('opacity', '0.5'); },
        stop:  function() { $(this).css('opacity', '1'); }
    });

    // Inicializar Draggables (Atracados)
    $('.buque-atracado-draggable').draggable({
        revert: 'invalid',
        helper: 'clone',
        cursor: 'move',
        opacity: 0.8,
        zIndex: 10000,
        appendTo: 'body'
    });

    // Pantalanes como Droppables
    $('.pantalan-slot').droppable({
        accept: '.buque-draggable',
        tolerance: 'pointer',

        over: function(event, ui) {
            const $slot  = $(this);
            const $buque = ui.draggable;

            if ($slot.hasClass('slot-ocupado')) {
                $slot.addClass('muelle-invalido');
                return;
            }

            const b_eslora = parseFloat($buque.data('eslora'));
            const b_manga  = parseFloat($buque.data('manga'));
            const b_calado = parseFloat($buque.data('calado'));
            const s_eslora = parseFloat($slot.data('eslora-max'));
            const s_manga  = parseFloat($slot.data('manga-max') || 999);
            const s_calado = parseFloat($slot.data('calado-max'));

            const esValido = b_eslora <= s_eslora && b_manga <= s_manga && b_calado <= s_calado;
            $slot.addClass(esValido ? 'muelle-valido' : 'muelle-invalido');
        },

        out: function() {
            $(this).removeClass('muelle-valido muelle-invalido');
        },

        drop: function(event, ui) {
            const $slot  = $(this);
            const $buque = ui.draggable;

            $slot.removeClass('muelle-valido muelle-invalido');

            if ($slot.hasClass('slot-ocupado')) {
                alert('❌ Este slot ya está ocupado');
                return;
            }

            const b_eslora = parseFloat($buque.data('eslora'));
            const b_manga  = parseFloat($buque.data('manga'));
            const b_calado = parseFloat($buque.data('calado'));
            const s_eslora = parseFloat($slot.data('eslora-max'));
            const s_manga  = parseFloat($slot.data('manga-max') || 999);
            const s_calado = parseFloat($slot.data('calado-max'));

            if (b_eslora > s_eslora || b_manga > s_manga || b_calado > s_calado) {
                alert('❌ El buque excede las dimensiones permitidas para este pantalán');
                return;
            }

            const buqueId    = $buque.data('buque-id');
            const muelleId   = $slot.data('muelle-id');
            const pantalanId = $slot.data('pantalan-id');
            const nombreBuque = $buque.find('.buque-nombre').text();

            if (!confirm('¿Atracar ' + nombreBuque + ' en este slot?')) return;

            $slot.css('opacity', '0.5');

            $.ajax({
                url: '/admin/buques/' + buqueId + '/asignar-muelle',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { muelle_id: muelleId, pantalan_id: pantalanId },
                success: function(response) {
                    alert('✅ ' + response.message);
                    location.reload();
                },
                error: function(xhr) {
                    $slot.css('opacity', '1');
                    alert('❌ ERROR: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                }
            });
        }
    });

    // Zona de fondeo Droppable (Desatracar)
    $('.fondeadero-droppable').droppable({
        accept: '.buque-atracado-draggable',
        tolerance: 'pointer',
        hoverClass: 'ui-state-hover',
        drop: function(event, ui) {
            const $buque = ui.draggable;
            const buqueId = $buque.data('buque-id');
            const nombreBuque = $buque.find('.buque-atracado-nombre').text();

            if (!confirm('¿Mover ' + nombreBuque + ' de vuelta al fondeadero?')) return;

            $.ajax({
                url: '/admin/buques/' + buqueId + '/desatracar',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    alert('✅ ' + response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('❌ ERROR: ' + (xhr.responseJSON?.message || 'No se pudo desatracar'));
                }
            });
        }
    });
});
</script>
@endpush