@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="mb-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Usuarios
            </h1>

            <p class="mt-1 text-gray-500">
                Administración de los usuarios del sistema
            </p>

        </div>

        <a href="{{ route('usuarios.create') }}"
           class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

            + Nuevo usuario

        </a>

    </div>

</div>


<!-- BÚSQUEDA -->

<div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="flex flex-col gap-4 md:flex-row">

        <input
            type="text"
            placeholder="Buscar usuario..."
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
                    Nombre
                </th>

                <th class="px-6 py-3">
                    Correo
                </th>

                <th class="px-6 py-3">
                    Teléfono
                </th>

                <th class="px-6 py-3">
                    Rol
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

            @foreach($usuarios as $usuario)

                <tr class="bg-white border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $usuario->id }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $usuario->nombre }} {{ $usuario->apellido }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $usuario->correo }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $usuario->telefono }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $usuario->rol->nombre ?? 'Sin rol' }}
                    </td>

                    <td class="px-6 py-4">

                        @if($usuario->estado)

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

                            <button
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">

                                Ver

                            </button>

                            <button
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200">

                                Editar

                            </button>

                            <button
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">

                                Eliminar

                            </button>

                        </div>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


<!-- PAGINACIÓN -->

<div class="mt-6">

    {{ $usuarios->links() }}

</div>

@endsection