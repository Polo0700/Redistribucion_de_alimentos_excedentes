@extends('layouts.app')

@section('title', 'Acciones importantes')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Acciones importantes
            </h1>

            <p class="mt-1 text-gray-500">
                Registro de acciones importantes realizadas en el sistema
            </p>

        </div>

    </div>

</div>


<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar acción..."
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
                <th class="px-6 py-3">Acción</th>
                <th class="px-6 py-3">Tabla afectada</th>
                <th class="px-6 py-3">Descripción</th>
                <th class="px-6 py-3">Fecha y hora</th>
                <th class="px-6 py-3">IP</th>

            </tr>

        </thead>

        <tbody>

            @foreach($acciones as $accion)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $accion->id_accion }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">

                        {{ $accion->usuario->nombre ?? 'Sin usuario' }}

                        {{ $accion->usuario->apellido ?? '' }}

                    </td>

                    <td class="px-6 py-4">
                        {{ $accion->accion }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $accion->tabla_afectada }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $accion->descripcion }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $accion->fecha_hora }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $accion->ip_origen }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


<div class="mt-6">

    {{ $acciones->links() }}

</div>

@endsection