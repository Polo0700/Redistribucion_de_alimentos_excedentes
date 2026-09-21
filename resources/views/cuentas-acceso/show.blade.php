@extends('layouts.app')

@section('title', 'Detalle de la cuenta de acceso')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la cuenta de acceso
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('cuentas-acceso.index') }}"
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
                <p class="text-gray-900">{{ $cuenta->id_cuenta }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Usuario</p>
                <p class="text-gray-900">{{ $cuenta->usuario->nombre ?? 'Sin usuario' }} {{ $cuenta->usuario->apellido ?? '' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Proveedor</p>
                <p class="text-gray-900">{{ $cuenta->proveedor }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Identificador externo</p>
                <p class="text-gray-900">{{ $cuenta->identificador_externo }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Contraseña (hash)</p>
                <p class="text-gray-900">••••••••••••</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Último acceso</p>
                <p class="text-gray-900">{{ $cuenta->fecha_ultimo_acceso }}</p>
            </div>

        </div>

    </div>

</div>

@endsection