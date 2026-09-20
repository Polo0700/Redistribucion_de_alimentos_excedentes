@extends('layouts.app')

@section('title', 'Editar donación')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold">
                Editar donación
            </h1>

            <p class="text-gray-500">
                Modifica los datos de la donación.
            </p>
        </div>

        <a href="{{ route('donaciones.index') }}"
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

        <form action="{{ route('donaciones.update', $donacion->id_donacion) }}"
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
                            {{ old('id_usuario', $donacion->id_usuario) == $usuario->id ? 'selected' : '' }}>

                            {{ $usuario->nombre }} {{ $usuario->apellido }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Fecha de donación
                </label>

                <input type="datetime-local"
                       name="fecha_donacion"
                       value="{{ old('fecha_donacion', \Carbon\Carbon::parse($donacion->fecha_donacion)->format('Y-m-d\TH:i')) }}"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Fecha límite
                </label>

                <input type="date"
                       name="fecha_limite"
                       value="{{ old('fecha_limite', $donacion->fecha_limite) }}"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Ubicación
                </label>

                <input type="text"
                       name="ubicacion"
                       value="{{ old('ubicacion', $donacion->ubicacion) }}"
                       maxlength="200"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Estado
                </label>

                <input type="text"
                       name="estado"
                       value="{{ old('estado', $donacion->estado) }}"
                       maxlength="30"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Observaciones
                </label>

                <textarea name="observaciones"
                          maxlength="250"
                          rows="4"
                          class="w-full p-2.5 border rounded-lg">{{ old('observaciones', $donacion->observaciones) }}</textarea>

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('donaciones.index') }}"
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