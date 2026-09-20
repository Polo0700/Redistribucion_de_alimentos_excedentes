@extends('layouts.app')

@section('title', 'Nueva entrega')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva entrega</h1>
    <p class="text-gray-600 mt-1">
        Registre los datos de una entrega.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('entregas.store') }}">

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Solicitud</label>
        <select name="id_solicitud" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione una solicitud</option>
            @foreach($solicitudes as $solicitud)
                <option value="{{ $solicitud->id_solicitud }}" {{ old('id_solicitud') == $solicitud->id_solicitud ? 'selected' : '' }}>
                    Solicitud #{{ $solicitud->id_solicitud }}
                </option>
            @endforeach
        </select>
        @error('id_solicitud')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Fecha de entrega
        </label>
        <input type="datetime-local" name="fecha_entrega" value="{{ old('fecha_entrega') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_entrega')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">
            Responsable
        </label>
        <input type="text" name="responsable" value="{{ old('responsable') }}" maxlength="100" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Nombre del responsable">
        @error('responsable')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Estado</label>
        <select name="estado" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione un estado</option>
            <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="En camino" {{ old('estado') == 'En camino' ? 'selected' : '' }}>En camino</option>
            <option value="Entregada" {{ old('estado') == 'Entregada' ? 'selected' : '' }}>Entregada</option>
            <option value="Cancelada" {{ old('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
        @error('estado')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 text-sm font-medium">
            Observaciones
        </label>
        <textarea rows="4" name="observaciones" maxlength="250"
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Observaciones de la entrega">{{ old('observaciones') }}</textarea>
        @error('observaciones')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="submit"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg">
    Guardar
</button>

<a href="{{ route('entregas.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection