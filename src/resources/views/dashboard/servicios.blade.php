@extends('layouts.app')

@section('title', 'Servicios del Puerto')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Servicios Portuarios Disponibles</h1>
    
    <p class="mb-6"><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Volver al Dashboard</a></p>

    @if($servicios->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No hay servicios registrados en el sistema.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($servicios as $tipo => $listaServicios)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">
                        {{ ucfirst(str_replace('_', ' ', $tipo)) }}
                    </h2>
                    
                    <ul class="space-y-3">
                        @foreach($listaServicios as $servicio)
                            <li class="flex justify-between items-center">
                                <span>{{ $servicio->nombre }}</span>
                                <span class="font-bold text-gray-700">{{ number_format($servicio->precio_base, 2) }} €</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
