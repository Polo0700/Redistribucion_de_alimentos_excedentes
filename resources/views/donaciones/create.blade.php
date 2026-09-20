@extends('layouts.app')

@section('title', 'Nueva donacion')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva donacion</h1>
    <p class="text-gray-600 mt-1">
        Registre una nueva donacion de alimentos.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('donaciones.store') }}">

@csrf

<h2 class="text-lg font-semibold text-gray-900 mb-4">
    Informacion de la donacion
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Donador</label>
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
        <label class="block mb-2 text-sm font-medium">Fecha de donacion</label>
        <input type="datetime-local" name="fecha_donacion" value="{{ old('fecha_donacion') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_donacion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Fecha limite</label>
        <input type="date" name="fecha_limite" value="{{ old('fecha_limite') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_limite')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select name="estado" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione un estado</option>
            <option value="Disponible" {{ old('estado') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="En proceso" {{ old('estado') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
            <option value="Entregada" {{ old('estado') == 'Entregada' ? 'selected' : '' }}>Entregada</option>
            <option value="Cancelada" {{ old('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
        @error('estado')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">Ubicacion</label>
        <input type="text" name="ubicacion" value="{{ old('ubicacion') }}" maxlength="200" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Lugar donde se encuentra la donacion">
        @error('ubicacion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">Observaciones</label>
        <textarea rows="3" name="observaciones" maxlength="250" required
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Observaciones">{{ old('observaciones') }}</textarea>
        @error('observaciones')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex gap-3 mt-8">

<button type="submit"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar donacion
</button>

<a href="{{ route('donaciones.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection