@extends('layouts.app')

@section('title', 'Administradores')

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
                Administradores
            </h1>

            <p class="mt-1 text-gray-500">
                Cuentas del área administrativa y su nivel de autorización
            </p>

        </div>

        <div class="flex gap-2">

            <a href="{{ route('administradores.niveles') }}"
               class="px-5 py-2.5 text-sm font-medium text-purple-700 bg-purple-100 rounded-lg hover:bg-purple-200">
                Matriz de niveles
            </a>

            <a href="{{ route('administradores.trashed') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">

                Ver eliminados

            </a>

            @can('crear')
            <a href="{{ route('administradores.create') }}"
               class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                + Nuevo administrador
            </a>
            @endcan

            @cannot('crear')
            <span class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-100 rounded-lg border border-gray-200 inline-flex items-center">
                🔒 Creación restringida (Nivel {{ auth('admin')->user()->nivel ?? 1 }})
            </span>
            @endcannot

        </div>

    </div>

</div>


<!-- TABLA -->

<div class="relative overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">

    <table class="w-full text-sm text-left text-gray-500">

        <thead class="text-xs text-gray-700 uppercase bg-gray-100">

            <tr>

                <th class="px-6 py-3">ID</th>

                <th class="px-6 py-3">Nombre completo</th>

                <th class="px-6 py-3">Username</th>

                <th class="px-6 py-3">Nivel</th>

                <th class="px-6 py-3">Estado</th>

                <th class="px-6 py-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @forelse($administradores as $administrador)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $administrador->id }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $administrador->nombre_completo }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $administrador->username }}
                    </td>

                    <td class="px-6 py-4">

                        @if($administrador->nivel == 3)
                            <span class="px-2.5 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full">
                                Nivel 3 (Superadmin)
                            </span>
                        @elseif($administrador->nivel == 2)
                            <span class="px-2.5 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                Nivel 2 (Editor)
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full">
                                Nivel 1 (Consulta)
                            </span>
                        @endif

                    </td>

                    <td class="px-6 py-4">

                        @if($administrador->activo)
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

                        <div class="flex items-center gap-2">

                            <a href="{{ route('administradores.show', $administrador->id) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                                Ver
                            </a>

                            @can('editar')
                            <a href="{{ route('administradores.edit', $administrador->id) }}"
                               class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                Editar
                            </a>
                            @endcan

                            @can('eliminar')
                            @unless($administrador->id === auth('admin')->id())
                            <form action="{{ route('administradores.destroy', $administrador->id) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este administrador? Se puede restaurar después.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">
                                    Eliminar
                                </button>
                            </form>
                            @endunless
                            @endcan

                            @cannot('editar')
                            <span class="text-xs text-gray-400 italic">
                                Solo lectura
                            </span>
                            @endcannot

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        No hay administradores registrados.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>


<!-- PAGINACIÓN -->

<div class="mt-6">

    {{ $administradores->links() }}

</div>

@endsection
