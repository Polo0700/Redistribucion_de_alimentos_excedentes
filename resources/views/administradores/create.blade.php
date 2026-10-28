@extends('layouts.app')

@section('title', 'Nuevo administrador')

@section('content')

<div class="mb-6">

    <h1 class="text-3xl font-bold text-gray-900">
        Nuevo administrador
    </h1>

    <p class="mt-1 text-gray-500">
        Registrar una cuenta de administrador con su nivel de autorización
    </p>

</div>


<div class="max-w-3xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <form method="POST" action="{{ route('administradores.store') }}" enctype="multipart/form-data">

        @csrf

        <!-- NOMBRE COMPLETO -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Nombre completo
            </label>

            <input
                type="text"
                name="nombre_completo"
                value="{{ old('nombre_completo') }}"
                maxlength="50"
                required
                placeholder="Ej. Juan Pérez"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">

            @error('nombre_completo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>


        <!-- USERNAME -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Username
            </label>

            <input
                type="text"
                name="username"
                value="{{ old('username') }}"
                maxlength="30"
                required
                placeholder="Ej. jperez"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">

            @error('username')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>


        <!-- CONTRASEÑA -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Contraseña
            </label>

            <input
                type="password"
                name="password"
                minlength="6"
                required
                placeholder="Mínimo 6 caracteres"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">

            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>


        <!-- NIVEL -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Nivel de autorización
            </label>

            <select
                name="nivel"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">

                <option value="1" {{ old('nivel') == 1 ? 'selected' : '' }}>
                    Nivel 1 — Solo consulta
                </option>

                <option value="2" {{ old('nivel') == 2 ? 'selected' : '' }}>
                    Nivel 2 — Consulta, crear y editar
                </option>

                <option value="3" {{ old('nivel') == 3 ? 'selected' : '' }}>
                    Nivel 3 — Acceso total (incluye eliminar y gestión de administradores)
                </option>

            </select>

            @error('nivel')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>


        <!-- FOTO -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Foto (opcional)
            </label>

            <input
                type="file"
                name="foto"
                accept="image/*"
                class="block w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer">

            @error('foto')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>


        <!-- ESTADO -->

        <div class="mb-6">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Estado
            </label>

            <input type="hidden" name="activo" value="0">

            <label class="inline-flex items-center cursor-pointer">

                <input
                    type="checkbox"
                    name="activo"
                    value="1"
                    checked
                    class="sr-only peer">

                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>

                <span class="ms-3 text-sm font-medium text-gray-900">
                    Activo
                </span>

            </label>

            @error('activo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>


        <!-- BOTONES -->

        <div class="flex gap-3">

            <a
                href="{{ route('administradores.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">

                Cancelar

            </a>

            <button
                type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

                Guardar

            </button>

        </div>

    </form>

</div>

@endsection
