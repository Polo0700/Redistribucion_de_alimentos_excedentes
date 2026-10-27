<?php

use App\Http\Controllers\Api\AccionImportanteController as ApiAccionImportanteController;
use App\Http\Controllers\Api\AlimentoController as ApiAlimentoController;
use App\Http\Controllers\Api\CategoriaAlimentoController as ApiCategoriaAlimentoController;
use App\Http\Controllers\Api\CuentaAccesoController as ApiCuentaAccesoController;
use App\Http\Controllers\Api\DonacionController as ApiDonacionController;
use App\Http\Controllers\Api\EntregaController as ApiEntregaController;
use App\Http\Controllers\Api\RolController as ApiRolController;
use App\Http\Controllers\Api\SolicitudController as ApiSolicitudController;
use App\Http\Controllers\Api\UsuarioController as ApiUsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Roles
|--------------------------------------------------------------------------
*/

Route::get('/roles', [ApiRolController::class, 'index'])->name('api.roles.index');

Route::get('/roles/create', [ApiRolController::class, 'create'])->name('api.roles.create');

Route::post('/roles', [ApiRolController::class, 'store'])->name('api.roles.store');

Route::get('/roles/{id}/edit', [ApiRolController::class, 'edit'])->name('api.roles.edit');

Route::put('/roles/{id}', [ApiRolController::class, 'update'])->name('api.roles.update');

Route::get('/roles/eliminados', [ApiRolController::class, 'trashed'])->name('api.roles.trashed');

Route::get('/roles/{id}', [ApiRolController::class, 'show'])->name('api.roles.show');

Route::delete('/roles/{id}', [ApiRolController::class, 'destroy'])->name('api.roles.destroy');

Route::patch('/roles/{id}/restore', [ApiRolController::class, 'restore'])->name('api.roles.restore');

Route::delete('/roles/{id}/force', [ApiRolController::class, 'forceDestroy'])->name('api.roles.forceDestroy');

/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/

Route::get('/usuarios', [ApiUsuarioController::class, 'index'])->name('api.usuarios.index');

Route::get('/usuarios/create', [ApiUsuarioController::class, 'create'])->name('api.usuarios.create');

Route::post('/usuarios', [ApiUsuarioController::class, 'store'])->name('api.usuarios.store');

Route::get('/usuarios/{id}/edit', [ApiUsuarioController::class, 'edit'])->name('api.usuarios.edit');

Route::put('/usuarios/{id}', [ApiUsuarioController::class, 'update'])->name('api.usuarios.update');

Route::get('/usuarios/eliminados', [ApiUsuarioController::class, 'trashed'])->name('api.usuarios.trashed');

Route::get('/usuarios/{id}', [ApiUsuarioController::class, 'show'])->name('api.usuarios.show');

Route::delete('/usuarios/{id}', [ApiUsuarioController::class, 'destroy'])->name('api.usuarios.destroy');

Route::patch('/usuarios/{id}/restore', [ApiUsuarioController::class, 'restore'])->name('api.usuarios.restore');

Route::delete('/usuarios/{id}/force', [ApiUsuarioController::class, 'forceDestroy'])->name('api.usuarios.forceDestroy');

/*
|--------------------------------------------------------------------------
| Cuentas de acceso
|--------------------------------------------------------------------------
*/

Route::get('/cuentas-acceso', [ApiCuentaAccesoController::class, 'index'])->name('api.cuentas-acceso.index');

Route::get('/cuentas-acceso/create', [ApiCuentaAccesoController::class, 'create'])->name('api.cuentas-acceso.create');

Route::post('/cuentas-acceso', [ApiCuentaAccesoController::class, 'store'])->name('api.cuentas-acceso.store');

Route::get('/cuentas-acceso/{id}/edit', [ApiCuentaAccesoController::class, 'edit'])->name('api.cuentas-acceso.edit');

Route::put('/cuentas-acceso/{id}', [ApiCuentaAccesoController::class, 'update'])->name('api.cuentas-acceso.update');

Route::get('/cuentas-acceso/eliminados', [ApiCuentaAccesoController::class, 'trashed'])->name('api.cuentas-acceso.trashed');

Route::get('/cuentas-acceso/{id}', [ApiCuentaAccesoController::class, 'show'])->name('api.cuentas-acceso.show');

