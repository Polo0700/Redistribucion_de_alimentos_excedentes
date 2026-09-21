@extends('layouts.app')

@section('title', 'Entregas')

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
                Entregas
            </h1>

            <p class="mt-1 text-gray-500">
                Administración de las entregas de alimentos
            </p>

        </div>

        <div class="flex gap-2">

        <a href="{{ route('entregas.trashed') }}"
           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">

            Ver eliminados

        </a>

        <a href="{{ route('entregas.create') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

            + Nueva entrega

        </a>

    </div>

    </div>

</div>


<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar entrega..."
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
                <th class="px-6 py-3">Solicitud</th>
                <th class="px-6 py-3">Fecha de entrega</th>
                <th class="px-6 py-3">Responsable</th>
                <th class="px-6 py-3">Estado</th>
                <th class="px-6 py-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @foreach($entregas as $entrega)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $entrega->id_entrega }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        Solicitud #{{ $entrega->solicitud->id_solicitud ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $entrega->fecha_entrega }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $entrega->responsable }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                            {{ $entrega->estado }}
                        </span>
                    </td>

                    <td class="px-6 py-4">

                        <div class="flex gap-2">

                            <a href="{{ route('entregas.show', $entrega->id_entrega) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg">
                                Ver
                            </a>

                           <a href="{{ route('entregas.edit', $entrega->id_entrega) }}"
                          class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg">
                             Editar
                               </a>

                            <form action="{{ route('entregas.destroy', $entrega->id_entrega) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta entrega? Se puede restaurar después.')">
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

    {{ $entregas->links() }}

</div>

@endsection