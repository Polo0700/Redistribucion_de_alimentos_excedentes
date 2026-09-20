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

    <form method="POST" action="{{ route('usuarios.store') }}">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="100" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="Ingrese el nombre">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Apellido</label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" maxlength="100" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="Ingrese el apellido">
                @error('apellido')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
                <select name="id_rol" required
                        class="w-full p-2.5 border border-gray-300 rounded-lg">
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" {{ old('id_rol') == $rol->id ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_rol')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Correo electronico</label>
                <input type="email" name="correo" value="{{ old('correo') }}" maxlength="150" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="correo@ejemplo.com">
                @error('correo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Telefono</label>
                <input type="tel" name="telefono" value="{{ old('telefono') }}" maxlength="100" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="Ingrese el telefono">
                @error('telefono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Fecha de registro</label>
                <input type="date" name="fecha_registro" value="{{ old('fecha_registro') }}" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg">
                @error('fecha_registro')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Direccion</label>
                <textarea rows="3" name="direccion" maxlength="200" required
                          class="w-full p-2.5 border border-gray-300 rounded-lg"
                          placeholder="Ingrese la direccion">{{ old('direccion') }}</textarea>
                @error('direccion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input type="hidden" name="estado" value="0">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="estado" value="1" checked class="w-4 h-4">
                    <span class="text-sm text-gray-900">Usuario activo</span>
                </label>
                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit"
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