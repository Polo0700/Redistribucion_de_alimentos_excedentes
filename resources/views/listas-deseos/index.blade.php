@extends('layouts.app')

@section('title', 'Listas de deseos')

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
                Listas de deseos
            </h1>

            <p class="mt-1 text-gray-500">
                Administración de las listas de deseos
            </p>

        </div>

        <div class="flex gap-2">

        <a href="{{ route('listas-deseos.trashed') }}"
           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">

            Ver eliminados

        </a>

        <a href="{{ route('listas-deseos.create') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

            + Nueva lista

        </a>

    </div>

    </div>

</div>


<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar lista..."
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
                <th class="px-6 py-3">Nombre</th>
                <th class="px-6 py-3">Fecha de creación</th>
                <th class="px-6 py-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @foreach($listas as $lista)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $lista->id_lista }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $lista->usuario->nombre ?? 'Sin usuario' }}
                        {{ $lista->usuario->apellido ?? '' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $lista->nombre }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $lista->fecha_creacion }}
                    </td>

                    <td class="px-6 py-4">

                        <div class="flex gap-2">

                            <a href="{{ route('listas-deseos.show', $lista->id_lista) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg">
                                Ver
                            </a>

                            <a href="{{ route('listas-deseos.edit', $lista->id_lista) }}"
                             class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg">
                               Editar
                             </a>

                            <form action="{{ route('listas-deseos.destroy', $lista->id_lista) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta lista de deseos? Se puede restaurar después.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg">
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


<div class="mt-6">

    {{ $listas->links() }}

</div>

@endsection