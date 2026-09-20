@extends('layouts.app')

@section('title', 'Editar lista de deseos')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold">
                Editar lista de deseos
            </h1>

            <p class="text-gray-500">
                Modifica la información de la lista.
            </p>
        </div>

        <a href="{{ route('listas-deseos.index') }}"
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

        <form action="{{ route('listas-deseos.update', $lista->id_lista) }}"
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
                            {{ old('id_usuario', $lista->id_usuario) == $usuario->id ? 'selected' : '' }}>

                            {{ $usuario->nombre }} {{ $usuario->apellido }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Nombre
                </label>

                <input type="text"
                       name="nombre"
                       value="{{ old('nombre', $lista->nombre) }}"
                       maxlength="100"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Fecha de creación
                </label>

                <input type="datetime-local"
                       name="fecha_creacion"
                       value="{{ old('fecha_creacion', \Carbon\Carbon::parse($lista->fecha_creacion)->format('Y-m-d\TH:i')) }}"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('listas-deseos.index') }}"
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