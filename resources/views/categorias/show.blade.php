@extends('layouts.app')

@section('title', 'Detalle de la categoría')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la categoría
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('categorias.index') }}"
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
                <p class="text-gray-900">{{ $categoria->id_categoria }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nombre</p>
                <p class="text-gray-900">{{ $categoria->nombre }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Descripción</p>
                <p class="text-gray-900">{{ $categoria->descripcion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $categoria->estado ? 'Activo' : 'Inactivo' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Registros de alimentos asociados</p>
                <p class="text-gray-900">{{ $categoria->alimentos()->count() }}</p>
            </div>

        </div>

    </div>

</div>

@endsection