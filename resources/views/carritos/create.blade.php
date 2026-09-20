@extends('layouts.app')

@section('title', 'Nuevo carrito')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nuevo carrito</h1>
    <p class="text-gray-600 mt-1">
        Formulario para crear un carrito.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('carritos.store') }}">

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Usuario</label>
        <select name="id_usuario" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione un usuario</option>
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
        <label class="block mb-2 text-sm font-medium">Fecha de creacion</label>
        <input type="datetime-local" name="fecha_creacion" value="{{ old('fecha_creacion') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_creacion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select name="estado" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione un estado</option>
            <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Procesado" {{ old('estado') == 'Procesado' ? 'selected' : '' }}>Procesado</option>
            <option value="Finalizado" {{ old('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
        </select>
        @error('estado')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="submit"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('carritos.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection