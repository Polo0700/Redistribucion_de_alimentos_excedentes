@extends('layouts.app')

@section('title', 'Nueva accion importante')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        Nueva accion importante
    </h1>

    <p class="text-gray-600 mt-1">
        Registre una accion importante en el sistema.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('acciones.store') }}">

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">
            Usuario
        </label>

        <select name="id_usuario" class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione un usuario (opcional)</option>
            @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}" {{ old('id_usuario') == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->nombre }} {{ $usuario->apellido }}
                </option>
            @endforeach
        </select>
        @error('id_usuario')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Accion
        </label>

        <select name="accion" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione una accion</option>
            <option value="Registro" {{ old('accion') == 'Registro' ? 'selected' : '' }}>Registro</option>
            <option value="Actualizacion" {{ old('accion') == 'Actualizacion' ? 'selected' : '' }}>Actualizacion</option>
            <option value="Eliminacion" {{ old('accion') == 'Eliminacion' ? 'selected' : '' }}>Eliminacion</option>
            <option value="Inicio de sesion" {{ old('accion') == 'Inicio de sesion' ? 'selected' : '' }}>Inicio de sesion</option>
        </select>
        @error('accion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Tabla afectada
        </label>

        <input type="text" name="tabla_afectada" value="{{ old('tabla_afectada') }}" maxlength="80" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Ej. usuarios">
        @error('tabla_afectada')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Fecha y hora
        </label>

        <input type="datetime-local" name="fecha_hora" value="{{ old('fecha_hora') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_hora')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            IP de origen
        </label>

        <input type="text" name="ip_origen" value="{{ old('ip_origen') }}" maxlength="45" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="127.0.0.1">
        @error('ip_origen')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">
            Descripcion
        </label>

        <textarea rows="4" name="descripcion" maxlength="250"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Descripcion de la accion">{{ old('descripcion') }}</textarea>
        @error('descripcion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="submit"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('acciones.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection