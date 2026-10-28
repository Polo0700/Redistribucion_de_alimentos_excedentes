<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
            <span class="text-4xl">🚫</span>
        </div>

        <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider text-red-700 bg-red-100 rounded-full">
            Error 403 &bull; No Autorizado
        </span>

        <h1 class="text-3xl font-bold text-gray-900 mt-4 mb-2">
            Acceso Denegado
        </h1>

        <p class="text-gray-600 mb-6 leading-relaxed">
            Tu nivel de administrador actual 
            @auth('admin')
                <strong class="text-gray-900">(Nivel {{ auth('admin')->user()->nivel }})</strong>
            @endauth
            no cuenta con los privilegios suficientes para realizar esta operación.
        </p>

        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg text-left text-sm text-amber-800 mb-6">
            <p class="font-semibold mb-1">Restricción de seguridad activa:</p>
            <p>Las operaciones de creación, edición, eliminación y administración de cuentas están protegidas en el servidor mediante reglas de autorización (Gates).</p>
        </div>

        <div class="flex justify-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                Volver al Dashboard
            </a>
            <a href="javascript:history.back()"
               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Regresar a la página anterior
            </a>
        </div>
    </div>
</body>
</html>

