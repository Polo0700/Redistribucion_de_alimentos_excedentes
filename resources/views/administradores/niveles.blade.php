@extends('layouts.app')

@section('title', 'Matriz de Niveles de Acceso')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- ENCABEZADO -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Gestión de Administradores
            </h1>
            <p class="mt-1 text-gray-600">
                Matriz de autorización por niveles
            </p>
        </div>
        <a href="{{ route('administradores.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
            Ir al listado de administradores
        </a>
    </div>

    <!-- TARJETA DEL USUARIO ACTUAL -->
    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center text-2xl font-bold">
                    🛡️
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ auth('admin')->user()?->nombre_completo ?? 'Administrador' }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Usuario: <code class="text-purple-600 font-semibold">{{ auth('admin')->user()?->username ?? 'admin' }}</code>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 text-sm font-bold rounded-full
                    {{ (auth('admin')->user()->nivel ?? 1) == 3 ? 'bg-purple-100 text-purple-800' : ((auth('admin')->user()->nivel ?? 1) == 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                    Nivel {{ auth('admin')->user()?->nivel ?? 1 }}
                </span>
            </div>
        </div>
    </div>

    <!-- TABLA DE DISTRIBUCIÓN DE NIVELES -->
    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            Matriz de Autorización por Niveles
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3">Nivel</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3 text-center">Consultar</th>
                        <th class="px-4 py-3 text-center">Crear</th>
                        <th class="px-4 py-3 text-center">Editar</th>
                        <th class="px-4 py-3 text-center">Eliminar</th>
                        <th class="px-4 py-3 text-center">Gestión Admins</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-gray-900">Nivel 1</td>
                        <td class="px-4 py-3 text-gray-700 font-medium">Consultor</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-red-500 font-bold">❌</td>
                        <td class="px-4 py-3 text-center text-red-500 font-bold">❌</td>
                        <td class="px-4 py-3 text-center text-red-500 font-bold">❌</td>
                        <td class="px-4 py-3 text-center text-red-500 font-bold">❌</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-gray-900">Nivel 2</td>
                        <td class="px-4 py-3 text-gray-700 font-medium">Editor</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-red-500 font-bold">❌</td>
                        <td class="px-4 py-3 text-center text-red-500 font-bold">❌</td>
                    </tr>
                    <tr class="hover:bg-gray-50 bg-purple-50/40">
                        <td class="px-4 py-3 font-bold text-purple-900">Nivel 3</td>
                        <td class="px-4 py-3 text-purple-900 font-bold">Superadmin</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">✅</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECCIÓN DE COMPROBACIÓN DE GATES ACTIVOS -->
    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-3">
            Comprobación de Operaciones Activas para tu Sesión
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            Demostración de directivas Blade <code>@@can</code> y <code>@@cannot</code> conectadas al servidor:
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Consultar -->
            <div class="p-4 border rounded-lg @can('consultar') bg-green-50 border-green-200 @else bg-gray-50 border-gray-200 @endcan">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-800">Gate: consultar</span>
                    @can('consultar')
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-200 text-green-800 rounded">Autorizado</span>
                    @endcan
                    @cannot('consultar')
                        <span class="px-2.5 py-1 text-xs font-bold bg-red-200 text-red-800 rounded">Denegado</span>
                    @endcannot
                </div>
            </div>

            <!-- Crear -->
            <div class="p-4 border rounded-lg @can('crear') bg-green-50 border-green-200 @else bg-gray-50 border-gray-200 @endcan">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-800">Gate: crear</span>
                    @can('crear')
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-200 text-green-800 rounded">Autorizado</span>
                    @endcan
                    @cannot('crear')
                        <span class="px-2.5 py-1 text-xs font-bold bg-red-200 text-red-800 rounded">Denegado</span>
                    @endcannot
                </div>
            </div>

            <!-- Editar -->
            <div class="p-4 border rounded-lg @can('editar') bg-green-50 border-green-200 @else bg-gray-50 border-gray-200 @endcan">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-800">Gate: editar</span>
                    @can('editar')
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-200 text-green-800 rounded">Autorizado</span>
                    @endcan
                    @cannot('editar')
                        <span class="px-2.5 py-1 text-xs font-bold bg-red-200 text-red-800 rounded">Denegado</span>
                    @endcannot
                </div>
            </div>

            <!-- Eliminar -->
            <div class="p-4 border rounded-lg @can('eliminar') bg-green-50 border-green-200 @else bg-gray-50 border-gray-200 @endcan">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-800">Gate: eliminar</span>
                    @can('eliminar')
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-200 text-green-800 rounded">Autorizado</span>
                    @endcan
                    @cannot('eliminar')
                        <span class="px-2.5 py-1 text-xs font-bold bg-red-200 text-red-800 rounded">Denegado</span>
                    @endcannot
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
