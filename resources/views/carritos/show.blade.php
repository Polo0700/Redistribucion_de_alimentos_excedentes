@extends('layouts.app')

@section('title', 'Detalle del carrito')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle del carrito
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('carritos.index') }}"
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
                <p class="text-gray-900">{{ $carrito->id_carrito }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuario</p>
                <p class="text-gray-900">{{ $carrito->usuario->nombre ?? 'Sin usuario' }} {{ $carrito->usuario->apellido ?? '' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha de creación</p>
                <p class="text-gray-900">{{ $carrito->fecha_creacion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $carrito->estado }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Detalles registrados</p>
                <p class="text-gray-900">{{ $carrito->detalles()->count() }}</p>
            </div>

        </div>

    </div>

</div>

@endsection