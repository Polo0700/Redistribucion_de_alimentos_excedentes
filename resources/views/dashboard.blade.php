@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-6">

    <h1 class="text-3xl font-bold text-gray-900">
        Panel de administración
    </h1>

    <p class="mt-2 text-gray-600">
        Plataforma de Redistribución de Alimentos Excedentes
    </p>

</div>


<!-- TARJETAS -->

<div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 xl:grid-cols-4">

    <!-- USUARIOS -->

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Usuarios
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    24
                </h2>

            </div>

            <div class="p-3 bg-green-100 rounded-lg text-2xl">
                👥
            </div>

        </div>

        <p class="mt-3 text-sm text-green-600">
            Usuarios registrados
        </p>

    </div>


    <!-- ALIMENTOS -->

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Alimentos
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    128
                </h2>

            </div>

            <div class="p-3 bg-blue-100 rounded-lg text-2xl">
                🥦
            </div>

        </div>

        <p class="mt-3 text-sm text-blue-600">
            Alimentos disponibles
        </p>

    </div>


    <!-- DONACIONES -->

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Donaciones
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    37
                </h2>

            </div>

            <div class="p-3 bg-yellow-100 rounded-lg text-2xl">
                🎁
            </div>

        </div>

        <p class="mt-3 text-sm text-yellow-600">
            Donaciones registradas
        </p>

    </div>


    <!-- SOLICITUDES -->

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Solicitudes
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    18
                </h2>

            </div>

            <div class="p-3 bg-purple-100 rounded-lg text-2xl">
                📋
            </div>

        </div>

        <p class="mt-3 text-sm text-purple-600">
            Solicitudes pendientes
        </p>

    </div>

</div>


<!-- ACTIVIDAD -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">


    <!-- ACTIVIDAD RECIENTE -->

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <h2 class="mb-4 text-xl font-semibold text-gray-900">
            Actividad reciente
        </h2>

        <div class="space-y-4">

            <div class="flex items-center gap-3">

                <div class="p-2 bg-green-100 rounded-full">
                    🎁
                </div>

                <div>

                    <p class="font-medium text-gray-900">
                        Nueva donación registrada
                    </p>

                    <p class="text-sm text-gray-500">
                        Hace 15 minutos
                    </p>

                </div>

            </div>


            <div class="flex items-center gap-3">

                <div class="p-2 bg-blue-100 rounded-full">
                    👤
                </div>

                <div>

                    <p class="font-medium text-gray-900">
                        Nuevo usuario registrado
                    </p>

                    <p class="text-sm text-gray-500">
                        Hace 1 hora
                    </p>

                </div>

            </div>


            <div class="flex items-center gap-3">

                <div class="p-2 bg-purple-100 rounded-full">
                    📋
                </div>

                <div>

                    <p class="font-medium text-gray-900">
                        Nueva solicitud recibida
                    </p>

                    <p class="text-sm text-gray-500">
                        Hace 2 horas
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- ESTADO -->

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <h2 class="mb-4 text-xl font-semibold text-gray-900">
            Estado del sistema
        </h2>


        <div class="mb-4">

            <div class="flex justify-between mb-1">

                <span class="text-sm font-medium">
                    Alimentos redistribuidos
                </span>

                <span class="text-sm font-medium">
                    75%
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-2.5">

                <div
                    class="bg-green-600 h-2.5 rounded-full"
                    style="width: 75%">
                </div>

            </div>

        </div>


        <div class="mb-4">

            <div class="flex justify-between mb-1">

                <span class="text-sm font-medium">
                    Solicitudes atendidas
                </span>

                <span class="text-sm font-medium">
                    62%
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-2.5">

                <div
                    class="bg-blue-600 h-2.5 rounded-full"
                    style="width: 62%">
                </div>

            </div>

        </div>


        <div>

            <div class="flex justify-between mb-1">

                <span class="text-sm font-medium">
                    Entregas completadas
                </span>

                <span class="text-sm font-medium">
                    84%
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-2.5">

                <div
                    class="bg-purple-600 h-2.5 rounded-full"
                    style="width: 84%">
                </div>

            </div>

        </div>

    </div>

</div>

@endsection