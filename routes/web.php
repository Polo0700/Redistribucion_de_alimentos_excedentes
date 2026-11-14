<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas web
|--------------------------------------------------------------------------
| El proyecto es una API REST pura: no hay vistas ni rutas web para las
| entidades. Todas las rutas se definen en routes/api.php y están
| protegidas con Laravel Sanctum.
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'API Redistribución de Alimentos Excedentes. Usa POST /api/login para autenticarte.',
    ]);
});
