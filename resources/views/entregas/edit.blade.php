@extends('layouts.app')

@section('title', 'Editar entrega')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-2xl font-bold">
                Editar entrega
            </h1>

            <p class="text-gray-500">
                Modifica la información de la entrega.
            </p>
        </div>

        <a href="{{ route('entregas.index') }}"
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

        <form action="{{ route('entregas.update', $entrega->id_entrega) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Solicitud
                </label>

                <select name="id_solicitud"
                        required
                        class="w-full p-2.5 border rounded-lg">

                    @foreach($solicitudes as $solicitud)

                        <option value="{{ $solicitud->id_solicitud }}"
                            {{ old('id_solicitud', $entrega->id_solicitud) == $solicitud->id_solicitud ? 'selected' : '' }}>

                            Solicitud #{{ $solicitud->id_solicitud }}

                            -
                            {{ $solicitud->usuario->nombre ?? 'Sin usuario' }}
                            {{ $solicitud->usuario->apellido ?? '' }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Fecha de entrega
                </label>

                <input type="datetime-local"
                       name="fecha_entrega"
                       value="{{ old('fecha_entrega', \Carbon\Carbon::parse($entrega->fecha_entrega)->format('Y-m-d\TH:i')) }}"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Responsable
                </label>

                <input type="text"
                       name="responsable"
                       value="{{ old('responsable', $entrega->responsable) }}"
                       maxlength="100"
                       required
                       class="w-full p-2.5 border rounded-lg">

            </div>


            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Estado
                </label>

                <input type="text"
                       name="estado"
                       value="{{ old('estado', $entrega->estado) }}"
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
                          class="w-full p-2.5 border rounded-lg">{{ old('observaciones', $entrega->observaciones) }}</textarea>

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('entregas.index') }}"
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