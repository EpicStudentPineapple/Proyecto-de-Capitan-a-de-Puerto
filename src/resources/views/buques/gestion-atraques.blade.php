@extends('layout.app')

@section('title', 'Gestión de Atraques')

@section('content')
<h1>Gestión de Atraques</h1>

<p><a href="{{ route('dashboard') }}">← Volver al Dashboard</a></p>

<div style="margin: 20px 0; padding: 20px; background: #fff3cd; border: 1px solid #ffc107;">
    <strong>Instrucciones:</strong>
    <ul>
        <li>Arrastra un buque fondeado hacia un muelle disponible</li>
        <li>El sistema validará automáticamente: calado, eslora y disponibilidad</li>
        <li>Si las condiciones son correctas, el buque se atracará</li>
    </ul>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
    
    <div>
        <h2>⚓ Buques Fondeados (Esperando Muelle)</h2>
        
        <div id="buques-fondeados" style="min-height: 400px; padding: 10px; border: 2px dashed #ccc;">
            @forelse($buquesFondeados as $buque)
                <div class="buque-draggable" 
                     data-buque-id="{{ $buque->id }}"
                     data-calado="{{ $buque->calado }}"
                     data-eslora="{{ $buque->eslora }}"
                     style="padding: 15px; margin: 10px 0; border: 2px solid #007bff; background: #e3f2fd; cursor: move;">
                    <strong>🚢 {{ $buque->nombre }}</strong><br>
                    <small>IMO: {{ $buque->imo }}</small><br>
                    <small>Tipo: {{ ucfirst(str_replace('_', ' ', $buque->tipo_buque)) }}</small><br>
                    <small>Eslora: {{ $buque->eslora }}m | Calado: {{ $buque->calado }}m</small><br>
                    <small>Propietario: {{ $buque->propietario->name }}</small>
                </div>
            @empty
                <p style="text-align: center; color: #999;">No hay buques fondeados</p>
            @endforelse
        </div>
    </div>
    <div>
        <h2>Muelles Disponibles</h2>
        
        <div style="min-height: 400px;">
            @forelse($muelles as $muelle)
                <div class="muelle-droppable" 
                     data-muelle-id="{{ $muelle->id }}"
                     data-calado-max="{{ $muelle->calado_maximo }}"
                     data-longitud="{{ $muelle->longitud }}"
                     style="padding: 15px; margin: 10px 0; border: 2px solid {{ $muelle->estaOcupado() ? '#dc3545' : '#28a745' }}; 
                            background: {{ $muelle->estaOcupado() ? '#f8d7da' : '#d4edda' }};">
                    
                    <strong>{{ $muelle->codigo }} - {{ $muelle->nombre }}</strong><br>
                    <small>Tipo: {{ ucfirst(str_replace('_', ' ', $muelle->tipo_muelle)) }}</small><br>
                    <small>Long: {{ $muelle->longitud }}m | Calado máx: {{ $muelle->calado_maximo }}m</small><br>
                    <small>Capacidad: {{ number_format($muelle->capacidad_toneladas) }} ton</small><br>
                    
                    @if($muelle->buqueActual)
                        <div style="margin-top: 10px; padding: 10px; background: white; border: 1px solid #dc3545;">
                            <strong>🔴 OCUPADO:</strong><br>
                            {{ $muelle->buqueActual->nombre }}<br>
                            <small>Salida: {{ $muelle->buqueActual->fecha_salida_prevista->format('d/m H:i') }}</small>
                        </div>
                    @else
                        <div style="margin-top: 10px; padding: 5px; background: white; border: 1px solid #28a745;">
                            <strong>🟢 DISPONIBLE</strong>
                        </div>
                    @endif
                </div>
            @empty
                <p style="text-align: center; color: #999;">No hay muelles disponibles</p>
            @endforelse
        </div>
    </div>
</div>

<div style="margin-top: 30px;">
    <h3>Leyenda:</h3>
    <ul>
        <li>🟢 <strong>Verde:</strong> Muelle disponible</li>
        <li>🔴 <strong>Rojo:</strong> Muelle ocupado</li>
        <li>🚢 <strong>Azul:</strong> Buque fondeado (arrastrable)</li>
    </ul>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Hacer buques arrastrables
    $(".buque-draggable").draggable({
        revert: "invalid",
        helper: "clone",
        cursor: "move",
        start: function(event, ui) {
            $(this).css('opacity', '0.5');
        },
        stop: function(event, ui) {
            $(this).css('opacity', '1');
        }
    });
    
    // Hacer muelles droppables
    $(".muelle-droppable").droppable({
        accept: ".buque-draggable",
        hoverClass: "ui-state-hover",
        drop: function(event, ui) {
            const buque = ui.draggable;
            const muelle = $(this);
            
            const buqueId = buque.data('buque-id');
            const muelleId = muelle.data('muelle-id');
            
            const buqueCalado = parseFloat(buque.data('calado'));
            const buqueEslora = parseFloat(buque.data('eslora'));
            const muelleCaladoMax = parseFloat(muelle.data('calado-max'));
            const muelleLongitud = parseFloat(muelle.data('longitud'));
            
            // Validaciones
            if (buqueCalado > muelleCaladoMax) {
                alert('❌ ERROR: El calado del buque (' + buqueCalado + 'm) supera el calado máximo del muelle (' + muelleCaladoMax + 'm)');
                return false;
            }
            
            if (buqueEslora > muelleLongitud) {
                alert('❌ ERROR: La eslora del buque (' + buqueEslora + 'm) supera la longitud del muelle (' + muelleLongitud + 'm)');
                return false;
            }
            
            // Verificar que el muelle esté disponible
            if (muelle.find('.muelle-ocupado').length > 0) {
                alert('❌ ERROR: El muelle está ocupado');
                return false;
            }
            
            // Confirmar asignación
            if (confirm('¿Asignar buque a este muelle?')) {
                asignarBuqueAMuelle(buqueId, muelleId);
            }
        }
    });
    
    // Función AJAX para asignar buque
    function asignarBuqueAMuelle(buqueId, muelleId) {
        $.ajax({
            url: '/buques/' + buqueId + '/asignar-muelle',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                muelle_id: muelleId,
                fecha_salida: null // Nota para Piña: Añadir un input para esto
            },
            success: function(response) {
                alert('✅ ' + response.message);
                location.reload(); // Recargar página para ver cambios
            },
            error: function(xhr) {
                const error = xhr.responseJSON;
                alert('❌ ERROR: ' + (error.message || 'No se pudo asignar el buque'));
            }
        });
    }
});
</script>
@endpush