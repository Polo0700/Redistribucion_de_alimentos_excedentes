@extends('layouts.app')

@section('title', 'Editar rol')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Editar rol
            </h1>

            <p class="text-gray-500">
                Modifica la información del rol seleccionado.
            </p>
        </div>

        <a href="{{ route('roles.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            Volver
        </a>

    </div>


    @if ($errors->any())

        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">

            <p class="font-semibold mb-2">
                Se encontraron los siguientes errores:
            </p>

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('roles.update', $rol->id) }}"
              method="POST">

            @csrf

            @method('PUT')


            <div class="mb-5">

                <label for="nombre"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre', $rol->nombre) }}"
                    maxlength="50"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mb-5">

                <label for="descripcion"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    maxlength="200"
                    rows="4"
                    class="w-full p-2.5 border border-gray-300 rounded-lg">{{ old('descripcion', $rol->descripcion) }}</textarea>

            </div>


            <div class="mb-5">

                <label for="estado"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

                    <option value="1"
                        {{ old('estado', $rol->estado) == 1 ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="0"
                        {{ old('estado', $rol->estado) == 0 ? 'selected' : '' }}>
                        Inactivo
                    </option>

                </select>

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('roles.index') }}"
                   class="px-5 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection