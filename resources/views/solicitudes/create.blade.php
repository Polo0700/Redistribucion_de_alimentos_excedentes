@extends('layouts.app')

@section('title', 'Nueva solicitud')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva solicitud</h1>
    <p class="text-gray-600 mt-1">
        Registre una solicitud de alimentos.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Usuario</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione un usuario</option>
            <option>Juan Pérez</option>
            <option>María López</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Fecha de solicitud</label>
        <input type="datetime-local"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Pendiente</option>
            <option>En proceso</option>
            <option>Aprobada</option>
            <option>Cancelada</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Dirección de entrega
        </label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Dirección">
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">
            Observaciones
        </label>
        <textarea rows="3"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Observaciones"></textarea>
    </div>

</div>

<hr class="my-8">

<h2 class="text-lg font-semibold mb-4">
    Alimentos solicitados
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block mb-2 text-sm font-medium">Alimento</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione un alimento</option>
            <option>Arroz</option>
            <option>Frijol</option>
            <option>Manzana</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Cantidad</label>
        <input type="number"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="0">
    </div>

</div>

<div class="mt-4">
    <button type="button"
            class="px-4 py-2 text-blue-700 bg-blue-100 rounded-lg">
        + Agregar alimento
    </button>
</div>

<div class="flex gap-3 mt-8">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar solicitud
</button>

<a href="{{ route('solicitudes.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection