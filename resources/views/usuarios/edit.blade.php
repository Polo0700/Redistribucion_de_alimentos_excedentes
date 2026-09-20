@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Editar usuario
            </h1>

            <p class="text-gray-500">
                Modifica la información del usuario.
            </p>
        </div>

        <a href="{{ route('usuarios.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            Volver
        </a>

    </div>


    @if ($errors->any())

        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">

            <p class="font-semibold mb-2">
                Corrige los siguientes errores:
            </p>

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('usuarios.update', $usuario->id) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Rol
                </label>

                <select name="id_rol"
                        required
                        class="w-full p-2.5 border border-gray-300 rounded-lg">

                    <option value="">
                        Selecciona un rol
                    </option>

                    @foreach($roles as $rol)

                        <option value="{{ $rol->id }}"
                            {{ old('id_rol', $usuario->id_rol) == $rol->id ? 'selected' : '' }}>

                            {{ $rol->nombre }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $usuario->nombre) }}"
                        maxlength="100"
                        required
                        class="w-full p-2.5 border border-gray-300 rounded-lg">

                </div>


                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Apellido
                    </label>

                    <input
                        type="text"
                        name="apellido"
                        value="{{ old('apellido', $usuario->apellido) }}"
                        maxlength="100"
                        required
                        class="w-full p-2.5 border border-gray-300 rounded-lg">

                </div>

            </div>


            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Correo
                </label>

                <input
                    type="email"
                    name="correo"
                    value="{{ old('correo', $usuario->correo) }}"
                    maxlength="150"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Teléfono
                </label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $usuario->telefono) }}"
                    maxlength="100"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Dirección
                </label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion', $usuario->direccion) }}"
                    maxlength="200"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Fecha de registro
                </label>

                <input
                    type="datetime-local"
                    name="fecha_registro"
                    value="{{ old('fecha_registro', \Carbon\Carbon::parse($usuario->fecha_registro)->format('Y-m-d\TH:i')) }}"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

            </div>


            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Estado
                </label>

                <select
                    name="estado"
                    required
                    class="w-full p-2.5 border border-gray-300 rounded-lg">

                    <option value="1"
                        {{ old('estado', $usuario->estado) == 1 ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="0"
                        {{ old('estado', $usuario->estado) == 0 ? 'selected' : '' }}>
                        Inactivo
                    </option>

                </select>

            </div>


            <div class="flex justify-end gap-3 mt-6">

                <a href="{{ route('usuarios.index') }}"
                   class="px-5 py-2.5 bg-gray-500 text-white rounded-lg">
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