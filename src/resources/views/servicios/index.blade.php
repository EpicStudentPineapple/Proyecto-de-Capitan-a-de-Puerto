@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">Servicios Portuarios</h1>
    
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.servicios.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            + Crear Nuevo Servicio
        </a>
    @endif

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div role="alert" aria-live="polite" style="background: #fee; border: 1px solid #c00; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #a00;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('propietario.servicios.solicitar') }}" method="POST" id="servicios-form">
        @csrf
        <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d H:i:s') }}">
        <input type="hidden" name="cantidad" value="1">

        @if(auth()->user()->isPropietario())
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
                <div>
                    <label for="buque_id" style="display: block; font-weight: bold; margin-bottom: 5px;">Seleccione Buque Atracado:</label>
                    <select name="buque_id" id="buque_id" required style="padding: 10px; border-radius: 4px; border: 1px solid #cbd5e0; min-width: 250px;">
                        <option value="">-- Seleccionar buque --</option>
                        @foreach($buquesAtracados as $buque)
                            <option value="{{ $buque->id }}">{{ $buque->nombre }} (Muelle: {{ $buque->muelle->nombre ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex-grow: 1;">
                    <p style="margin: 0; color: #64748b; font-size: 0.9rem;">
                        <strong>Nota:</strong> Solo se muestran buques en estado "Atracado". 
                        Seleccione los servicios abajo y pulse el botón de solicitud.
                    </p>
                </div>
                <button type="submit" id="btn-solicitar" disabled style="padding: 12px 24px; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: not-allowed; opacity: 0.6; transition: all 0.2s;">
                    Solicitar Servicios Seleccionados
                </button>
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                        @if(auth()->user()->isPropietario())
                            <th style="padding: 12px; text-align: center; width: 50px;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                        @endif
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Código</th>
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Nombre</th>
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Tipo</th>
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Precio Base</th>
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Unidad</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold;">24h</th>
                        <th style="padding: 12px; text-align: left; font-weight: bold;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.1s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            @if(auth()->user()->isPropietario())
                                <td style="padding: 12px; text-align: center;">
                                    <input type="checkbox" name="servicio_ids[]" value="{{ $servicio->id }}" class="servicio-checkbox" style="cursor: pointer;">
                                </td>
                            @endif
                            <td style="padding: 12px; font-family: monospace; font-weight: bold;">{{ $servicio->codigo }}</td>
                            <td style="padding: 12px;">{{ $servicio->nombre }}</td>
                            <td style="padding: 12px;">
                                <span style="font-size: 0.8rem; background: #e2e8f0; padding: 2px 8px; border-radius: 12px;">
                                    {{ ucfirst(str_replace('_', ' ', $servicio->tipo_servicio)) }}
                                </span>
                            </td>
                            <td style="padding: 12px; font-weight: bold; color: #2d3748;">{{ number_format($servicio->precio_base, 2) }} €</td>
                            <td style="padding: 12px; font-size: 0.9rem; color: #718096;">{{ ucfirst(str_replace('_', ' ', $servicio->unidad_cobro)) }}</td>
                            <td style="padding: 12px; text-align: center;">
                                @if($servicio->disponible_24h)
                                    <span title="Disponible 24h" style="color: #10b981;">●</span>
                                @else
                                    <span title="Horario restringido" style="color: #cbd5e0;">○</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 10px;">
                                    <a href="{{ route('servicios.show', $servicio->id) }}" style="color: #3182ce; text-decoration: none; font-weight: bold;">Ver detalle</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.servicios.edit', $servicio->id) }}" style="color: #4a5568; text-decoration: none;">Editar</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isPropietario() ? 8 : 7 }}" style="padding: 40px; text-align: center; color: #a0aec0; font-style: italic;">
                                No hay servicios registrados en la base de datos
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div style="margin-top: 20px;">
        {{ $servicios->links() }}
    </div>
</div>

@if(auth()->user()->isPropietario())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.servicio-checkbox');
        const selectAll = document.getElementById('select-all');
        const btnSolicitar = document.getElementById('btn-solicitar');
        const buqueId = document.getElementById('buque_id');

        function updateButtonState() {
            const hasChecked = Array.from(checkboxes).some(cb => cb.checked);
            const hasBuque = buqueId.value !== '';
            
            if (hasChecked && hasBuque) {
                btnSolicitar.disabled = false;
                btnSolicitar.style.cursor = 'pointer';
                btnSolicitar.style.opacity = '1';
                btnSolicitar.style.background = '#059669';
            } else {
                btnSolicitar.disabled = true;
                btnSolicitar.style.cursor = 'not-allowed';
                btnSolicitar.style.opacity = '0.6';
                btnSolicitar.style.background = '#10b981';
            }
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateButtonState));
        buqueId.addEventListener('change', updateButtonState);

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateButtonState();
            });
        }

        document.getElementById('servicios-form').addEventListener('submit', function(e) {
            const selectedCount = document.querySelectorAll('.servicio-checkbox:checked').length;
            const buqueName = buqueId.options[buqueId.selectedIndex].text;
            
            if (!confirm(`¿Confirmar la solicitud de ${selectedCount} servicio(s) para el buque "${buqueName}"?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endif
@endsection
