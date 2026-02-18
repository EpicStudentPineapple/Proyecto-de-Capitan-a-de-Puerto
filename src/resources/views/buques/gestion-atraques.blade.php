@extends('layouts.app')

@section('title', 'Gestión de Atraques')

@section('content')
<h1>Gestión de Atraques (Sistema por Pantalanes)</h1>

<p><a href="{{ route('dashboard') }}">← Volver al Dashboard</a></p>

@push('styles')
<style>
    .buque-draggable.ui-draggable-dragging {
        transform: rotate(2deg);
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        z-index: 9999;
    }
    
    .pantalan-slot.ui-state-hover {
        background: #f0f4f8 !important;
        border-color: #007bff !important;
        transform: scale(1.01);
    }

    .pantalan-slot.muelle-valido {
        background: #e6fffa !important;
        border-color: #38b2ac !important;
    }

    .pantalan-slot.muelle-invalido {
        background: #fff5f5 !important;
        border-color: #f56565 !important;
    }

    .fondeadero-droppable.ui-state-hover {
        background: #ebf8ff !important;
        border-color: #4299e1 !important;
    }

    .muelle-container {
        border: 2px solid #cbd5e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        background: #f7fafc;
    }

    .muelle-header {
        border-bottom: 2px solid #cbd5e0;
        margin-bottom: 15px;
        padding-bottom: 10px;
    }

    .pantalanes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }

    .pantalan-slot {
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        padding: 12px;
        background: white;
        transition: all 0.2s ease;
        min-height: 120px;
    }

    .slot-ocupado {
        border-color: #feb2b2;
        background: #fff5f5;
    }

    .slot-disponible {
        border-color: #9ae6b4;
        background: #f0fff4;
        border-style: dashed;
    }
</style>
@endpush

<div style="margin: 20px 0; padding: 20px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px;">
    <strong>🚢 Manual de Operaciones:</strong>
    <ul style="margin-top: 10px;">
        <li><strong>Fondeo → Atraque:</strong> Arrastra un buque azul a un slot verde discontinuo.</li>
        <li><strong>Desatraque:</strong> Arrastra un buque rojo de su slot hacia la zona de "Buques Fondeados".</li>
        <li><strong>Validación:</strong> El sistema comprueba eslora, manga y calado contra los límites del pantalán específico.</li>
    </ul>
</div>

