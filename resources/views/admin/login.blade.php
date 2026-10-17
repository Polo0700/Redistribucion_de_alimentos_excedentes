@extends('layouts.app')

@section('title', 'Ingreso administrativo')

@section('content')

<div class="max-w-md mx-auto mt-12">
    <div class="bg-white rounded-lg shadow-md p-6">

        <h1 class="text-2xl font-bold text-gray-900">&Aacute;rea Administrativa</h1>
        <p class="text-gray-600 mt-1 mb-6">Ingresa con tu cuenta de acceso.</p>

        <form method="POST" action="{{ route('admin.in') }}">

            @csrf

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Correo electr&oacute;nico</label>
                <input type="email" name="identificador_externo"
                       value="{{ old('identificador_externo') }}" maxlength="150" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="correo@ejemplo.com">
                @error('identificador_externo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Contrase&ntilde;a</label>
                <input type="password" name="password" maxlength="255" required
                       class="w-full p-2.5 border border-gray-300 rounded-lg"
                       placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full px-5 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700">
                Ingresar
            </button>

        </form>

    </div>
</div>

@endsection
