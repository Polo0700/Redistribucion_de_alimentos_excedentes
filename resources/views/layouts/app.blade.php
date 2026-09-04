<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Redistribución de Alimentos')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">

        <div class="px-4 py-3 lg:px-6">

            <div class="flex items-center justify-between">

                <!-- LOGO -->
                <div class="flex items-center">

                    <button
                        data-drawer-target="logo-sidebar"
                        data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar"
                        type="button"
                        class="inline-flex items-center p-2 text-gray-500 rounded-lg sm:hidden hover:bg-gray-100">

                        <span class="sr-only">
                            Abrir menú
                        </span>

                        ☰
                    </button>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center ms-2">

                        <span class="text-xl font-bold text-green-700">
                            🍎 Redistribución
                        </span>

                    </a>

                </div>


                <!-- USUARIO -->
                <div class="flex items-center">

                    <button
                        type="button"
                        class="flex text-sm bg-gray-800 rounded-full"
                        data-dropdown-toggle="dropdown-user">

                        <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                            U
                        </div>

                    </button>

                    <div
                        id="dropdown-user"
                        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow">

                        <div class="px-4 py-3">

                            <p class="text-sm text-gray-900">
                                Usuario Demo
                            </p>

                            <p class="text-sm font-medium text-gray-500 truncate">
                                usuario@ejemplo.com
                            </p>

                        </div>

                        <ul class="py-1">

                            <li>
                                <a href="#"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Perfil
                                </a>
                            </li>

                            <li>
                                <a href="#"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Cerrar sesión
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </nav>


    <!-- SIDEBAR -->

    <aside
        id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">

        <div class="h-full px-3 pb-4 overflow-y-auto bg-white border-r">

            <ul class="space-y-2 font-medium">

                <!-- DASHBOARD -->

                <li>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">📊</span>

                        <span class="ms-3">
                            Dashboard
                        </span>

                    </a>

                </li>


                <!-- SECCIÓN PRINCIPAL -->

                <li>

                    <div class="px-2 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">
                        Gestión
                    </div>

                </li>


                <!-- USUARIOS -->

                <li>

                    <a href="{{ route('usuarios.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">👥</span>

                        <span class="ms-3">
                            Usuarios
                        </span>

                    </a>

                </li>


                <!-- ROLES -->

                <li>

                    <a href="{{ route('roles.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">🔐</span>

                        <span class="ms-3">
                            Roles
                        </span>

                    </a>

                </li>


                <!-- CUENTAS -->

                <li>

                    <a href="{{ route('cuentas-acceso.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">🔑</span>

                        <span class="ms-3">
                            Cuentas de acceso
                        </span>

                    </a>

                </li>


                <!-- ALIMENTOS -->

                <li>

                    <a href="{{ route('alimentos.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">🥦</span>

                        <span class="ms-3">
                            Alimentos
                        </span>

                    </a>

                </li>


                <!-- CATEGORÍAS -->

                <li>

                    <a href="{{ route('categorias.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">📂</span>

                        <span class="ms-3">
                            Categorías
                        </span>

                    </a>

                </li>


                <!-- DONACIONES -->

                <li>

                    <a href="{{ route('donaciones.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">🎁</span>

                        <span class="ms-3">
                            Donaciones
                        </span>

                    </a>

                </li>


                <!-- CARritos -->

                <li>

                    <a href="{{ route('carritos.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">🛒</span>

                        <span class="ms-3">
                            Carritos
                        </span>

                    </a>

                </li>


                <!-- LISTAS -->

                <li>

                    <a href="{{ route('listas-deseos.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">❤️</span>

                        <span class="ms-3">
                            Listas de deseos
                        </span>

                    </a>

                </li>


                <!-- SOLICITUDES -->

                <li>

                    <a href="{{ route('solicitudes.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">📋</span>

                        <span class="ms-3">
                            Solicitudes
                        </span>

                    </a>

                </li>


                <!-- ENTREGAS -->

                <li>

                    <a href="{{ route('entregas.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">🚚</span>

                        <span class="ms-3">
                            Entregas
                        </span>

                    </a>

                </li>


                <!-- ADMINISTRACIÓN -->

                <li>

                    <div class="px-2 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">
                        Administración
                    </div>

                </li>


                <!-- ACCIONES -->

                <li>

                    <a href="{{ route('acciones.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-100">

                        <span class="text-xl">📝</span>

                        <span class="ms-3">
                            Acciones importantes
                        </span>

                    </a>

                </li>

            </ul>

        </div>

    </aside>


    <!-- CONTENIDO -->

    <main class="p-4 sm:ml-64 pt-24">

        @yield('content')

    </main>


</body>

</html>