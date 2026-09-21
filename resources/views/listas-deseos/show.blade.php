@extends('layouts.app')

@section('title', 'Detalle de la lista de deseos')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la lista de deseos
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('listas-deseos.index') }}"
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
                <p class="text-gray-900">{{ $lista->id_lista }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuario</p>
                <p class="text-gray-900">{{ $lista->usuario->nombre ?? 'Sin usuario' }} {{ $lista->usuario->apellido ?? '' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nombre</p>
                <p class="text-gray-900">{{ $lista->nombre }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha de creación</p>
                <p class="text-gray-900">{{ $lista->fecha_creacion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Deseos asociados</p>
                <p class="text-gray-900">{{ $lista->deseos()->count() }}</p>
            </div>

        </div>

    </div>

</div>

@endsection