@extends('layouts.app')

@section('title', 'Nueva acción importante')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        Nueva acción importante
    </h1>

    <p class="text-gray-600 mt-1">
        Formulario visual para registrar una acción importante.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">
            Usuario
        </label>

        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione un usuario</option>
            <option>Juan Pérez</option>
            <option>María López</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Acción
        </label>

        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione una acción</option>
            <option>Registro</option>
            <option>Actualización</option>
            <option>Eliminación</option>
            <option>Inicio de sesión</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Tabla afectada
        </label>

        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Ej. usuarios">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Fecha y hora
        </label>

        <input type="datetime-local"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            IP de origen
        </label>

        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="127.0.0.1">
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">
            Descripción
        </label>

        <textarea rows="4"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Descripción de la acción"></textarea>
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('acciones.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection