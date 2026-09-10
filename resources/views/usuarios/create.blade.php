@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nuevo usuario</h1>
    <p class="text-gray-600 mt-1">
        Formulario para registrar un nuevo usuario.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

    <form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Nombre
                </label>
                <input type="text"
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="Ingrese el nombre">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Apellido
                </label>
                <input type="text"
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="Ingrese el apellido">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Rol
                </label>
                <select class="w-full p-2.5 border border-gray-300 rounded-lg">
                    <option>Seleccione un rol</option>
                    <option>Administrador</option>
                    <option>Donador</option>
                    <option>Beneficiario</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Correo electrónico
                </label>
                <input type="email"
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="correo@ejemplo.com">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Teléfono
                </label>
                <input type="tel"
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="Ingrese el teléfono">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Fecha de registro
                </label>
                <input type="date"
                       class="w-full p-2.5 border border-gray-300 rounded-lg">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Dirección
                </label>
                <textarea rows="3"
                          class="w-full p-2.5 border border-gray-300 rounded-lg"
                          placeholder="Ingrese la dirección"></textarea>
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="w-4 h-4">
                    <span class="text-sm text-gray-900">Usuario activo</span>
                </label>
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button type="button"
                    class="px-5 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700">
                Guardar
            </button>

            <a href="{{ route('usuarios.index') }}"
               class="px-5 py-2.5 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
        </div>

    </form>

</div>

@endsection