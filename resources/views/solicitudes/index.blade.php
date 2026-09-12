@extends('layouts.app')

@section('title', 'Solicitudes')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Solicitudes
            </h1>

            <p class="mt-1 text-gray-500">
                Administración de solicitudes de alimentos
            </p>

        </div>

        <a href="{{ route('solicitudes.create') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

            + Nueva solicitud

        </a>

    </div>

</div>


<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar solicitud..."
            class="flex-1 p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50">

        <button
            type="button"
            class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg">

            Buscar

        </button>

    </div>

</div>


<div class="relative overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">

    <table class="w-full text-sm text-left text-gray-500">

        <thead class="text-xs text-gray-700 uppercase bg-gray-100">

            <tr>

                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Usuario</th>
                <th class="px-6 py-3">Fecha</th>
                <th class="px-6 py-3">Estado</th>
                <th class="px-6 py-3">Dirección de entrega</th>
                <th class="px-6 py-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @foreach($solicitudes as $solicitud)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $solicitud->id_solicitud }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $solicitud->usuario->nombre ?? 'Sin usuario' }}
                        {{ $solicitud->usuario->apellido ?? '' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $solicitud->fecha_solicitud }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                            {{ $solicitud->estado }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        {{ $solicitud->direccion_entrega }}
                    </td>

                    <td class="px-6 py-4">

                        <div class="flex gap-2">

                            <button type="button"
                                class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg">
                                Ver
                            </button>

                            <button type="button"
                                class="px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-lg">
                                Editar
                            </button>

                            <button type="button"
                                class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg">
                                Eliminar
                            </button>

                        </div>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


<div class="mt-6">

    {{ $solicitudes->links() }}

</div>

@endsection