<div style="display: grid; grid-template-columns: 350px 1fr; gap: 30px; margin-top: 30px; align-items: start;">
    
    <!-- BUQUES FONDEADOS -->
    <div style="position: sticky; top: 20px;">
        <h2 style="font-size: 1.5rem; margin-bottom: 15px;">⚓ Buques Fondeados</h2>
        
        <div id="buques-fondeados" class="fondeadero-droppable" style="min-height: 600px; padding: 15px; border: 2px dashed #a0aec0; border-radius: 8px; background: #edf2f7;">
            @forelse($buquesFondeados as $buque)
                <div class="buque-draggable" 
                     data-buque-id="{{ $buque->id }}"
                     data-calado="{{ $buque->calado }}"
                     data-eslora="{{ $buque->eslora }}"
                     data-manga="{{ $buque->manga }}"
                     style="padding: 12px; margin-bottom: 12px; border: 2px solid #4299e1; background: white; border-radius: 6px; cursor: move; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="color: #2b6cb0; font-weight: bold;">🚢 {{ $buque->nombre }}</div>
                    <div style="font-size: 0.85rem; margin-top: 5px;">
                        <strong>IMO:</strong> {{ $buque->imo }}<br>
                        <strong>Dim:</strong> {{ $buque->eslora }}m x {{ $buque->manga }}m<br>
                        <strong>Calado:</strong> {{ $buque->calado }}m
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #718096; padding-top: 40px;">No hay buques en fondeadero</div>
            @endforelse
        </div>
    </div>

    <!-- MUELLES Y PANTALANES -->
    <div>
        <h2 style="font-size: 1.5rem; margin-bottom: 15px;">🏢 Muelles y Slots (Pantalanes)</h2>
        
        @foreach($muelles as $muelle)
            <div class="muelle-container">
                <div class="muelle-header">
                    <h3 style="margin: 0; color: #2d3748;">Muelle: {{ $muelle->nombre }} ({{ $muelle->codigo }})</h3>
                    <small style="color: #718096;">{{ ucfirst($muelle->tipo_muelle) }} | Calado máx muelle: {{ $muelle->calado_maximo }}m</small>
                </div>

                <div class="pantalanes-grid">
                    @foreach($muelle->pantalans as $pantalan)
                        <div class="pantalan-slot {{ $pantalan->buqueActual ? 'slot-ocupado' : 'slot-disponible' }}" 
                             data-pantalan-id="{{ $pantalan->id }}"
                             data-muelle-id="{{ $muelle->id }}"
                             data-calado-max="{{ $pantalan->calado_maximo }}"
                             data-eslora-max="{{ $pantalan->longitud_maxima }}"
                             data-manga-max="{{ $pantalan->manga_maxima }}">
                            
                            <div style="font-weight: bold; border-bottom: 1px solid #edf2f7; padding-bottom: 4px; margin-bottom: 8px;">
                                Slot: {{ $pantalan->codigo }}
                            </div>
                            
                            <div style="font-size: 0.75rem; color: #718096; margin-bottom: 8px;">
                                L: {{ $pantalan->longitud_maxima }}m | M: {{ $pantalan->manga_maxima ?? 'N/A' }}m | C: {{ $pantalan->calado_maximo }}m
                            </div>

                            @if($pantalan->buqueActual)
                                <div class="buque-atracado-draggable" 
                                     data-buque-id="{{ $pantalan->buqueActual->id }}"
                                     style="padding: 8px; background: white; border: 1px solid #f56565; border-radius: 4px; cursor: move; position: relative; z-index: 100;">
                                    <div style="color: #c53030; font-weight: bold; font-size: 0.9rem;">📍 {{ $pantalan->buqueActual->nombre }}</div>
                                    <small style="font-size: 0.7rem;">IMO: {{ $pantalan->buqueActual->imo }}</small>
                                </div>
                            @else
                                <div style="text-align: center; color: #a0aec0; padding: 10px 0; font-style: italic; font-size: 0.85rem;">
                                    Disponible
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
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
        stop: function() { $(this).css('opacity', '1'); }
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
    
    // Inicializar Pantalanes (Slots) como Droppables
    $('.pantalan-slot').droppable({
        accept: '.buque-draggable',
        tolerance: 'pointer',
        
        over: function(event, ui) {
            const $slot = $(this);
            const $buque = ui.draggable;
            
            if ($slot.hasClass('slot-ocupado')) {
                $slot.addClass('muelle-invalido');
                return;
            }

            const b_eslora = parseFloat($buque.data('eslora'));
            const b_manga = parseFloat($buque.data('manga'));
            const b_calado = parseFloat($buque.data('calado'));

            const s_eslora = parseFloat($slot.data('eslora-max'));
            const s_manga = parseFloat($slot.data('manga-max') || 999);
            const s_calado = parseFloat($slot.data('calado-max'));

            const esValido = b_eslora <= s_eslora && b_manga <= s_manga && b_calado <= s_calado;
            
            $slot.addClass(esValido ? 'muelle-valido' : 'muelle-invalido');
        },
        
        out: function() {
            $(this).removeClass('muelle-valido muelle-invalido');
        },
        
        drop: function(event, ui) {
            const $slot = $(this);
            const $buque = ui.draggable;
            
            $slot.removeClass('muelle-valido muelle-invalido');

            if ($slot.hasClass('slot-ocupado')) {
                alert('❌ Este slot ya está ocupado');
                return;
            }

            const b_eslora = parseFloat($buque.data('eslora'));
            const b_manga = parseFloat($buque.data('manga'));
            const b_calado = parseFloat($buque.data('calado'));
            const s_eslora = parseFloat($slot.data('eslora-max'));
            const s_manga = parseFloat($slot.data('manga-max') || 999);
            const s_calado = parseFloat($slot.data('calado-max'));

            if (b_eslora > s_eslora || b_manga > s_manga || b_calado > s_calado) {
                alert('❌ El buque excede las dimensiones permitidas para este pantalán');
                return;
            }
            
            const buqueId = $buque.data('buque-id');
            const muelleId = $slot.data('muelle-id');
            const pantalanId = $slot.data('pantalan-id');
            const nombreBuque = $buque.find('div').first().text();

            if (!confirm('¿Atracar ' + nombreBuque + ' en este slot?')) return;
            
            console.log('📡 Entregando buque ' + buqueId + ' a pantalán ' + pantalanId);
            $slot.css('opacity', '0.5');
            
            $.ajax({
                url: '/admin/buques/' + buqueId + '/asignar-muelle', // Reuso la ruta pero mando pantalan_id
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    muelle_id: muelleId,
                    pantalan_id: pantalanId
                },
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
            const nombreBuque = $buque.find('div').first().text();

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