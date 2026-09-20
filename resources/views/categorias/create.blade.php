@extends('layouts.app')

@section('title', 'Nueva categoria')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva categoria</h1>
    <p class="text-gray-600 mt-1">
        Formulario para registrar una categoria de alimentos.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('categorias.store') }}">

@csrf

<div class="space-y-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="80" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Ej. Frutas">
        @error('nombre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Descripcion</label>
        <textarea rows="4" name="descripcion" maxlength="200" required
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Descripcion de la categoria">{{ old('descripcion') }}</textarea>
        @error('descripcion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <input type="hidden" name="estado" value="0">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="estado" value="1" checked class="w-4 h-4">
            <span>Categoria activa</span>
        </label>
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

<a href="{{ route('categorias.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection