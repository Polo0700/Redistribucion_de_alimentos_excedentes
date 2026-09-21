<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CuentaAccesoController;
use App\Http\Controllers\CategoriaAlimentoController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ListaDeseoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\AccionImportanteController;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::view('/dashboard', 'dashboard')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Roles
|--------------------------------------------------------------------------
*/

Route::get('/roles', [RolController::class, 'index'])->name('roles.index');

Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');

Route::post('/roles', [RolController::class, 'store'])->name('roles.store');

Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');

Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');

Route::get('/roles/eliminados', [RolController::class, 'trashed'])->name('roles.trashed');

Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show');

Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');

Route::patch('/roles/{id}/restore', [RolController::class, 'restore'])->name('roles.restore');

Route::delete('/roles/{id}/force', [RolController::class, 'forceDestroy'])->name('roles.forceDestroy');

/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');

Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');

Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

Route::get('/usuarios/eliminados', [UsuarioController::class, 'trashed'])->name('usuarios.trashed');

Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');

Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

Route::patch('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');

Route::delete('/usuarios/{id}/force', [UsuarioController::class, 'forceDestroy'])->name('usuarios.forceDestroy');


/*
|--------------------------------------------------------------------------
| Cuentas de acceso
|--------------------------------------------------------------------------
*/

Route::get('/cuentas-acceso', [CuentaAccesoController::class, 'index'])->name('cuentas-acceso.index');

Route::get('/cuentas-acceso/create', [CuentaAccesoController::class, 'create'])->name('cuentas-acceso.create');

Route::post('/cuentas-acceso', [CuentaAccesoController::class, 'store'])->name('cuentas-acceso.store');

Route::get('/cuentas-acceso/{id}/edit', [CuentaAccesoController::class, 'edit'])->name('cuentas-acceso.edit');

