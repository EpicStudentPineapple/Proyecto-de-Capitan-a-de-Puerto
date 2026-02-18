@extends('layouts.app')

@section('content')
<div class="perfiles-page">
    <div class="profile-header">
        <h1>Mi Perfil</h1>
        <a href="{{ route('perfil.editar-mi-perfil') }}" class="btn btn-primary">
            Editar Perfil
        </a>
    </div>

    @if(session('success'))
        <div role="alert" aria-live="polite" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-section">
        <section style="margin-bottom: var(--space-8);">
            <h2 class="profile-section-title" style="border-bottom: 2px solid var(--color-accent); padding-bottom: var(--space-2); margin-bottom: var(--space-4);">
                Información de Usuario
            </h2>
            <div class="data-list">
                <span class="data-label">Nombre:</span>
                <span class="data-value">{{ $user->name }}</span>
                
                <span class="data-label">Email:</span>
                <span class="data-value">{{ $user->email }}</span>
                
                <span class="data-label">Tipo de Usuario:</span>
                <span class="data-value">{{ ucfirst($perfil->tipo_usuario) }}</span>
                
                <span class="data-label">Fecha de Alta:</span>
                <span class="data-value">{{ $perfil->fecha_alta->format('d/m/Y') }}</span>
            </div>
        </section>

        <section>
            <h2 class="profile-section-title" style="border-bottom: 2px solid var(--color-accent); padding-bottom: var(--space-2); margin-bottom: var(--space-4);">
                Información Profesional
            </h2>
            <div class="data-list">
                <span class="data-label">Empresa:</span>
                <span class="data-value">{{ $perfil->empresa ?? 'No especificada' }}</span>
                
                <span class="data-label">Cargo:</span>
                <span class="data-value">{{ $perfil->cargo ?? 'No especificado' }}</span>
                
                <span class="data-label">Teléfono:</span>
                <span class="data-value">{{ $perfil->telefono ?? 'No especificado' }}</span>
                
                @if($perfil->licencia_maritima)
                    <span class="data-label">Licencia Marítima:</span>
                    <span class="data-value">{{ $perfil->licencia_maritima }}</span>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
