@extends('layouts.app')

@section('title', 'Carritos')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Carritos
            </h1>

            <p class="mt-1 text-gray-500">
                Administración de los carritos de los usuarios
            </p>

        </div>

        <a href="{{ route('carritos.create') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

            + Nuevo carrito

        </a>

    </div>

</div>


<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar carrito..."
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
                <th class="px-6 py-3">Fecha de creación</th>
                <th class="px-6 py-3">Estado</th>
                <th class="px-6 py-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @foreach($carritos as $carrito)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $carrito->id_carrito }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $carrito->usuario->nombre ?? 'Sin usuario' }}
                        {{ $carrito->usuario->apellido ?? '' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $carrito->fecha_creacion }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                            {{ $carrito->estado }}
                        </span>
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

    {{ $carritos->links() }}

</div>

@endsection