Route::put('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'update'])->name('cuentas-acceso.update');

Route::get('/cuentas-acceso/eliminados', [CuentaAccesoController::class, 'trashed'])->name('cuentas-acceso.trashed');

Route::get('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'show'])->name('cuentas-acceso.show');

Route::delete('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'destroy'])->name('cuentas-acceso.destroy');

Route::patch('/cuentas-acceso/{id}/restore', [CuentaAccesoController::class, 'restore'])->name('cuentas-acceso.restore');

Route::delete('/cuentas-acceso/{id}/force', [CuentaAccesoController::class, 'forceDestroy'])->name('cuentas-acceso.forceDestroy');


/*
|--------------------------------------------------------------------------
| Categorías
|--------------------------------------------------------------------------
*/

Route::get('/categorias', [CategoriaAlimentoController::class, 'index'])->name('categorias.index');

Route::get('/categorias/create', [CategoriaAlimentoController::class, 'create'])->name('categorias.create');

Route::post('/categorias', [CategoriaAlimentoController::class, 'store'])->name('categorias.store');

Route::get('/categorias/{id}/edit', [CategoriaAlimentoController::class, 'edit'])->name('categorias.edit');

Route::put('/categorias/{id}', [CategoriaAlimentoController::class, 'update'])->name('categorias.update');

Route::get('/categorias/eliminados', [CategoriaAlimentoController::class, 'trashed'])->name('categorias.trashed');

Route::get('/categorias/{id}', [CategoriaAlimentoController::class, 'show'])->name('categorias.show');

Route::delete('/categorias/{id}', [CategoriaAlimentoController::class, 'destroy'])->name('categorias.destroy');

Route::patch('/categorias/{id}/restore', [CategoriaAlimentoController::class, 'restore'])->name('categorias.restore');

Route::delete('/categorias/{id}/force', [CategoriaAlimentoController::class, 'forceDestroy'])->name('categorias.forceDestroy');


/*
|--------------------------------------------------------------------------
| Alimentos
|--------------------------------------------------------------------------
*/

Route::get('/alimentos', [AlimentoController::class, 'index'])->name('alimentos.index');

Route::get('/alimentos/eliminados', [AlimentoController::class, 'trashed'])->name('alimentos.trashed');

Route::get('/alimentos/create', [AlimentoController::class, 'create'])->name('alimentos.create');

Route::post('/alimentos', [AlimentoController::class, 'store'])->name('alimentos.store');

Route::get('/alimentos/{id}', [AlimentoController::class, 'show'])->name('alimentos.show');

Route::get('/alimentos/{id}/edit', [AlimentoController::class, 'edit'])->name('alimentos.edit');

Route::put('/alimentos/{id}', [AlimentoController::class, 'update'])->name('alimentos.update');

Route::delete('/alimentos/{id}', [AlimentoController::class, 'destroy'])->name('alimentos.destroy');

Route::patch('/alimentos/{id}/restore', [AlimentoController::class, 'restore'])->name('alimentos.restore');

Route::delete('/alimentos/{id}/force', [AlimentoController::class, 'forceDestroy'])->name('alimentos.forceDestroy');


/*
|--------------------------------------------------------------------------
| Donaciones
|--------------------------------------------------------------------------
*/

Route::get('/donaciones', [DonacionController::class, 'index'])->name('donaciones.index');

Route::get('/donaciones/create', [DonacionController::class, 'create'])->name('donaciones.create');

Route::post('/donaciones', [DonacionController::class, 'store'])->name('donaciones.store');

Route::get('/donaciones/{id}/edit', [DonacionController::class, 'edit']) ->name('donaciones.edit');

Route::put('/donaciones/{id}', [DonacionController::class, 'update'])->name('donaciones.update');

Route::get('/donaciones/eliminados', [DonacionController::class, 'trashed'])->name('donaciones.trashed');

Route::get('/donaciones/{id}', [DonacionController::class, 'show'])->name('donaciones.show');

Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy'])->name('donaciones.destroy');

Route::patch('/donaciones/{id}/restore', [DonacionController::class, 'restore'])->name('donaciones.restore');

Route::delete('/donaciones/{id}/force', [DonacionController::class, 'forceDestroy'])->name('donaciones.forceDestroy');


/*
|--------------------------------------------------------------------------
| Carritos
|--------------------------------------------------------------------------
*/

Route::get('/carritos', [CarritoController::class, 'index'])->name('carritos.index');

Route::get('/carritos/create', [CarritoController::class, 'create'])->name('carritos.create');

Route::post('/carritos', [CarritoController::class, 'store'])->name('carritos.store');

Route::get('/carritos/{id}/edit', [CarritoController::class, 'edit'])->name('carritos.edit');

Route::put('/carritos/{id}', [CarritoController::class, 'update'])->name('carritos.update');

Route::get('/carritos/eliminados', [CarritoController::class, 'trashed'])->name('carritos.trashed');

Route::get('/carritos/{id}', [CarritoController::class, 'show'])->name('carritos.show');

Route::delete('/carritos/{id}', [CarritoController::class, 'destroy'])->name('carritos.destroy');

Route::patch('/carritos/{id}/restore', [CarritoController::class, 'restore'])->name('carritos.restore');

Route::delete('/carritos/{id}/force', [CarritoController::class, 'forceDestroy'])->name('carritos.forceDestroy');


/*
|--------------------------------------------------------------------------
| Listas de deseos
|--------------------------------------------------------------------------
*/

Route::get('/listas-deseos', [ListaDeseoController::class, 'index'])->name('listas-deseos.index');

Route::get('/listas-deseos/create', [ListaDeseoController::class, 'create'])->name('listas-deseos.create');

Route::post('/listas-deseos', [ListaDeseoController::class, 'store'])->name('listas-deseos.store');

Route::get('/listas-deseos/{id}/edit', [ListaDeseoController::class, 'edit'])->name('listas-deseos.edit');

Route::put('/listas-deseos/{id}', [ListaDeseoController::class, 'update'])->name('listas-deseos.update');

Route::get('/listas-deseos/eliminados', [ListaDeseoController::class, 'trashed'])->name('listas-deseos.trashed');

Route::get('/listas-deseos/{id}', [ListaDeseoController::class, 'show'])->name('listas-deseos.show');

Route::delete('/listas-deseos/{id}', [ListaDeseoController::class, 'destroy'])->name('listas-deseos.destroy');

Route::patch('/listas-deseos/{id}/restore', [ListaDeseoController::class, 'restore'])->name('listas-deseos.restore');

Route::delete('/listas-deseos/{id}/force', [ListaDeseoController::class, 'forceDestroy'])->name('listas-deseos.forceDestroy');


/*
|--------------------------------------------------------------------------
| Solicitudes
|--------------------------------------------------------------------------
*/

Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');

Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');

Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');

Route::get('/solicitudes/{id}/edit', [SolicitudController::class, 'edit']) ->name('solicitudes.edit');

Route::put('/solicitudes/{id}', [SolicitudController::class, 'update']) ->name('solicitudes.update');

Route::get('/solicitudes/eliminados', [SolicitudController::class, 'trashed'])->name('solicitudes.trashed');

Route::get('/solicitudes/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show');

Route::delete('/solicitudes/{id}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy');

Route::patch('/solicitudes/{id}/restore', [SolicitudController::class, 'restore'])->name('solicitudes.restore');

Route::delete('/solicitudes/{id}/force', [SolicitudController::class, 'forceDestroy'])->name('solicitudes.forceDestroy');


/*
|--------------------------------------------------------------------------
| Entregas
|--------------------------------------------------------------------------
*/

Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');

Route::get('/entregas/create', [EntregaController::class, 'create'])->name('entregas.create');

Route::post('/entregas', [EntregaController::class, 'store'])->name('entregas.store');

Route::get('/entregas/{id}/edit', [EntregaController::class, 'edit'])->name('entregas.edit');

Route::put('/entregas/{id}', [EntregaController::class, 'update'])->name('entregas.update');

Route::get('/entregas/eliminados', [EntregaController::class, 'trashed'])->name('entregas.trashed');

Route::get('/entregas/{id}', [EntregaController::class, 'show'])->name('entregas.show');

Route::delete('/entregas/{id}', [EntregaController::class, 'destroy'])->name('entregas.destroy');

Route::patch('/entregas/{id}/restore', [EntregaController::class, 'restore'])->name('entregas.restore');

Route::delete('/entregas/{id}/force', [EntregaController::class, 'forceDestroy'])->name('entregas.forceDestroy');


/*
|--------------------------------------------------------------------------
| Acciones importantes
|--------------------------------------------------------------------------
*/

Route::get('/acciones-importantes', [AccionImportanteController::class, 'index'])->name('acciones.index');

Route::get('/acciones-importantes/create', [AccionImportanteController::class, 'create'])->name('acciones.create');

Route::post('/acciones-importantes', [AccionImportanteController::class, 'store'])->name('acciones.store');

Route::get('/acciones-importantes/{id}/edit', [AccionImportanteController::class, 'edit'])->name('acciones.edit');

Route::put('/acciones-importantes/{id}', [AccionImportanteController::class, 'update'])->name('acciones.update');

Route::get('/acciones-importantes/eliminados', [AccionImportanteController::class, 'trashed'])->name('acciones.trashed');

Route::get('/acciones-importantes/{id}', [AccionImportanteController::class, 'show'])->name('acciones.show');

Route::delete('/acciones-importantes/{id}', [AccionImportanteController::class, 'destroy'])->name('acciones.destroy');

Route::patch('/acciones-importantes/{id}/restore', [AccionImportanteController::class, 'restore'])->name('acciones.restore');

Route::delete('/acciones-importantes/{id}/force', [AccionImportanteController::class, 'forceDestroy'])->name('acciones.forceDestroy');