@extends('layouts.app')

@section('title', 'Nuevo alimento')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nuevo alimento</h1>
    <p class="text-gray-600 mt-1">
        Registre un nuevo alimento en el catalogo.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form id="form-alimento" method="POST" action="{{ route('alimentos.store') }}" enctype="multipart/form-data">

@csrf

<div class="space-y-6">

    <div>
        <label class="block mb-2 text-sm font-medium">Nombre del alimento</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="80" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Ej. Arroz">
        @error('nombre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Categoria</label>
        <select name="id_categoria" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione una categoria</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
        @error('id_categoria')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Descripcion</label>
        <textarea rows="4" name="descripcion" maxlength="200" required
                  class="w-full p-2.5 border border-gray-300 rounded-lg"
                  placeholder="Descripcion del alimento">{{ old('descripcion') }}</textarea>
        @error('descripcion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Imagen del alimento</label>
        <input type="file" id="imagen" name="imagen"
               accept=".jpg,.jpeg,.png,.webp"
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        <p class="text-sm text-gray-500 mt-1">
            Formatos permitidos: JPG, PNG, WEBP. Tamano maximo: 2 MB.
        </p>
        @error('imagen')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <input type="hidden" name="estado" value="0">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="estado" value="1" checked class="w-4 h-4">
            <span>Alimento disponible</span>
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

<a href="{{ route('alimentos.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

<script>
    document.getElementById('form-alimento').addEventListener('submit', function (e) {
        const input = document.getElementById('imagen');
        const archivo = input.files[0];

        if (archivo) {
            const permitidos = ['image/jpeg', 'image/png', 'image/webp'];

            if (!permitidos.includes(archivo.type)) {
                alert('Tipo de archivo no permitido. Usa JPG, PNG o WEBP.');
                e.preventDefault();
                return;
            }

            if (archivo.size > 2 * 1024 * 1024) {
                alert('La imagen supera el tamano maximo permitido de 2 MB.');
                e.preventDefault();
            }
        }
    });
</script>

@endsection