@extends('layouts.app')

@section('title', 'Nueva cuenta de acceso')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva cuenta de acceso</h1>
    <p class="text-gray-600 mt-1">
        Registre los datos de acceso de un usuario.
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
        <label class="block mb-2 text-sm font-medium">Proveedor</label>
        <select class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option>Seleccione proveedor</option>
            <option>Correo</option>
            <option>Google</option>
            <option>Facebook</option>
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Identificador externo</label>
        <input type="text"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Identificador">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Contraseña</label>
        <input type="password"
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Contraseña">
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Fecha de último acceso</label>
        <input type="datetime-local"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="button"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700">
    Guardar
</button>

<a href="{{ route('cuentas-acceso.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection