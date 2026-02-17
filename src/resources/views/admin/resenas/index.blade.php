@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700;">Gestión de Reseñas</h1>
    </div>

    @if (session('success'))
        <div role="alert" aria-live="polite" style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div role="alert" aria-live="assertive" style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter -->
    <div style="margin-bottom: 1.5rem;">
        <label for="filter-status" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Filtrar por estado:</label>
        <select id="filter-status" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; min-width: 200px;">
            <option value="">Todas</option>
            <option value="1">Aprobadas</option>
            <option value="0">Pendientes</option>
        </select>
    </div>

    <!-- Reviews Table -->
    <div id="reviews-container">
        <div style="text-align: center; padding: 2rem; color: #6b7280;">
            Cargando reseñas...
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="background: white; max-width: 600px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Editar Reseña</h2>
            
            <form id="edit-form">
                <input type="hidden" id="edit-resena-id">
                
                <div style="margin-bottom: 1rem;">
                    <label for="edit-calificacion" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        Calificación <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="edit-calificacion" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="1">1 - Muy malo</option>
                        <option value="2">2 - Malo</option>
                        <option value="3">3 - Regular</option>
                        <option value="4">4 - Bueno</option>
                        <option value="5">5 - Excelente</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="edit-comentario" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        Comentario <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea id="edit-comentario" required minlength="10" maxlength="500" rows="4" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"></textarea>
                    <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                        Mínimo 10 caracteres, máximo 500
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeEditModal()" style="padding: 0.5rem 1rem; background: #e5e7eb; border: none; border-radius: 0.375rem; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let allReviews = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Load reviews
    function loadReviews(filter = '') {
        const url = filter !== '' ? `/api/resenas?aprobado=${filter}` : '/api/resenas';
        
        fetch(url, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            allReviews = data.data || data;
            renderReviews();
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            document.getElementById('reviews-container').innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">Error al cargar reseñas</div>';
        });
    }

    function renderReviews() {
        if (allReviews.length === 0) {
            document.getElementById('reviews-container').innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280;">No hay reseñas</div>';
            return;
        }

        const tableHtml = `
            <table style="width: 100%; border-collapse: collapse; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                <thead>
                    <tr style="background: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Usuario</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Servicio</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Calificación</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Comentario</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Estado</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${allReviews.map(resena => {
                        const stars = '★'.repeat(resena.calificacion) + '☆'.repeat(5 - resena.calificacion);
                        const userName = resena.user.perfil?.nombre || resena.user.name;
                        const comentarioShort = resena.comentario.length > 50 ? resena.comentario.substring(0, 50) + '...' : resena.comentario;
                        
                        return `
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 0.75rem;">${userName}</td>
                                <td style="padding: 0.75rem;">${resena.servicio.nombre}</td>
                                <td style="padding: 0.75rem; color: #fbbf24;">${stars}</td>
                                <td style="padding: 0.75rem;">${comentarioShort}</td>
                                <td style="padding: 0.75rem;">
                                    ${resena.aprobado 
                                        ? '<span style="color: #10b981;">✓ Aprobada</span>' 
                                        : '<span style="color: #f59e0b;">⏳ Pendiente</span>'}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <button onclick="editResena(${resena.id})" style="padding: 0.25rem 0.5rem; background: #3b82f6; color: white; border: none; border-radius: 0.25rem; cursor: pointer; margin-right: 0.5rem;">
                                        Editar
                                    </button>
                                    ${!resena.aprobado ? `
                                        <button onclick="aprobarResena(${resena.id})" style="padding: 0.25rem 0.5rem; background: #10b981; color: white; border: none; border-radius: 0.25rem; cursor: pointer; margin-right: 0.5rem;">
                                            Aprobar
                                        </button>
                                    ` : ''}
                                    <button onclick="deleteResena(${resena.id})" style="padding: 0.25rem 0.5rem; background: #ef4444; color: white; border: none; border-radius: 0.25rem; cursor: pointer;">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        `;

        document.getElementById('reviews-container').innerHTML = tableHtml;
    }

    function editResena(id) {
        const resena = allReviews.find(r => r.id === id);
        if (!resena) return;

        document.getElementById('edit-resena-id').value = id;
        document.getElementById('edit-calificacion').value = resena.calificacion;
        document.getElementById('edit-comentario').value = resena.comentario;
        document.getElementById('edit-modal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    function aprobarResena(id) {
        if (!confirm('¿Aprobar esta reseña?')) return;

        fetch(`/api/resenas/${id}/aprobar`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            loadReviews(document.getElementById('filter-status').value);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al aprobar reseña');
        });
    }

    function deleteResena(id) {
        if (!confirm('¿Eliminar esta reseña permanentemente?')) return;

        fetch(`/api/resenas/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            loadReviews(document.getElementById('filter-status').value);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar reseña');
        });
    }

    // Edit form submission
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('edit-resena-id').value;
        const data = {
            calificacion: document.getElementById('edit-calificacion').value,
            comentario: document.getElementById('edit-comentario').value
        };

        fetch(`/api/resenas/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            closeEditModal();
            loadReviews(document.getElementById('filter-status').value);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar reseña');
        });
    });

    // Filter change
    document.getElementById('filter-status').addEventListener('change', function() {
        loadReviews(this.value);
    });

    // Initial load
    loadReviews();
</script>
@endsection
