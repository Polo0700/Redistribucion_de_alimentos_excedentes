@extends('layouts.app')

@section('title', 'Editar categoría')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold">
                Editar categoría
            </h1>

            <p class="text-gray-500">
                Modifica la información de la categoría.
            </p>
        </div>

        <a href="{{ route('categorias.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg">
            Volver
        </a>

    </div>


    @if ($errors->any())

        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('categorias.update', $categoria->id_categoria) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Nombre
                </label>

                <input type="text"
                       name="nombre"
                       value="{{ old('nombre', $categoria->nombre) }}"
                       maxlength="80"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Descripción
                </label>

                <textarea name="descripcion"
                          maxlength="200"
                          rows="4"
                          class="w-full p-2.5 border rounded-lg">{{ old('descripcion', $categoria->descripcion) }}</textarea>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Estado
                </label>

                <select name="estado"
                        required
                        class="w-full p-2.5 border rounded-lg">

                    <option value="1"
                        {{ old('estado', $categoria->estado) == 1 ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="0"
                        {{ old('estado', $categoria->estado) == 0 ? 'selected' : '' }}>
                        Inactivo
                    </option>

                </select>

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('categorias.index') }}"
                   class="px-5 py-2.5 bg-gray-500 text-white rounded-lg">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-lg">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection