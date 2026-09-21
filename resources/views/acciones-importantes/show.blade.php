@extends('layouts.app')

@section('title', 'Detalle de la acción importante')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la acción importante
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('acciones.index') }}"
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
                <p class="text-gray-900">{{ $accion->id_accion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuario</p>
                <p class="text-gray-900">{{ $accion->usuario->nombre ?? 'Sin usuario' }} {{ $accion->usuario->apellido ?? '' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Acción</p>
                <p class="text-gray-900">{{ $accion->accion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Tabla afectada</p>
                <p class="text-gray-900">{{ $accion->tabla_afectada }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Descripción</p>
                <p class="text-gray-900">{{ $accion->descripcion ?? 'Sin descripción' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha y hora</p>
                <p class="text-gray-900">{{ $accion->fecha_hora }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">IP de origen</p>
                <p class="text-gray-900">{{ $accion->ip_origen }}</p>
            </div>

        </div>

    </div>

</div>

@endsection