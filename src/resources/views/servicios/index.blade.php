@extends('layouts.app')

@section('content')

<div class="servicios-page">
    <div class="servicios-header">
        <h1>Servicios Portuarios</h1>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">
                + Crear Nuevo Servicio
            </a>
        @endif
    </div>

    @if(session('success'))
        <div role="alert" aria-live="polite" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div role="alert" aria-live="polite" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('propietario.servicios.solicitar') }}" method="POST" id="servicios-form">
        @csrf
        <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d H:i:s') }}">
        <input type="hidden" name="cantidad" value="1">

        @if(auth()->user()->isPropietario())
            <div class="solicitud-panel">
                <div class="solicitud-select-group">
                    <label for="buque_id" class="form-label">Seleccione Buque Atracado:</label>
                    <select name="buque_id" id="buque_id" required class="form-control" style="min-width: 250px;">
                        <option value="">-- Seleccionar buque --</option>
                        @foreach($buquesAtracados as $buque)
                            <option value="{{ $buque->id }}">{{ $buque->nombre }} (Muelle: {{ $buque->muelle->nombre ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <p class="solicitud-hint">
                    <strong>Nota:</strong> Solo se muestran buques en estado «Atracado».
                    Seleccione los servicios abajo y pulse el botón de solicitud.
                </p>
                <button type="submit" id="btn-solicitar" disabled
                        class="btn btn-success"
                        aria-disabled="true">
                    Solicitar Servicios Seleccionados
                </button>
            </div>
        @endif

        <div class="table-wrapper">
            <table class="table" aria-label="Catálogo de servicios portuarios">
                <thead>
                    <tr>
                        @if(auth()->user()->isPropietario())
                            <th scope="col" style="width: 50px; text-align: center;">
                                <input type="checkbox" id="select-all"
                                       aria-label="Seleccionar todos los servicios">
                            </th>
                        @endif
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Precio Base</th>
                        <th scope="col">Unidad</th>
                        <th scope="col" style="text-align: center;">24h</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr>
                            @if(auth()->user()->isPropietario())
                                <td style="text-align: center;">
                                    <input type="checkbox"
                                           name="servicio_ids[]"
                                           value="{{ $servicio->id }}"
                                           class="servicio-checkbox"
                                           aria-label="Seleccionar {{ $servicio->nombre }}">
                                </td>
                            @endif
                            <td><code>{{ $servicio->codigo }}</code></td>
                            <td>{{ $servicio->nombre }}</td>
                            <td>
                                <span class="badge-tipo">
                                    {{ ucfirst(str_replace('_', ' ', $servicio->tipo_servicio)) }}
                                </span>
                            </td>
                            <td class="font-bold">{{ number_format($servicio->precio_base, 2) }} €</td>
                            <td class="text-muted text-sm">{{ ucfirst(str_replace('_', ' ', $servicio->unidad_cobro)) }}</td>
                            <td style="text-align: center;">
                                @if($servicio->disponible_24h)
                                    <span class="disponible-24h" title="Disponible 24h" aria-label="Disponible las 24 horas">●</span>
                                @else
                                    <span class="no-disponible-24h" title="Horario restringido" aria-label="Horario restringido">○</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('servicios.show', $servicio->id) }}" class="btn btn-accent btn-sm">Ver detalle</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="btn btn-outline btn-sm">Editar</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isPropietario() ? 8 : 7 }}"
                                class="text-center text-muted"
                                style="padding: 2.5rem; font-style: italic;">
                                No hay servicios registrados en la base de datos
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="pagination-wrapper">
        {{ $servicios->links() }}
    </div>
</div>

@if(auth()->user()->isPropietario())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.servicio-checkbox');
        const selectAll  = document.getElementById('select-all');
        const btnSolicitar = document.getElementById('btn-solicitar');
        const buqueId    = document.getElementById('buque_id');

        function updateButtonState() {
            const hasChecked = Array.from(checkboxes).some(cb => cb.checked);
            const hasBuque   = buqueId.value !== '';
            const enabled    = hasChecked && hasBuque;
            btnSolicitar.disabled = !enabled;
            btnSolicitar.setAttribute('aria-disabled', String(!enabled));
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
