@extends('layouts.app')

@section('title', 'Detalle del alimento')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle del alimento
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('alimentos.index') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900">

            Volver al listado

        </a>

    </div>

</div>


<div class="bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="p-6">

        <div class="flex gap-6">

            @if($alimento->imagen)

                <img src="{{ asset('storage/' . $alimento->imagen) }}"
                     alt="{{ $alimento->nombre }}"
                     class="w-40 h-40 object-cover rounded-lg">

            @else

                <div class="flex items-center justify-center w-40 h-40 text-sm text-gray-400 bg-gray-100 rounded-lg">
                    Sin imagen
                </div>

            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">ID</p>
                    <p class="text-gray-900">{{ $alimento->id_alimento }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Nombre</p>
                    <p class="text-gray-900">{{ $alimento->nombre }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Descripción</p>
                    <p class="text-gray-900">{{ $alimento->descripcion }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Categoría</p>
                    <p class="text-gray-900">{{ $alimento->categoria->nombre ?? 'Sin categoría' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                    <p class="text-gray-900">{{ $alimento->estado ? 'Activo' : 'Inactivo' }}</p>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection