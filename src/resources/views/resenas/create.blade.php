@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1>Escribir una Reseña</h1>
    <p style="color: #64748b; margin-bottom: 20px;">Tu opinión nos ayuda a mejorar nuestra plataforma y servicios.</p>

    <form action="{{ route('resenas.store') }}" method="POST" x-data="{ tipo: '{{ $servicios->isEmpty() ? 'plataforma' : 'servicio' }}' }" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        @csrf
        
        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">¿Qué quieres valorar?</label>
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                @if(!$servicios->isEmpty())
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="tipo" value="servicio" x-model="tipo" style="margin-right: 8px;">
                        Un Servicio Portuario
                    </label>
                @endif
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="tipo" value="plataforma" x-model="tipo" style="margin-right: 8px;">
                    El Sistema / Plataforma
                </label>
            </div>
            @if($servicios->isEmpty())
                <p style="font-size: 0.8rem; color: #718096; margin-top: 5px;">Nota: No tienes servicios recientes para reseñar, pero puedes valorar el sistema.</p>
            @endif
        </div>

        <div style="margin-bottom: 25px;" x-show="tipo === 'servicio'" x-cloak>
            <label for="servicio_id" style="display: block; font-weight: bold; margin-bottom: 8px;">Selecciona el servicio:</label>
            <select name="servicio_id" id="servicio_id" :required="tipo === 'servicio'" style="width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 6px;">
                <option value="">-- Selecciona un servicio --</option>
                @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                @endforeach
            </select>
            @error('servicio_id')
                <p style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">Calificación:</label>
            <div style="display: flex; gap: 15px;">
                @foreach([5,4,3,2,1] as $star)
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="calificacion" value="{{ $star }}" required style="margin-right: 5px;">
                        <span style="color: #fbbf24; font-size: 1.2rem;">{{ str_repeat('★', $star) }}</span>
                    </label>
                @endforeach
            </div>
            @error('calificacion')
                <p style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 25px;">
            <label for="comentario" style="display: block; font-weight: bold; margin-bottom: 8px;">Tu comentario:</label>
            <textarea name="comentario" id="comentario" rows="5" required minlength="10" maxlength="500" 
                :placeholder="tipo === 'servicio' ? 'Cuéntanos qué te ha parecido el servicio...' : 'Cuéntanos qué te ha parecido el sistema de gestión...'"
                style="width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 6px; resize: vertical;"></textarea>
            <p style="font-size: 0.75rem; color: #718096; margin-top: 5px;">Mínimo 10 caracteres, máximo 500.</p>
            @error('comentario')
                <p style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ route('resenas.index') }}" style="padding: 12px 24px; border: 1px solid #cbd5e0; border-radius: 6px; color: #4a5568; text-decoration: none;">
                Cancelar
            </a>
            <button type="submit" style="padding: 12px 24px; background: #0066cc; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Enviar Reseña
            </button>
        </div>
    </form>
</div>
@endsection
