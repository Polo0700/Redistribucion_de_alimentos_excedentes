@extends('layouts.app')

@section('title', 'Detalle de la donación')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la donación
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('donaciones.index') }}"
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
                <p class="text-gray-900">{{ $donacion->id_donacion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuario</p>
                <p class="text-gray-900">{{ $donacion->usuario->nombre ?? 'Sin usuario' }} {{ $donacion->usuario->apellido ?? '' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha de donación</p>
                <p class="text-gray-900">{{ $donacion->fecha_donacion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha límite</p>
                <p class="text-gray-900">{{ $donacion->fecha_limite }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Ubicación</p>
                <p class="text-gray-900">{{ $donacion->ubicacion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $donacion->estado }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Observaciones</p>
                <p class="text-gray-900">{{ $donacion->observaciones ?? 'Sin observaciones' }}</p>
            </div>

        </div>

    </div>

</div>

@endsection