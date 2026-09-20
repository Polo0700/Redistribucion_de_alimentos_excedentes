@extends('layouts.app')

@section('title', 'Nueva cuenta de acceso')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nueva cuenta de acceso</h1>
    <p class="text-gray-600 mt-1">
        Registre los datos de acceso de un usuario.
    </p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">

<form method="POST" action="{{ route('cuentas-acceso.store') }}">

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
        <label class="block mb-2 text-sm font-medium">Proveedor</label>
        <select name="proveedor" required class="w-full p-2.5 border border-gray-300 rounded-lg">
            <option value="">Seleccione proveedor</option>
            <option value="Correo" {{ old('proveedor') == 'Correo' ? 'selected' : '' }}>Correo</option>
            <option value="Google" {{ old('proveedor') == 'Google' ? 'selected' : '' }}>Google</option>
            <option value="Facebook" {{ old('proveedor') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
        </select>
        @error('proveedor')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Identificador externo</label>
        <input type="text" name="identificador_externo" value="{{ old('identificador_externo') }}" maxlength="150" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Identificador">
        @error('identificador_externo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Contrasena</label>
        <input type="password" name="contrasena" minlength="6" maxlength="255" required
               class="w-full p-2.5 border border-gray-300 rounded-lg"
               placeholder="Minimo 6 caracteres">
        @error('contrasena')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium">Fecha de ultimo acceso</label>
        <input type="datetime-local" name="fecha_ultimo_acceso" value="{{ old('fecha_ultimo_acceso') }}" required
               class="w-full p-2.5 border border-gray-300 rounded-lg">
        @error('fecha_ultimo_acceso')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex gap-3 mt-6">

<button type="submit"
        class="px-5 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700">
    Guardar
</button>

<a href="{{ route('cuentas-acceso.index') }}"
   class="px-5 py-2.5 bg-gray-200 rounded-lg">
    Cancelar
</a>

</div>

</form>

</div>

@endsection