@extends('layouts.app')

@section('title', 'Nueva lista de deseos')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva lista de deseos</h1>
    <p class="text-gray-600 mt-1">
        Cree una lista y seleccione los alimentos deseados.
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
        <label class="block mb-2 text-sm font-medium">Nombre de la lista</label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Ej. Alimentos para la semana">
    </div>

</div>

<div class="mt-6">
    <label class="block mb-2 text-sm font-medium">
        Alimentos deseados
    </label>

    <select multiple
            class="w-full p-2.5 border border-gray-300 rounded-lg">
        <option>Arroz</option>
        <option>Frijol</option>
        <option>Manzana</option>
        <option>Leche</option>
    </select>

    <p class="text-sm text-gray-500 mt-1">
        Puede seleccionar uno o varios alimentos.
    </p>
</div>

<div class="flex gap-3 mt-6">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('listas-deseos.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection