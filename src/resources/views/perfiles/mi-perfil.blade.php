@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Mi Perfil</h1>
        <a href="{{ route('perfil.editar-mi-perfil') }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;">
            Editar Perfil
        </a>
    </div>

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
                <dd>{{ $user->name }}</dd>
                
                <dt style="font-weight: bold;">Email:</dt>
                <dd>{{ $user->email }}</dd>
                
                <dt style="font-weight: bold;">Tipo de Usuario:</dt>
                <dd>{{ ucfirst($perfil->tipo_usuario) }}</dd>
                
                <dt style="font-weight: bold;">Fecha de Alta:</dt>
                <dd>{{ $perfil->fecha_alta->format('d/m/Y') }}</dd>
            </dl>
        </section>

        <section>
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información Profesional</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Empresa:</dt>
                <dd>{{ $perfil->empresa ?? 'No especificada' }}</dd>
                
                <dt style="font-weight: bold;">Cargo:</dt>
                <dd>{{ $perfil->cargo ?? 'No especificado' }}</dd>
                
                <dt style="font-weight: bold;">Teléfono:</dt>
                <dd>{{ $perfil->telefono ?? 'No especificado' }}</dd>
                
                @if($perfil->licencia_maritima)
                    <dt style="font-weight: bold;">Licencia Marítima:</dt>
                    <dd>{{ $perfil->licencia_maritima }}</dd>
                @endif
            </dl>
        </section>
    </div>
</div>
@endsection
