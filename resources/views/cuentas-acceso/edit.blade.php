@extends('layouts.app')

@section('title', 'Editar cuenta de acceso')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Editar cuenta de acceso
            </h1>

            <p class="text-gray-500">
                Modifica los datos de la cuenta.
            </p>
        </div>

        <a href="{{ route('cuentas-acceso.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg">
            Volver
        </a>

    </div>


    @if ($errors->any())

        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('cuentas-acceso.update', $cuenta->id_cuenta) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Usuario
                </label>

                <select name="id_usuario"
                        required
                        class="w-full p-2.5 border rounded-lg">

                    @foreach($usuarios as $usuario)

                        <option value="{{ $usuario->id }}"
                            {{ old('id_usuario', $cuenta->id_usuario) == $usuario->id ? 'selected' : '' }}>

                            {{ $usuario->nombre }} {{ $usuario->apellido }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Proveedor
                </label>

                <input type="text"
                       name="proveedor"
                       value="{{ old('proveedor', $cuenta->proveedor) }}"
                       maxlength="30"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Identificador externo
                </label>

                <input type="text"
                       name="identificador_externo"
                       value="{{ old('identificador_externo', $cuenta->identificador_externo) }}"
                       maxlength="150"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Contraseña / Hash
                </label>

                <input type="text"
                       name="contrasena_hash"
                       value="{{ old('contrasena_hash', $cuenta->contrasena_hash) }}"
                       maxlength="255"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Último acceso
                </label>

                <input type="datetime-local"
                       name="fecha_ultimo_acceso"
                       value="{{ old('fecha_ultimo_acceso', \Carbon\Carbon::parse($cuenta->fecha_ultimo_acceso)->format('Y-m-d\TH:i')) }}"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('cuentas-acceso.index') }}"
                   class="px-5 py-2.5 bg-gray-500 text-white rounded-lg">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-lg">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection