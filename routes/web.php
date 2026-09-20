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


/*
|--------------------------------------------------------------------------
| Alimentos
|--------------------------------------------------------------------------
*/

Route::get('/alimentos', [AlimentoController::class, 'index'])->name('alimentos.index');

Route::get('/alimentos/create', [AlimentoController::class, 'create'])->name('alimentos.create');

Route::post('/alimentos', [AlimentoController::class, 'store'])->name('alimentos.store');

Route::get('/alimentos/{id}/edit', [AlimentoController::class, 'edit'])->name('alimentos.edit');

Route::put('/alimentos/{id}', [AlimentoController::class, 'update'])->name('alimentos.update');


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