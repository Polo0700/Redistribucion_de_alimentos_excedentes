<?php

use App\Http\Controllers\AccionImportanteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CategoriaAlimentoController;
use App\Http\Controllers\CuentaAccesoController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|-----------------------------------------------
| api del panel (token sanctum obligatorio)
|-----------------------------------------------
*/

// login y logout del api con token
Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::delete('/auth/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    // rutas de roles
    Route::get('/roles', [RolController::class, 'index']);
    Route::post('/roles', [RolController::class, 'store']);
    Route::get('/roles/create', [RolController::class, 'create']);
    Route::get('/roles/eliminados', [RolController::class, 'trashed']);
    Route::get('/roles/{id}/edit', [RolController::class, 'edit']);
    Route::put('/roles/{id}', [RolController::class, 'update']);
    Route::patch('/roles/{id}/restore', [RolController::class, 'restore']);
    Route::delete('/roles/{id}/force', [RolController::class, 'forceDestroy']);
    Route::delete('/roles/{id}', [RolController::class, 'destroy']);
    Route::get('/roles/{id}', [RolController::class, 'show']);

    // rutas de usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::get('/usuarios/create', [UsuarioController::class, 'create']);
    Route::get('/usuarios/eliminados', [UsuarioController::class, 'trashed']);
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit']);
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::patch('/usuarios/{id}/restore', [UsuarioController::class, 'restore']);
    Route::delete('/usuarios/{id}/force', [UsuarioController::class, 'forceDestroy']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);

    // rutas de cuentas de acceso
    Route::get('/cuentas-acceso', [CuentaAccesoController::class, 'index']);
    Route::post('/cuentas-acceso', [CuentaAccesoController::class, 'store']);
    Route::get('/cuentas-acceso/create', [CuentaAccesoController::class, 'create']);
    Route::get('/cuentas-acceso/eliminados', [CuentaAccesoController::class, 'trashed']);
    Route::get('/cuentas-acceso/{id}/edit', [CuentaAccesoController::class, 'edit']);
    Route::put('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'update']);
    Route::patch('/cuentas-acceso/{id}/restore', [CuentaAccesoController::class, 'restore']);
    Route::delete('/cuentas-acceso/{id}/force', [CuentaAccesoController::class, 'forceDestroy']);
    Route::delete('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'destroy']);
    Route::get('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'show']);

    // rutas de categorias
    Route::get('/categorias', [CategoriaAlimentoController::class, 'index']);
    Route::post('/categorias', [CategoriaAlimentoController::class, 'store']);
    Route::get('/categorias/create', [CategoriaAlimentoController::class, 'create']);
    Route::get('/categorias/eliminados', [CategoriaAlimentoController::class, 'trashed']);
    Route::get('/categorias/{id}/edit', [CategoriaAlimentoController::class, 'edit']);
    Route::put('/categorias/{id}', [CategoriaAlimentoController::class, 'update']);
    Route::patch('/categorias/{id}/restore', [CategoriaAlimentoController::class, 'restore']);
    Route::delete('/categorias/{id}/force', [CategoriaAlimentoController::class, 'forceDestroy']);
    Route::delete('/categorias/{id}', [CategoriaAlimentoController::class, 'destroy']);
    Route::get('/categorias/{id}', [CategoriaAlimentoController::class, 'show']);

    // rutas de alimentos
    Route::get('/alimentos', [AlimentoController::class, 'index']);
    Route::post('/alimentos', [AlimentoController::class, 'store']);
    Route::get('/alimentos/create', [AlimentoController::class, 'create']);
    Route::get('/alimentos/eliminados', [AlimentoController::class, 'trashed']);
    Route::get('/alimentos/{id}/edit', [AlimentoController::class, 'edit']);
    Route::put('/alimentos/{id}', [AlimentoController::class, 'update']);
    Route::patch('/alimentos/{id}/restore', [AlimentoController::class, 'restore']);
    Route::delete('/alimentos/{id}/force', [AlimentoController::class, 'forceDestroy']);
    Route::delete('/alimentos/{id}', [AlimentoController::class, 'destroy']);
    Route::get('/alimentos/{id}', [AlimentoController::class, 'show']);

    // rutas de donaciones
    Route::get('/donaciones', [DonacionController::class, 'index']);
    Route::post('/donaciones', [DonacionController::class, 'store']);
    Route::get('/donaciones/create', [DonacionController::class, 'create']);
    Route::get('/donaciones/eliminados', [DonacionController::class, 'trashed']);
    Route::get('/donaciones/{id}/edit', [DonacionController::class, 'edit']);
    Route::put('/donaciones/{id}', [DonacionController::class, 'update']);
    Route::patch('/donaciones/{id}/restore', [DonacionController::class, 'restore']);
    Route::delete('/donaciones/{id}/force', [DonacionController::class, 'forceDestroy']);
    Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy']);
    Route::get('/donaciones/{id}', [DonacionController::class, 'show']);

    // rutas de solicitudes
    Route::get('/solicitudes', [SolicitudController::class, 'index']);
    Route::post('/solicitudes', [SolicitudController::class, 'store']);
    Route::get('/solicitudes/create', [SolicitudController::class, 'create']);
    Route::get('/solicitudes/eliminados', [SolicitudController::class, 'trashed']);
    Route::get('/solicitudes/{id}/edit', [SolicitudController::class, 'edit']);
    Route::put('/solicitudes/{id}', [SolicitudController::class, 'update']);
    Route::patch('/solicitudes/{id}/restore', [SolicitudController::class, 'restore']);
    Route::delete('/solicitudes/{id}/force', [SolicitudController::class, 'forceDestroy']);
    Route::delete('/solicitudes/{id}', [SolicitudController::class, 'destroy']);
    Route::get('/solicitudes/{id}', [SolicitudController::class, 'show']);

    // rutas de entregas
    Route::get('/entregas', [EntregaController::class, 'index']);
    Route::post('/entregas', [EntregaController::class, 'store']);
    Route::get('/entregas/create', [EntregaController::class, 'create']);
    Route::get('/entregas/eliminados', [EntregaController::class, 'trashed']);
    Route::get('/entregas/{id}/edit', [EntregaController::class, 'edit']);
    Route::put('/entregas/{id}', [EntregaController::class, 'update']);
    Route::patch('/entregas/{id}/restore', [EntregaController::class, 'restore']);
    Route::delete('/entregas/{id}/force', [EntregaController::class, 'forceDestroy']);
    Route::delete('/entregas/{id}', [EntregaController::class, 'destroy']);
    Route::get('/entregas/{id}', [EntregaController::class, 'show']);

    // rutas de acciones importantes
    Route::get('/acciones-importantes', [AccionImportanteController::class, 'index']);
    Route::post('/acciones-importantes', [AccionImportanteController::class, 'store']);
    Route::get('/acciones-importantes/create', [AccionImportanteController::class, 'create']);
    Route::get('/acciones-importantes/eliminados', [AccionImportanteController::class, 'trashed']);
    Route::get('/acciones-importantes/{id}/edit', [AccionImportanteController::class, 'edit']);
    Route::put('/acciones-importantes/{id}', [AccionImportanteController::class, 'update']);
    Route::patch('/acciones-importantes/{id}/restore', [AccionImportanteController::class, 'restore']);
    Route::delete('/acciones-importantes/{id}/force', [AccionImportanteController::class, 'forceDestroy']);
    Route::delete('/acciones-importantes/{id}', [AccionImportanteController::class, 'destroy']);
    Route::get('/acciones-importantes/{id}', [AccionImportanteController::class, 'show']);

    // rutas de administradores (solo nivel 3)
    Route::get('/administradores', [AdministradorController::class, 'index']);
    Route::post('/administradores', [AdministradorController::class, 'store']);
    Route::get('/administradores/create', [AdministradorController::class, 'create']);
    Route::get('/administradores/eliminados', [AdministradorController::class, 'trashed']);
    Route::get('/administradores/{id}/edit', [AdministradorController::class, 'edit']);
    Route::put('/administradores/{id}', [AdministradorController::class, 'update']);
    Route::patch('/administradores/{id}/restore', [AdministradorController::class, 'restore']);
    Route::delete('/administradores/{id}/force', [AdministradorController::class, 'forceDestroy']);
    Route::delete('/administradores/{id}', [AdministradorController::class, 'destroy']);
    Route::get('/administradores/{id}', [AdministradorController::class, 'show']);

}); // fin auth:sanctum
