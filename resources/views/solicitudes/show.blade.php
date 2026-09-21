@extends('layouts.app')

@section('title', 'Detalle de la solicitud')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la solicitud
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('solicitudes.index') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900">

            Volver al listado

        </a>

    </div>

</div>


<div class="bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="p-6">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">ID</p>
                <p class="text-gray-900">{{ $solicitud->id_solicitud }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuario</p>
                <p class="text-gray-900">{{ $solicitud->usuario->nombre ?? 'Sin usuario' }} {{ $solicitud->usuario->apellido ?? '' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha de la solicitud</p>
                <p class="text-gray-900">{{ $solicitud->fecha_solicitud }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $solicitud->estado }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Dirección de entrega</p>
                <p class="text-gray-900">{{ $solicitud->direccion_entrega }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Observaciones</p>
                <p class="text-gray-900">{{ $solicitud->observaciones ?? 'Sin observaciones' }}</p>
            </div>

        </div>

    </div>

</div>

@endsection