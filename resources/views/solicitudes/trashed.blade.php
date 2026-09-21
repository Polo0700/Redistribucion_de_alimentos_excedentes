@extends('layouts.app')

@section('title', 'Solicitudes eliminadas')

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
                Solicitudes eliminadas
            </h1>

            <p class="mt-1 text-gray-500">
                Registros con borrado lógico que pueden restaurarse o eliminarse definitivamente
            </p>

        </div>

        <a href="{{ route('solicitudes.index') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900">

            Volver al listado

        </a>

    </div>

</div>


<!-- TABLA -->

<div class="relative overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">

    <table class="w-full text-sm text-left text-gray-500">

        <thead class="text-xs text-gray-700 uppercase bg-gray-100">

            <tr>

                <th class="px-6 py-3">ID</th>

                <th class="px-6 py-3">Estado</th>

                <th class="px-6 py-3">Fecha</th>

                <th class="px-6 py-3">Eliminado el</th>

                <th class="px-6 py-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @forelse($solicitudes as $solicitud)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $solicitud->id_solicitud }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $solicitud->estado }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $solicitud->fecha_solicitud }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $solicitud->deleted_at }}
                    </td>

                    <td class="px-6 py-4">

                        <div class="flex gap-2">

                            <form action="{{ route('solicitudes.restore', $solicitud->id_solicitud) }}" method="POST"
                                  onsubmit="return confirm('¿Restaurar esta solicitud? Volverá a mostrarse en el listado principal.')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200">
                                    Restaurar
                                </button>
                            </form>

                            <form action="{{ route('solicitudes.forceDestroy', $solicitud->id_solicitud) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar DEFINITIVAMENTE? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">
                                    Eliminar definitivamente
                                </button>
                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                        No hay solicitudes eliminadas.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>


<!-- PAGINACIÓN -->

<div class="mt-6">

    {{ $solicitudes->links() }}

</div>

@endsection