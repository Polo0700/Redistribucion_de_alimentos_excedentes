@extends('layouts.app')

@section('title', 'Nuevo rol')

@section('content')

<div class="mb-6">

    <h1 class="text-3xl font-bold text-gray-900">
        Nuevo rol
    </h1>

    <p class="mt-1 text-gray-500">
        Registrar un nuevo rol en el sistema
    </p>

</div>


<div class="max-w-3xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

    <form>

        <!-- NOMBRE -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Nombre del rol
            </label>

            <input
                type="text"
                placeholder="Ej. Administrador"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">

        </div>


        <!-- DESCRIPCIÓN -->

        <div class="mb-5">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Descripción
            </label>

            <textarea
                rows="4"
                placeholder="Describe las funciones del rol..."
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500"></textarea>

        </div>


        <!-- ESTADO -->

        <div class="mb-6">

            <label class="block mb-2 text-sm font-medium text-gray-900">
                Estado
            </label>

            <label class="inline-flex items-center cursor-pointer">

                <input
                    type="checkbox"
                    checked
                    class="sr-only peer">

                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>

                <span class="ms-3 text-sm font-medium text-gray-900">
                    Activo
                </span>

            </label>

        </div>


        <!-- BOTONES -->

        <div class="flex gap-3">

            <a
                href="{{ route('roles.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">

                Cancelar

            </a>

            <button
                type="button"
                class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

                Guardar

            </button>

        </div>

    </form>

</div>

@endsection