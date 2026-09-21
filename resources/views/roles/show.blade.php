@extends('layouts.app')

@section('title', 'Detalle del rol')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle del rol
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('roles.index') }}"
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
                <p class="text-gray-900">{{ $rol->id }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nombre</p>
                <p class="text-gray-900">{{ $rol->nombre }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Descripción</p>
                <p class="text-gray-900">{{ $rol->descripcion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $rol->estado ? 'Activo' : 'Inactivo' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuarios asignados</p>
                <p class="text-gray-900">{{ $rol->usuarios()->count() }}</p>
            </div>

        </div>

    </div>

</div>

@endsection