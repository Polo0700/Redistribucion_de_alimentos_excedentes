@extends('layouts.app')

@section('title', 'Detalle del administrador')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle del administrador
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('administradores.index') }}"
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
                <p class="text-gray-900">{{ $administrador->id }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nombre completo</p>
                <p class="text-gray-900">{{ $administrador->nombre_completo }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Username</p>
                <p class="text-gray-900">{{ $administrador->username }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nivel de autorización</p>
                <p class="text-gray-900">
                    Nivel {{ $administrador->nivel }}
                    ({{ $administrador->nivel == 3 ? 'Acceso total' : ($administrador->nivel == 2 ? 'Consulta, crear y editar' : 'Solo consulta') }})
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $administrador->activo ? 'Activo' : 'Inactivo' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Foto</p>

                @if($administrador->foto)
                    <img src="{{ asset('storage/' . $administrador->foto) }}"
                         alt="Foto de {{ $administrador->username }}"
                         class="w-20 h-20 rounded-full object-cover">
                @else
                    <p class="text-gray-500 italic">Sin foto registrada</p>
                @endif

            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Creado el</p>
                <p class="text-gray-900">{{ $administrador->created_at }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Última actualización</p>
                <p class="text-gray-900">{{ $administrador->updated_at }}</p>
            </div>

        </div>

    </div>

</div>

@endsection
