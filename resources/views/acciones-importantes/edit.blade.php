@extends('layouts.app')

@section('title', 'Editar acción importante')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold">
                Editar acción importante
            </h1>

            <p class="text-gray-500">
                Modifica la información registrada.
            </p>
        </div>

        <a href="{{ route('acciones.index') }}"
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

        <form action="{{ route('acciones.update', $accion->id_accion) }}"
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
                            {{ old('id_usuario', $accion->id_usuario) == $usuario->id ? 'selected' : '' }}>

                            {{ $usuario->nombre }} {{ $usuario->apellido }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Acción
                </label>

                <input type="text"
                       name="accion"
                       value="{{ old('accion', $accion->accion) }}"
                       maxlength="100"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Tabla afectada
                </label>

                <input type="text"
                       name="tabla_afectada"
                       value="{{ old('tabla_afectada', $accion->tabla_afectada) }}"
                       maxlength="80"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Descripción
                </label>

                <textarea name="descripcion"
                          maxlength="250"
                          rows="4"
                          class="w-full p-2.5 border rounded-lg">{{ old('descripcion', $accion->descripcion) }}</textarea>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Fecha y hora
                </label>

                <input type="datetime-local"
                       name="fecha_hora"
                       value="{{ old('fecha_hora', \Carbon\Carbon::parse($accion->fecha_hora)->format('Y-m-d\TH:i')) }}"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    IP de origen
                </label>

                <input type="text"
                       name="ip_origen"
                       value="{{ old('ip_origen', $accion->ip_origen) }}"
                       maxlength="45"
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('acciones.index') }}"
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