Route::delete('/cuentas-acceso/{id}', [ApiCuentaAccesoController::class, 'destroy'])->name('api.cuentas-acceso.destroy');

Route::patch('/cuentas-acceso/{id}/restore', [ApiCuentaAccesoController::class, 'restore'])->name('api.cuentas-acceso.restore');

Route::delete('/cuentas-acceso/{id}/force', [ApiCuentaAccesoController::class, 'forceDestroy'])->name('api.cuentas-acceso.forceDestroy');

/*
|--------------------------------------------------------------------------
| Categorías
|--------------------------------------------------------------------------
*/

Route::get('/categorias', [ApiCategoriaAlimentoController::class, 'index'])->name('api.categorias.index');

Route::get('/categorias/create', [ApiCategoriaAlimentoController::class, 'create'])->name('api.categorias.create');

Route::post('/categorias', [ApiCategoriaAlimentoController::class, 'store'])->name('api.categorias.store');

Route::get('/categorias/{id}/edit', [ApiCategoriaAlimentoController::class, 'edit'])->name('api.categorias.edit');

Route::put('/categorias/{id}', [ApiCategoriaAlimentoController::class, 'update'])->name('api.categorias.update');

Route::get('/categorias/eliminados', [ApiCategoriaAlimentoController::class, 'trashed'])->name('api.categorias.trashed');

Route::get('/categorias/{id}', [ApiCategoriaAlimentoController::class, 'show'])->name('api.categorias.show');

Route::delete('/categorias/{id}', [ApiCategoriaAlimentoController::class, 'destroy'])->name('api.categorias.destroy');

Route::patch('/categorias/{id}/restore', [ApiCategoriaAlimentoController::class, 'restore'])->name('api.categorias.restore');

Route::delete('/categorias/{id}/force', [ApiCategoriaAlimentoController::class, 'forceDestroy'])->name('api.categorias.forceDestroy');

/*
|--------------------------------------------------------------------------
| Alimentos
|--------------------------------------------------------------------------
*/

Route::get('/alimentos', [ApiAlimentoController::class, 'index'])->name('api.alimentos.index');

Route::get('/alimentos/create', [ApiAlimentoController::class, 'create'])->name('api.alimentos.create');

Route::post('/alimentos', [ApiAlimentoController::class, 'store'])->name('api.alimentos.store');

Route::get('/alimentos/{id}/edit', [ApiAlimentoController::class, 'edit'])->name('api.alimentos.edit');

Route::put('/alimentos/{id}', [ApiAlimentoController::class, 'update'])->name('api.alimentos.update');

Route::get('/alimentos/eliminados', [ApiAlimentoController::class, 'trashed'])->name('api.alimentos.trashed');

Route::get('/alimentos/{id}', [ApiAlimentoController::class, 'show'])->name('api.alimentos.show');

Route::delete('/alimentos/{id}', [ApiAlimentoController::class, 'destroy'])->name('api.alimentos.destroy');

Route::patch('/alimentos/{id}/restore', [ApiAlimentoController::class, 'restore'])->name('api.alimentos.restore');

Route::delete('/alimentos/{id}/force', [ApiAlimentoController::class, 'forceDestroy'])->name('api.alimentos.forceDestroy');

/*
|--------------------------------------------------------------------------
| Donaciones
|--------------------------------------------------------------------------
*/

Route::get('/donaciones', [ApiDonacionController::class, 'index'])->name('api.donaciones.index');

Route::get('/donaciones/create', [ApiDonacionController::class, 'create'])->name('api.donaciones.create');

Route::post('/donaciones', [ApiDonacionController::class, 'store'])->name('api.donaciones.store');

Route::get('/donaciones/{id}/edit', [ApiDonacionController::class, 'edit'])->name('api.donaciones.edit');

Route::put('/donaciones/{id}', [ApiDonacionController::class, 'update'])->name('api.donaciones.update');

Route::get('/donaciones/eliminados', [ApiDonacionController::class, 'trashed'])->name('api.donaciones.trashed');

Route::get('/donaciones/{id}', [ApiDonacionController::class, 'show'])->name('api.donaciones.show');

Route::delete('/donaciones/{id}', [ApiDonacionController::class, 'destroy'])->name('api.donaciones.destroy');

Route::patch('/donaciones/{id}/restore', [ApiDonacionController::class, 'restore'])->name('api.donaciones.restore');

Route::delete('/donaciones/{id}/force', [ApiDonacionController::class, 'forceDestroy'])->name('api.donaciones.forceDestroy');

/*
|--------------------------------------------------------------------------
| Solicitudes
|--------------------------------------------------------------------------
*/

Route::get('/solicitudes', [ApiSolicitudController::class, 'index'])->name('api.solicitudes.index');

Route::get('/solicitudes/create', [ApiSolicitudController::class, 'create'])->name('api.solicitudes.create');

Route::post('/solicitudes', [ApiSolicitudController::class, 'store'])->name('api.solicitudes.store');

Route::get('/solicitudes/{id}/edit', [ApiSolicitudController::class, 'edit'])->name('api.solicitudes.edit');

Route::put('/solicitudes/{id}', [ApiSolicitudController::class, 'update'])->name('api.solicitudes.update');

Route::get('/solicitudes/eliminados', [ApiSolicitudController::class, 'trashed'])->name('api.solicitudes.trashed');

Route::get('/solicitudes/{id}', [ApiSolicitudController::class, 'show'])->name('api.solicitudes.show');

Route::delete('/solicitudes/{id}', [ApiSolicitudController::class, 'destroy'])->name('api.solicitudes.destroy');

Route::patch('/solicitudes/{id}/restore', [ApiSolicitudController::class, 'restore'])->name('api.solicitudes.restore');

Route::delete('/solicitudes/{id}/force', [ApiSolicitudController::class, 'forceDestroy'])->name('api.solicitudes.forceDestroy');

/*
|--------------------------------------------------------------------------
| Entregas
|--------------------------------------------------------------------------
*/

Route::get('/entregas', [ApiEntregaController::class, 'index'])->name('api.entregas.index');

Route::get('/entregas/create', [ApiEntregaController::class, 'create'])->name('api.entregas.create');

Route::post('/entregas', [ApiEntregaController::class, 'store'])->name('api.entregas.store');

Route::get('/entregas/{id}/edit', [ApiEntregaController::class, 'edit'])->name('api.entregas.edit');

Route::put('/entregas/{id}', [ApiEntregaController::class, 'update'])->name('api.entregas.update');

Route::get('/entregas/eliminados', [ApiEntregaController::class, 'trashed'])->name('api.entregas.trashed');

Route::get('/entregas/{id}', [ApiEntregaController::class, 'show'])->name('api.entregas.show');

Route::delete('/entregas/{id}', [ApiEntregaController::class, 'destroy'])->name('api.entregas.destroy');

Route::patch('/entregas/{id}/restore', [ApiEntregaController::class, 'restore'])->name('api.entregas.restore');

Route::delete('/entregas/{id}/force', [ApiEntregaController::class, 'forceDestroy'])->name('api.entregas.forceDestroy');

/*
|--------------------------------------------------------------------------
| Acciones importantes
|--------------------------------------------------------------------------
*/

Route::get('/acciones-importantes', [ApiAccionImportanteController::class, 'index'])->name('api.acciones.index');

Route::get('/acciones-importantes/create', [ApiAccionImportanteController::class, 'create'])->name('api.acciones.create');

Route::post('/acciones-importantes', [ApiAccionImportanteController::class, 'store'])->name('api.acciones.store');

Route::get('/acciones-importantes/{id}/edit', [ApiAccionImportanteController::class, 'edit'])->name('api.acciones.edit');

Route::put('/acciones-importantes/{id}', [ApiAccionImportanteController::class, 'update'])->name('api.acciones.update');

Route::get('/acciones-importantes/eliminados', [ApiAccionImportanteController::class, 'trashed'])->name('api.acciones.trashed');

Route::get('/acciones-importantes/{id}', [ApiAccionImportanteController::class, 'show'])->name('api.acciones.show');

Route::delete('/acciones-importantes/{id}', [ApiAccionImportanteController::class, 'destroy'])->name('api.acciones.destroy');

Route::patch('/acciones-importantes/{id}/restore', [ApiAccionImportanteController::class, 'restore'])->name('api.acciones.restore');

Route::delete('/acciones-importantes/{id}/force', [ApiAccionImportanteController::class, 'forceDestroy'])->name('api.acciones.forceDestroy');
