@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Perfil de {{ $perfil->user->name }}</h1>
        <a href="{{ route('admin.perfiles.edit', $perfil->id) }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;">
            Editar
        </a>
    </div>

    <a href="{{ route('admin.perfiles.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
        ← Volver al listado
    </a>

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información de Usuario</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Nombre:</dt>
                <dd>{{ $perfil->user->name }}</dd>
                
                <dt style="font-weight: bold;">Email:</dt>
                <dd><a href="mailto:{{ $perfil->user->email }}" style="color: #0066cc;">{{ $perfil->user->email }}</a></dd>
                
                <dt style="font-weight: bold;">Tipo de Usuario:</dt>
                <dd>{{ ucfirst($perfil->tipo_usuario) }}</dd>
                
                <dt style="font-weight: bold;">Estado:</dt>
                <dd>
                    <span style="padding: 4px 12px; border-radius: 3px; background: {{ $perfil->activo ? '#efe' : '#fee' }};">
                        {{ $perfil->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </dd>
                
                <dt style="font-weight: bold;">Fecha de Alta:</dt>
                <dd>{{ $perfil->fecha_alta->format('d/m/Y') }}</dd>
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información Profesional</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                @if($perfil->empresa)
                    <dt style="font-weight: bold;">Empresa:</dt>
                    <dd>{{ $perfil->empresa }}</dd>
                @endif
                
                @if($perfil->cargo)
                    <dt style="font-weight: bold;">Cargo:</dt>
                    <dd>{{ $perfil->cargo }}</dd>
                @endif
                
                @if($perfil->telefono)
                    <dt style="font-weight: bold;">Teléfono:</dt>
                    <dd><a href="tel:{{ $perfil->telefono }}" style="color: #0066cc;">{{ $perfil->telefono }}</a></dd>
                @endif
                
                @if($perfil->licencia_maritima)
                    <dt style="font-weight: bold;">Licencia Marítima:</dt>
                    <dd>{{ $perfil->licencia_maritima }}</dd>
                @endif
            </dl>
        </section>
    </div>
</div>
@endsection
