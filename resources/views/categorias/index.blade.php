@extends('layouts.app')

@section('title', 'Categorías de alimentos')

@section('content')

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">

<div>
    <h1 class="text-2xl font-bold text-gray-900">Categorías de alimentos</h1>
    <p class="text-gray-600 mt-1">
        Administración de las categorías utilizadas para clasificar los alimentos.
    </p>
</div>

<a href="{{ route('categorias.create') }}"
   class="mt-4 md:mt-0 px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
    + Nueva categoría
</a>

</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">

<table class="w-full text-sm text-left text-gray-500">

<thead class="text-xs text-gray-700 uppercase bg-gray-100">
<tr>
    <th class="px-6 py-3">ID</th>
    <th class="px-6 py-3">Nombre</th>
    <th class="px-6 py-3">Descripción</th>
    <th class="px-6 py-3">Estado</th>
    <th class="px-6 py-3">Acciones</th>
</tr>
</thead>

<tbody>

<tr class="border-b hover:bg-gray-50">
    <td class="px-6 py-4">1</td>
    <td class="px-6 py-4 font-medium text-gray-900">Frutas</td>
    <td class="px-6 py-4">Alimentos de origen frutal.</td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded-full">
            Activa
        </span>
    </td>
    <td class="px-6 py-4 space-x-2">
        <button class="text-blue-600 hover:underline">Ver</button>
        <button class="text-yellow-600 hover:underline">Editar</button>
        <button class="text-red-600 hover:underline">Eliminar</button>
    </td>
</tr>

<tr class="border-b hover:bg-gray-50">
    <td class="px-6 py-4">2</td>
    <td class="px-6 py-4 font-medium text-gray-900">Verduras</td>
    <td class="px-6 py-4">Vegetales disponibles.</td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded-full">
            Activa
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