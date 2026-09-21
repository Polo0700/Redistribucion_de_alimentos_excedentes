@extends('layouts.app')

@section('title', 'Alimentos')

@section('content')

@if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Alimentos
            </h1>

            <p class="mt-1 text-gray-500">
                Administración de los alimentos disponibles
            </p>

        </div>

        <div class="flex gap-2">

            <a href="{{ route('alimentos.trashed') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">

                Ver eliminados

            </a>

            <a href="{{ route('alimentos.create') }}"
               class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

                + Nuevo alimento

            </a>

        </div>

    </div>

</div>


<!-- BÚSQUEDA -->

<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar alimento..."
            class="flex-1 p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500">

        <button
            type="button"
            class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900">

            Buscar

        </button>

    </div>

</div>


<!-- TABLA -->

<div class="relative overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">

    <table class="w-full text-sm text-left text-gray-500">

        <thead class="text-xs text-gray-700 uppercase bg-gray-100">

            <tr>

                <th class="px-6 py-3">
                    ID
                </th>

                <th class="px-6 py-3">
                    Imagen
                </th>

                <th class="px-6 py-3">
                    Nombre
                </th>

                <th class="px-6 py-3">
                    Descripción
                </th>

                <th class="px-6 py-3">
                    Categoría
                </th>

                <th class="px-6 py-3">
                    Estado
                </th>

                <th class="px-6 py-3">
                    Acciones
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach($alimentos as $alimento)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $alimento->id_alimento }}
                    </td>

                    <td class="px-6 py-4">
                        @if($alimento->imagen)
                            <img src="{{ asset('storage/' . $alimento->imagen) }}"
                                 alt="{{ $alimento->nombre }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <span class="text-xs text-gray-400">Sin imagen</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $alimento->nombre }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $alimento->descripcion }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $alimento->categoria->nombre ?? 'Sin categoría' }}
                    </td>

                    <td class="px-6 py-4">

                        @if($alimento->estado)

                            <span class="px-2.5 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                Activo
                            </span>

                        @else

                            <span class="px-2.5 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full">
                                Inactivo
                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex gap-2">

                            <a href="{{ route('alimentos.show', $alimento->id_alimento) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg">

                                Ver

                            </a>

                                 <a href="{{ route('alimentos.edit', $alimento->id_alimento) }}"
                                    class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg">
                                  Editar
                                   </a>

                            <form action="{{ route('alimentos.destroy', $alimento->id_alimento) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este alimento? Se puede restaurar después.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">

                                    Eliminar

                                </button>
                            </form>

                        </div>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


<!-- PAGINACIÓN -->

<div class="mt-6">

    {{ $alimentos->links() }}

</div>

@endsection