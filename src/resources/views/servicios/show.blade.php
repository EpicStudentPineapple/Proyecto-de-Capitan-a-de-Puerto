@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>{{ $servicio->nombre }}</h1>
        @if(auth()->user()->isAdmin())
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.servicios.edit', $servicio->id) }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;">
                    Editar
                </a>
                <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este servicio?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 10px 20px; background: #c00; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>
                </form>
            </div>
        @endif
    </div>

    <a href="{{ route('servicios.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
        ← Volver al listado
    </a>

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información del Servicio</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Código:</dt>
                <dd>{{ $servicio->codigo }}</dd>
                
                <dt style="font-weight: bold;">Nombre:</dt>
                <dd>{{ $servicio->nombre }}</dd>
                
                <dt style="font-weight: bold;">Tipo:</dt>
                <dd>{{ ucfirst(str_replace('_', ' ', $servicio->tipo_servicio)) }}</dd>
                
                @if($servicio->descripcion)
                    <dt style="font-weight: bold;">Descripción:</dt>
                    <dd style="white-space: pre-wrap;">{{ $servicio->descripcion }}</dd>
                @endif
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Tarifas</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Precio Base:</dt>
                <dd>{{ number_format($servicio->precio_base, 2) }} €</dd>
                
                <dt style="font-weight: bold;">Unidad de Cobro:</dt>
                <dd>{{ ucfirst(str_replace('_', ' ', $servicio->unidad_cobro)) }}</dd>
                
                @if($servicio->tiempo_estimado_minutos)
                    <dt style="font-weight: bold;">Tiempo Estimado:</dt>
                    <dd>{{ $servicio->tiempo_estimado_minutos }} minutos</dd>
                @endif
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Disponibilidad y Contacto</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Disponible 24h:</dt>
                <dd>{{ $servicio->disponible_24h ? 'Sí' : 'No' }}</dd>
                
                <dt style="font-weight: bold;">Requiere Reserva:</dt>
                <dd>{{ $servicio->requiere_reserva ? 'Sí' : 'No' }}</dd>
                
                @if($servicio->proveedor)
                    <dt style="font-weight: bold;">Proveedor:</dt>
                    <dd>{{ $servicio->proveedor }}</dd>
                @endif
                
                @if($servicio->telefono_contacto)
                    <dt style="font-weight: bold;">Teléfono:</dt>
                    <dd><a href="tel:{{ $servicio->telefono_contacto }}" style="color: #0066cc;">{{ $servicio->telefono_contacto }}</a></dd>
                @endif
            </dl>
        </section>

        @if($servicio->buques->count() > 0)
            <section>
                <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Buques que han Solicitado este Servicio</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left;">Buque</th>
                            <th style="padding: 10px; text-align: left;">Fecha Solicitud</th>
                            <th style="padding: 10px; text-align: left;">Estado</th>
                            <th style="padding: 10px; text-align: left;">Cantidad</th>
                            <th style="padding: 10px; text-align: left;">Precio Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicio->buques as $buque)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">{{ $buque->nombre }}</td>
                                <td style="padding: 10px;">{{ \Carbon\Carbon::parse($buque->pivot->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                                <td style="padding: 10px;">
                                    <span style="padding: 4px 8px; border-radius: 3px; font-size: 14px; background: 
                                        @if($buque->pivot->estado == 'completado') #efe @elseif($buque->pivot->estado == 'en_proceso') #fef @else #ffe @endif;">
                                        {{ ucfirst(str_replace('_', ' ', $buque->pivot->estado)) }}
                                    </span>
                                </td>
                                <td style="padding: 10px;">{{ $buque->pivot->cantidad }}</td>
                                <td style="padding: 10px;">{{ number_format($buque->pivot->precio_total, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif

        <!-- Reviews Section -->
        <section style="margin-top: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Reseñas del Servicio</h2>
            
            <div id="reviews-stats" style="margin-bottom: 20px;">
                <div style="text-align: center; padding: 1rem; color: #6b7280;">
                    Cargando calificación...
                </div>
            </div>

            @if(auth()->user()->isPropietario())
                <div style="margin-bottom: 20px;">
                    <button id="create-review-btn" onclick="showCreateReviewForm()" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">
                        Dejar una Reseña
                    </button>
                </div>

                <!-- Create Review Form -->
                <div id="create-review-form" style="display: none; background: #f9fafb; padding: 20px; border-radius: 4px; margin-bottom: 20px;">
                    <h3 style="margin-bottom: 15px;">Escribir Reseña</h3>
                    <form id="review-form">
                        <div style="margin-bottom: 15px;">
                            <label for="calificacion" style="display: block; font-weight: 600; margin-bottom: 5px;">
                                Calificación <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="calificacion" required style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
                                <option value="">Seleccione...</option>
                                <option value="1">1 - Muy malo</option>
                                <option value="2">2 - Malo</option>
                                <option value="3">3 - Regular</option>
                                <option value="4">4 - Bueno</option>
                                <option value="5">5 - Excelente</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="comentario" style="display: block; font-weight: 600; margin-bottom: 5px;">
                                Comentario <span style="color: #ef4444;">*</span>
                            </label>
                            <textarea id="comentario" required minlength="10" maxlength="500" rows="4" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;"></textarea>
                            <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                                Mínimo 10 caracteres, máximo 500
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                Enviar Reseña
                            </button>
                            <button type="button" onclick="hideCreateReviewForm()" style="padding: 10px 20px; background: #e5e7eb; border: none; border-radius: 4px; cursor: pointer;">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div id="reviews-list">
                <div style="text-align: center; padding: 2rem; color: #6b7280;">
                    Cargando reseñas...
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    const servicioId = {{ $servicio->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const isPropietario = {{ auth()->user()->isPropietario() ? 'true' : 'false' }};

    // Load reviews for this service
    function loadReviews() {
        fetch(`/api/resenas/servicio/${servicioId}`)
            .then(response => response.json())
            .then(data => {
                // Display average rating
                const statsHtml = data.promedio 
                    ? `<div style="text-align: center; font-size: 1.5rem;">
                        <span style="color: #fbbf24;">★</span> 
                        <strong>${data.promedio}</strong> / 5.0
                        <span style="color: #6b7280; font-size: 1rem;">(${data.resenas.length} reseñas)</span>
                       </div>`
                    : '<div style="text-align: center; color: #6b7280;">Sin reseñas aún</div>';
                
                document.getElementById('reviews-stats').innerHTML = statsHtml;

                // Display reviews
                if (data.resenas.length === 0) {
                    document.getElementById('reviews-list').innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280;">No hay reseñas para este servicio</div>';
                } else {
                    const reviewsHtml = data.resenas.map(resena => {
                        const stars = '★'.repeat(resena.calificacion) + '☆'.repeat(5 - resena.calificacion);
                        const fecha = new Date(resena.fecha_resena).toLocaleDateString('es-ES');
                        const userName = resena.user.perfil?.nombre || resena.user.name;
                        
                        return `
                            <div style="border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px; margin-bottom: 15px; background: white;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <strong>${userName}</strong>
                                    <span style="color: #fbbf24;">${stars}</span>
                                </div>
                                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 10px;">${fecha}</div>
                                <div style="color: #374151;">${resena.comentario}</div>
                            </div>
                        `;
                    }).join('');

                    document.getElementById('reviews-list').innerHTML = reviewsHtml;
                }
            })
            .catch(error => {
                console.error('Error loading reviews:', error);
                document.getElementById('reviews-list').innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">Error al cargar reseñas</div>';
            });
    }

    // Check if propietario can review this service
    if (isPropietario) {
    fetch('/api/resenas/mis-servicios', {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const canReview = data.some(s => s.id === servicioId);
            if (canReview) {
                document.getElementById('create-review-btn').style.display = 'inline-block';
            }
        })
        .catch(error => console.error('Error checking review eligibility:', error));
    }

    function showCreateReviewForm() {
        document.getElementById('create-review-form').style.display = 'block';
        document.getElementById('create-review-btn').style.display = 'none';
    }

    function hideCreateReviewForm() {
        document.getElementById('create-review-form').style.display = 'none';
        document.getElementById('create-review-btn').style.display = 'inline-block';
        document.getElementById('review-form').reset();
    }

    // Handle review submission
    if (isPropietario) {
        document.getElementById('review-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const data = {
                servicio_id: servicioId,
                calificacion: document.getElementById('calificacion').value,
                comentario: document.getElementById('comentario').value
            };

            fetch('/api/resenas', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    hideCreateReviewForm();
                    document.getElementById('create-review-btn').style.display = 'none';
                    loadReviews();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar reseña');
            });
        });
    }

    // Initial load
    loadReviews();
</script>
@endsection
