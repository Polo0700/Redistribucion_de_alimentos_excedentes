@extends('layouts.app')

@section('title', 'Donaciones')

@section('content')

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">

<div>
    <h1 class="text-2xl font-bold text-gray-900">Donaciones</h1>
    <p class="text-gray-600 mt-1">
        Consulta de las donaciones registradas en el sistema.
    </p>
</div>

<a href="{{ route('donaciones.create') }}"
   class="mt-4 md:mt-0 px-4 py-2 text-white bg-green-600 rounded-lg">
    + Nueva donación
</a>

</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">

<table class="w-full text-sm text-left text-gray-500">

<thead class="text-xs text-gray-700 uppercase bg-gray-100">
<tr>
    <th class="px-6 py-3">ID</th>
    <th class="px-6 py-3">Donador</th>
    <th class="px-6 py-3">Fecha</th>
    <th class="px-6 py-3">Fecha límite</th>
    <th class="px-6 py-3">Ubicación</th>
    <th class="px-6 py-3">Estado</th>
    <th class="px-6 py-3">Acciones</th>
</tr>
</thead>

<tbody>

<tr class="border-b hover:bg-gray-50">
    <td class="px-6 py-4">1</td>
    <td class="px-6 py-4 font-medium text-gray-900">María López</td>
    <td class="px-6 py-4">05/09/2026</td>
    <td class="px-6 py-4">08/09/2026</td>
    <td class="px-6 py-4">Col. Centro</td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 text-xs text-blue-700 bg-blue-100 rounded-full">
            Disponible
        </span>
    </td>
    <td class="px-6 py-4 space-x-2">
        <button class="text-blue-600 hover:underline">Ver</button>
        <button class="text-yellow-600 hover:underline">Editar</button>
        <button class="text-red-600 hover:underline">Eliminar</button>
    </td>
</tr>

</tbody>
</table>

</div>

@endsection