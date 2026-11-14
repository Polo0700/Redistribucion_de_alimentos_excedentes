<?php

use Illuminate\Support\Facades\Route;

// Raíz de la API: respuesta JSON informativa (API REST pura, sin vistas)
Route::get('/', function () {
    return response()->json([
        'message' => 'API Redistribución de Alimentos Excedentes. Usa POST /api/v1/admin/login para autenticarte.',
    ]);
});

Route::view('/cliente', 'cliente.login')->name('cliente.login.view');

Route::view('/cliente/panel', 'cliente.panel')->name('cliente.panel');
