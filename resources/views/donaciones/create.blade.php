@extends('layouts.app')

@section('title', 'Nueva donación')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva donación</h1>
    <p class="text-gray-600 mt-1">
        Registre una nueva donación de alimentos.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form>

<h2 class="text-lg font-semibold text-gray-900 mb-4">
    Información de la donación
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Donador</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione un usuario</option>
            <option>María López</option>
            <option>Juan Pérez</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Fecha de donación</label>
        <input type="datetime-local"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Fecha límite</label>
        <input type="date"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Disponible</option>
            <option>En proceso</option>
            <option>Entregada</option>
            <option>Cancelada</option>
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">Ubicación</label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Lugar donde se encuentra la donación">
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">Observaciones</label>
        <textarea rows="3"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Observaciones"></textarea>
    </div>

</div>

<hr class="my-8">

<h2 class="text-lg font-semibold text-gray-900 mb-4">
    Alimentos de la donación
</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <div>
        <label class="block mb-2 text-sm font-medium">Alimento</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione</option>
            <option>Arroz</option>
            <option>Manzana</option>
            <option>Frijol</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Cantidad</label>
        <input type="number"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="0">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Observaciones</label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Opcional">
    </div>

</div>

<div class="mt-4">
    <button type="button"
            class="px-4 py-2 text-sm text-blue-700 bg-blue-100 rounded-lg">
        + Agregar alimento
    </button>
</div>

<div class="flex gap-3 mt-8">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar donación
</button>

<a href="{{ route('donaciones.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection