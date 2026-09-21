@extends('layouts.app')

@section('title', 'Detalle del usuario')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle del usuario
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('usuarios.index') }}"
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
                <p class="text-gray-900">{{ $usuario->id }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Rol</p>
                <p class="text-gray-900">{{ $usuario->rol->nombre ?? 'Sin rol' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nombre</p>
                <p class="text-gray-900">{{ $usuario->nombre }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Apellido</p>
                <p class="text-gray-900">{{ $usuario->apellido }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Correo</p>
                <p class="text-gray-900">{{ $usuario->correo }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Teléfono</p>
                <p class="text-gray-900">{{ $usuario->telefono }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Dirección</p>
                <p class="text-gray-900">{{ $usuario->direccion }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha de registro</p>
                <p class="text-gray-900">{{ $usuario->fecha_registro }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</p>
            </div>

        </div>

    </div>

</div>

@endsection