@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>{{ $pantalan->nombre }}</h1>
        @if(auth()->user()->isAdmin())
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.pantalans.edit', $pantalan->id) }}" style="padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;">
                    Editar
                </a>
                <form action="{{ route('admin.pantalans.destroy', $pantalan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este pantalán?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 10px 20px; background: #c00; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>
                </form>
            </div>
        @endif
    </div>

    <a href="{{ route('pantalans.index') }}" style="display: inline-block; margin-bottom: 20px; color: #0066cc;">
        ← Volver al listado
    </a>

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Información General</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Código:</dt>
                <dd>{{ $pantalan->codigo }}</dd>
                
                <dt style="font-weight: bold;">Nombre:</dt>
                <dd>{{ $pantalan->nombre }}</dd>
                
                <dt style="font-weight: bold;">Muelle:</dt>
                <dd><a href="{{ route('muelles.show', $pantalan->muelle->id) }}" style="color: #0066cc;">{{ $pantalan->muelle->nombre }}</a></dd>
                
                <dt style="font-weight: bold;">Número de Amarre:</dt>
                <dd>{{ $pantalan->numero_amarre }}</dd>
                
                <dt style="font-weight: bold;">Tipo de Amarre:</dt>
                <dd>{{ ucfirst($pantalan->tipo_amarre) }}</dd>
                
                <dt style="font-weight: bold;">Disponible:</dt>
                <dd>
                    <span style="padding: 4px 12px; border-radius: 3px; background: {{ $pantalan->disponible ? '#efe' : '#fee' }};">
                        {{ $pantalan->disponible ? 'Sí' : 'No' }}
                    </span>
                </dd>
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Dimensiones</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Longitud Máxima:</dt>
                <dd>{{ $pantalan->longitud_maxima }} metros</dd>
                
                @if($pantalan->manga_maxima)
                    <dt style="font-weight: bold;">Manga Máxima:</dt>
                    <dd>{{ $pantalan->manga_maxima }} metros</dd>
                @endif
                
                <dt style="font-weight: bold;">Calado Máximo:</dt>
                <dd>{{ $pantalan->calado_maximo }} metros</dd>
            </dl>
        </section>

        <section style="margin-bottom: 30px;">
            <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Servicios</h2>
            <dl style="display: grid; grid-template-columns: 200px 1fr; gap: 10px;">
                <dt style="font-weight: bold;">Agua:</dt>
                <dd>{{ $pantalan->agua_disponible ? '✓ Disponible' : '✗ No disponible' }}</dd>
                
                <dt style="font-weight: bold;">Electricidad:</dt>
                <dd>{{ $pantalan->electricidad_disponible ? '✓ Disponible' : '✗ No disponible' }}</dd>
                
                @if($pantalan->electricidad_disponible && $pantalan->amperaje)
                    <dt style="font-weight: bold;">Amperaje:</dt>
                    <dd>{{ $pantalan->amperaje }} A</dd>
                @endif
                
                <dt style="font-weight: bold;">WiFi:</dt>
                <dd>{{ $pantalan->wifi_disponible ? '✓ Disponible' : '✗ No disponible' }}</dd>
                
                @if($pantalan->precio_dia)
                    <dt style="font-weight: bold;">Precio por Día:</dt>
                    <dd>{{ number_format($pantalan->precio_dia, 2) }} €</dd>
                @endif
            </dl>
        </section>

        @if($pantalan->observaciones)
            <section>
                <h2 style="border-bottom: 2px solid #0066cc; padding-bottom: 10px; margin-bottom: 15px;">Observaciones</h2>
                <p style="white-space: pre-wrap;">{{ $pantalan->observaciones }}</p>
            </section>
        @endif
    </div>
</div>
@endsection
