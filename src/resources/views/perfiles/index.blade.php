@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">Perfiles de Usuario</h1>

    @if(session('success'))
        <div role="alert" aria-live="polite" style="background: #efe; border: 1px solid #0a0; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #060;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Usuario</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Email</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Tipo</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Empresa</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Activo</th>
                    <th style="padding: 12px; text-align: left; font-weight: bold;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perfiles as $perfil)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">{{ $perfil->user->name }}</td>
                        <td style="padding: 12px;">{{ $perfil->user->email }}</td>
                        <td style="padding: 12px;">{{ ucfirst($perfil->tipo_usuario) }}</td>
                        <td style="padding: 12px;">{{ $perfil->empresa ?? 'N/A' }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; border-radius: 3px; background: {{ $perfil->activo ? '#efe' : '#fee' }};">
                                {{ $perfil->activo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <a href="{{ route('admin.perfiles.show', $perfil->id) }}" style="color: #0066cc; margin-right: 10px;">Ver</a>
                            <a href="{{ route('admin.perfiles.edit', $perfil->id) }}" style="color: #0066cc;">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #666;">
                            No hay perfiles registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $perfiles->links() }}
    </div>
</div>
@endsection
