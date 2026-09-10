@extends('layouts.app')

@section('title', 'Nueva categoría')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva categoría</h1>
    <p class="text-gray-600 mt-1">
        Formulario para registrar una categoría de alimentos.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form>

<div class="space-y-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Nombre</label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Ej. Frutas">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Descripción</label>
        <textarea rows="4"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Descripción de la categoría"></textarea>
    </div>

    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" class="w-4 h-4">
            <span>Categoría activa</span>
        </label>
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('categorias.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection