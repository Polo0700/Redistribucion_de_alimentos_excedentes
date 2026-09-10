@extends('layouts.app')

@section('title', 'Nueva entrega')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva entrega</h1>
    <p class="text-gray-600 mt-1">
        Registre los datos de una entrega.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Solicitud</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione una solicitud</option>
            <option>Solicitud #1</option>
            <option>Solicitud #2</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Fecha de entrega
        </label>
        <input type="datetime-local"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Responsable
        </label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Nombre del responsable">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Pendiente</option>
            <option>En camino</option>
            <option>Entregada</option>
            <option>Cancelada</option>
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">
            Observaciones
        </label>
        <textarea rows="4"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Observaciones de la entrega"></textarea>
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('entregas.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection