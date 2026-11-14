<?php

use App\Http\Controllers\AccionImportanteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaAlimentoController;
use App\Http\Controllers\CuentaAccesoController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA: autenticación con Sanctum
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS POR SANCTUM
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // cierre de sesión (revoca el token actual)
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    /*
    |--------------------------------------------------------------------------
    | Administradores
    |--------------------------------------------------------------------------
    */

    Route::get('/administradores/trashed', [AdministradorController::class, 'trashed'])->name('administradores.trashed');

    Route::apiResource('administradores', AdministradorController::class);

    Route::patch('/administradores/{id}/restore', [AdministradorController::class, 'restore'])->name('administradores.restore');

    Route::delete('/administradores/{id}/force', [AdministradorController::class, 'forceDestroy'])->name('administradores.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    Route::get('/roles/trashed', [RolController::class, 'trashed'])->name('roles.trashed');

    Route::apiResource('roles', RolController::class);

    Route::patch('/roles/{id}/restore', [RolController::class, 'restore'])->name('roles.restore');

    Route::delete('/roles/{id}/force', [RolController::class, 'forceDestroy'])->name('roles.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Usuarios
    |--------------------------------------------------------------------------
    */

    Route::get('/usuarios/trashed', [UsuarioController::class, 'trashed'])->name('usuarios.trashed');

    Route::apiResource('usuarios', UsuarioController::class);

    Route::patch('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');

    Route::delete('/usuarios/{id}/force', [UsuarioController::class, 'forceDestroy'])->name('usuarios.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Cuentas de acceso
    |--------------------------------------------------------------------------
    */

    Route::get('/cuentas-acceso/trashed', [CuentaAccesoController::class, 'trashed'])->name('cuentas-acceso.trashed');

    Route::apiResource('cuentas-acceso', CuentaAccesoController::class);

    Route::patch('/cuentas-acceso/{id}/restore', [CuentaAccesoController::class, 'restore'])->name('cuentas-acceso.restore');

    Route::delete('/cuentas-acceso/{id}/force', [CuentaAccesoController::class, 'forceDestroy'])->name('cuentas-acceso.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Categorías de alimentos
    |--------------------------------------------------------------------------
    */

    Route::get('/categorias/trashed', [CategoriaAlimentoController::class, 'trashed'])->name('categorias.trashed');

    Route::apiResource('categorias', CategoriaAlimentoController::class);

    Route::patch('/categorias/{id}/restore', [CategoriaAlimentoController::class, 'restore'])->name('categorias.restore');

    Route::delete('/categorias/{id}/force', [CategoriaAlimentoController::class, 'forceDestroy'])->name('categorias.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Alimentos
    |--------------------------------------------------------------------------
    */

    Route::get('/alimentos/trashed', [AlimentoController::class, 'trashed'])->name('alimentos.trashed');

    Route::apiResource('alimentos', AlimentoController::class);

    Route::patch('/alimentos/{id}/restore', [AlimentoController::class, 'restore'])->name('alimentos.restore');

    Route::delete('/alimentos/{id}/force', [AlimentoController::class, 'forceDestroy'])->name('alimentos.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Donaciones
    |--------------------------------------------------------------------------
    */

    Route::get('/donaciones/trashed', [DonacionController::class, 'trashed'])->name('donaciones.trashed');

    Route::apiResource('donaciones', DonacionController::class);

    Route::patch('/donaciones/{id}/restore', [DonacionController::class, 'restore'])->name('donaciones.restore');

    Route::delete('/donaciones/{id}/force', [DonacionController::class, 'forceDestroy'])->name('donaciones.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Solicitudes
    |--------------------------------------------------------------------------
    */

    Route::get('/solicitudes/trashed', [SolicitudController::class, 'trashed'])->name('solicitudes.trashed');

    Route::apiResource('solicitudes', SolicitudController::class);

    Route::patch('/solicitudes/{id}/restore', [SolicitudController::class, 'restore'])->name('solicitudes.restore');

    Route::delete('/solicitudes/{id}/force', [SolicitudController::class, 'forceDestroy'])->name('solicitudes.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Entregas
    |--------------------------------------------------------------------------
    */

    Route::get('/entregas/trashed', [EntregaController::class, 'trashed'])->name('entregas.trashed');

    Route::apiResource('entregas', EntregaController::class);

    Route::patch('/entregas/{id}/restore', [EntregaController::class, 'restore'])->name('entregas.restore');

    Route::delete('/entregas/{id}/force', [EntregaController::class, 'forceDestroy'])->name('entregas.forceDestroy');

    /*
    |--------------------------------------------------------------------------
    | Acciones importantes
    |--------------------------------------------------------------------------
    */

    Route::get('/acciones-importantes/trashed', [AccionImportanteController::class, 'trashed'])->name('acciones.trashed');

    Route::apiResource('acciones-importantes', AccionImportanteController::class);

    Route::patch('/acciones-importantes/{id}/restore', [AccionImportanteController::class, 'restore'])->name('acciones.restore');

    Route::delete('/acciones-importantes/{id}/force', [AccionImportanteController::class, 'forceDestroy'])->name('acciones.forceDestroy');
});
