@extends('layouts.app')

@section('title', 'Acciones importantes')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        Acciones importantes
    </h1>

    <p class="text-gray-600 mt-1">
        Registro de las acciones relevantes realizadas en el sistema.
    </p>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">

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
    <th class="px-6 py-3">Acciones</th>
</tr>
</thead>

<tbody>

<tr class="border-b hover:bg-gray-50">

    <td class="px-6 py-4">1</td>

    <td class="px-6 py-4 font-medium text-gray-900">
        Juan Pérez
    </td>

    <td class="px-6 py-4">
        Registro
    </td>

    <td class="px-6 py-4">
        usuarios
    </td>

    <td class="px-6 py-4">
        Se registró un nuevo usuario.
    </td>

    <td class="px-6 py-4">
        05/09/2026 10:30
    </td>

    <td class="px-6 py-4">
        127.0.0.1
    </td>

    <td class="px-6 py-4">
        <button class="text-blue-600 hover:underline">
            Ver
        </button>
    </td>

</tr>

<tr class="border-b hover:bg-gray-50">

    <td class="px-6 py-4">2</td>

    <td class="px-6 py-4 font-medium text-gray-900">
        María López
    </td>

    <td class="px-6 py-4">
        Actualización
    </td>

    <td class="px-6 py-4">
        donaciones
    </td>

    <td class="px-6 py-4">
        Se actualizó una donación.
    </td>

    <td class="px-6 py-4">
        05/09/2026 11:20
    </td>

    <td class="px-6 py-4">
        127.0.0.1
    </td>

    <td class="px-6 py-4">
        <button class="text-blue-600 hover:underline">
            Ver
        </button>
    </td>

</tr>

</tbody>
</table>

</div>

@endsection