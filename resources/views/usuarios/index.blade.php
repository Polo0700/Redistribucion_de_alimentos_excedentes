@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
        <p class="text-gray-600 mt-1">
            Consulta y administración de los usuarios del sistema.
        </p>
    </div>

    <a href="{{ route('usuarios.create') }}"
       class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
        + Nuevo usuario
    </a>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">

    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Nombre</th>
                <th class="px-6 py-3">Rol</th>
                <th class="px-6 py-3">Correo</th>
                <th class="px-6 py-3">Teléfono</th>
                <th class="px-6 py-3">Estado</th>
                <th class="px-6 py-3">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4">1</td>
                <td class="px-6 py-4 font-medium text-gray-900">
                    Juan Pérez
                </td>
                <td class="px-6 py-4">Administrador</td>
                <td class="px-6 py-4">juan@example.com</td>
                <td class="px-6 py-4">3312345678</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                        Activo
                    </span>
                </td>
                <td class="px-6 py-4 space-x-2">
                    <button class="font-medium text-blue-600 hover:underline">
                        Ver
                    </button>

                    <button class="font-medium text-yellow-600 hover:underline">
                        Editar
                    </button>

                    <button class="font-medium text-red-600 hover:underline">
                        Eliminar
                    </button>
                </td>
            </tr>

            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4">2</td>
                <td class="px-6 py-4 font-medium text-gray-900">
                    María López
                </td>
                <td class="px-6 py-4">Donador</td>
                <td class="px-6 py-4">maria@example.com</td>
                <td class="px-6 py-4">3398765432</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                        Activo
                    </span>
                </td>
                <td class="px-6 py-4 space-x-2">
                    <button class="font-medium text-blue-600 hover:underline">
                        Ver
                    </button>

                    <button class="font-medium text-yellow-600 hover:underline">
                        Editar
                    </button>

                    <button class="font-medium text-red-600 hover:underline">
                        Eliminar
                    </button>
                </td>
            </tr>

        </tbody>
    </table>

</div>

@endsection