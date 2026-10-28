@extends('layouts.app')

@section('title', 'Editar administrador')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Editar administrador
            </h1>

            <p class="text-gray-500">
                Modifica la información del administrador seleccionado.
            </p>
        </div>

        <a href="{{ route('administradores.index') }}"
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

        <form action="{{ route('administradores.update', $administrador->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            @method('PUT')


            <div class="mb-5">

                <label for="nombre_completo"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Nombre completo
                </label>

                <input
                    type="text"
                    id="nombre_completo"
                    name="nombre_completo"
                    value="{{ old('nombre_completo', $administrador->nombre_completo) }}"
                    maxlength="50"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mb-5">

                <label for="username"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username', $administrador->username) }}"
                    maxlength="30"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mb-5">

                <label for="password"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Nueva contraseña (dejar vacío para mantener la actual)
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="6"
                    placeholder="Mínimo 6 caracteres"
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mb-5">

                <label for="nivel"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Nivel de autorización
                </label>

                <select
                    id="nivel"
                    name="nivel"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

                    <option value="1"
                        {{ old('nivel', $administrador->nivel) == 1 ? 'selected' : '' }}>
                        Nivel 1 — Solo consulta
                    </option>

                    <option value="2"
                        {{ old('nivel', $administrador->nivel) == 2 ? 'selected' : '' }}>
                        Nivel 2 — Consulta, crear y editar
                    </option>

                    <option value="3"
                        {{ old('nivel', $administrador->nivel) == 3 ? 'selected' : '' }}>
                        Nivel 3 — Acceso total
                    </option>

                </select>

                <p class="mt-1 text-xs text-gray-400">
                    El nivel se valida en el servidor contra los valores permitidos (1, 2 o 3).
                </p>

            </div>


            <div class="mb-5">

                <label for="foto"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Foto (opcional)
                </label>

                <input
                    type="file"
                    id="foto"
                    name="foto"
                    accept="image/*"
                    class="w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer">

            </div>


            <div class="mb-5">

                <label for="activo"
                       class="block mb-2 text-sm font-medium text-gray-900">
                    Estado
                </label>

                <select
                    id="activo"
                    name="activo"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

                    <option value="1"
                        {{ old('activo', $administrador->activo) == 1 ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="0"
                        {{ old('activo', $administrador->activo) == 0 ? 'selected' : '' }}>
                        Inactivo
                    </option>

                </select>

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('administradores.index') }}"
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
