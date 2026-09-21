@extends('layouts.app')

@section('title', 'Detalle de la entrega')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Detalle de la entrega
            </h1>

            <p class="mt-1 text-gray-500">
                Vista de consulta del registro (solo lectura)
            </p>

        </div>

        <a href="{{ route('entregas.index') }}"
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
                <p class="text-gray-900">{{ $entrega->id_entrega }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Solicitud</p>
                <p class="text-gray-900">Solicitud #{{ $entrega->solicitud->id_solicitud ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Fecha de entrega</p>
                <p class="text-gray-900">{{ $entrega->fecha_entrega }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Responsable</p>
                <p class="text-gray-900">{{ $entrega->responsable }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Estado</p>
                <p class="text-gray-900">{{ $entrega->estado }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Observaciones</p>
                <p class="text-gray-900">{{ $entrega->observaciones ?? 'Sin observaciones' }}</p>
            </div>

        </div>

    </div>

</div>

@endsection