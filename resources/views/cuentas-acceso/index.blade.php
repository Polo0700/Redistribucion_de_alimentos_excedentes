@extends('layouts.app')

@section('title', 'Cuentas de acceso')

@section('content')

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Cuentas de acceso</h1>
        <p class="text-gray-600 mt-1">
            Consulta de las cuentas utilizadas para acceder al sistema.
        </p>
    </div>

    <a href="{{ route('cuentas-acceso.create') }}"
       class="mt-4 md:mt-0 px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
        + Nueva cuenta
    </a>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">

<table class="w-full text-sm text-left text-gray-500">

<thead class="text-xs text-gray-700 uppercase bg-gray-100">
<tr>
    <th class="px-6 py-3">ID</th>
    <th class="px-6 py-3">Usuario</th>
    <th class="px-6 py-3">Proveedor</th>
    <th class="px-6 py-3">Identificador</th>
    <th class="px-6 py-3">Último acceso</th>
    <th class="px-6 py-3">Acciones</th>
</tr>
</thead>

<tbody>

<tr class="bg-white border-b hover:bg-gray-50">
    <td class="px-6 py-4">1</td>
    <td class="px-6 py-4 font-medium text-gray-900">Juan Pérez</td>
    <td class="px-6 py-4">Correo</td>
    <td class="px-6 py-4">juan@example.com</td>
    <td class="px-6 py-4">05/09/2026 10:30</td>
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