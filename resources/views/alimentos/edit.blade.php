@extends('layouts.app')

@section('title', 'Editar alimento')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Editar alimento
            </h1>

            <p class="text-gray-500">
                Modifica la información del alimento.
            </p>
        </div>

        <a
            href="{{ route('alimentos.index') }}"
            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"
        >
            Volver
        </a>

    </div>


    {{-- Errores --}}
    @if ($errors->any())

        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">

            <p class="font-semibold mb-2">
                Corrige los siguientes errores:
            </p>

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Formulario --}}
    <div class="bg-white rounded-lg shadow p-6">

        <form
            action="{{ route('alimentos.update', $alimento->id_alimento) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            {{-- Categoría --}}
            <div class="mb-5">

                <label
                    for="id_categoria"
                    class="block mb-2 text-sm font-medium text-gray-900"
                >
                    Categoría
                </label>

                <select
                    id="id_categoria"
                    name="id_categoria"
                    required
                    class="w-full p-2.5 border rounded-lg"
                >

                    @foreach($categorias as $categoria)

                        <option
                            value="{{ $categoria->id_categoria }}"
                            {{ old('id_categoria', $alimento->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}
                        >
                            {{ $categoria->nombre }}
                        </option>

                    @endforeach

                </select>

                @error('id_categoria')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Nombre --}}
            <div class="mb-5">

                <label
                    for="nombre"
                    class="block mb-2 text-sm font-medium text-gray-900"
                >
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre', $alimento->nombre) }}"
                    maxlength="80"
                    required
                    class="w-full p-2.5 border rounded-lg"
                >

                @error('nombre')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Descripción --}}
            <div class="mb-5">

                <label
                    for="descripcion"
                    class="block mb-2 text-sm font-medium text-gray-900"
                >
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    maxlength="200"
                    rows="4"
                    required
                    class="w-full p-2.5 border rounded-lg"
                >{{ old('descripcion', $alimento->descripcion) }}</textarea>

                @error('descripcion')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Estado --}}
            <div class="mb-5">

                <label
                    for="estado"
                    class="block mb-2 text-sm font-medium text-gray-900"
                >
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                    class="w-full p-2.5 border rounded-lg"
                >

                    <option
                        value="1"
                        {{ old('estado', $alimento->estado) == 1 ? 'selected' : '' }}
                    >
                        Activo
                    </option>

                    <option
                        value="0"
                        {{ old('estado', $alimento->estado) == 0 ? 'selected' : '' }}
                    >
                        Inactivo
                    </option>

                </select>

                @error('estado')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Imagen actual --}}
            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Imagen actual
                </label>

                @if($alimento->imagen)

                    <div class="mb-4">

                        <img
                            src="{{ asset('storage/' . $alimento->imagen) }}"
                            alt="{{ $alimento->nombre }}"
                            class="w-48 h-48 object-cover rounded-lg border"
                        >

                        <p class="mt-2 text-sm text-gray-500">
                            Imagen actualmente registrada.
                        </p>

                    </div>

                @else

                    <div class="mb-4 p-4 bg-gray-100 rounded-lg">

                        <p class="text-gray-500">
                            Este alimento no tiene una imagen actualmente.
                        </p>

                    </div>

                @endif

            </div>


            {{-- Nueva imagen --}}
            <div class="mb-6">

                <label
                    for="imagen"
                    class="block mb-2 text-sm font-medium text-gray-900"
                >
                    Nueva imagen
                </label>

                <input
                    type="file"
                    id="imagen"
                    name="imagen"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="w-full p-2.5 border rounded-lg"
                >

                <p class="mt-1 text-sm text-gray-500">
                    Opcional. Si no seleccionas una imagen,
                    se conservará la actual.
                    Formatos permitidos: JPG, JPEG, PNG y WEBP.
                    Máximo 2 MB.
                </p>

                @error('imagen')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Botones --}}
            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('alimentos.index') }}"
                    class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection