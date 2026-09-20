@extends('layouts.app')

@section('title', 'Nueva solicitud')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva solicitud</h1>
    <p class="text-gray-600 mt-1">
        Registre una solicitud de alimentos.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('solicitudes.store') }}">

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
        <label class="block mb-2 text-sm font-medium">Fecha de solicitud</label>
        <input type="datetime-local" name="fecha_solicitud" value="{{ old('fecha_solicitud') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_solicitud')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select name="estado" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione un estado</option>
            <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="En proceso" {{ old('estado') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
            <option value="Aprobada" {{ old('estado') == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
            <option value="Cancelada" {{ old('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
        @error('estado')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Direccion de entrega
        </label>
        <input type="text" name="direccion_entrega" value="{{ old('direccion_entrega') }}" maxlength="200" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Direccion">
        @error('direccion_entrega')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">
            Observaciones
        </label>
        <textarea rows="3" name="observaciones" maxlength="250"
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
    Guardar solicitud
</button>

<a href="{{ route('solicitudes.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection