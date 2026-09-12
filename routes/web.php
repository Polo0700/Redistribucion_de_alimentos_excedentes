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

Route::view('/roles/create', 'roles.create')
    ->name('roles.create');


/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

Route::view('/usuarios/create', 'usuarios.create')
    ->name('usuarios.create');


/*
|--------------------------------------------------------------------------
| Cuentas de acceso
|--------------------------------------------------------------------------
*/

Route::get('/cuentas-acceso', [CuentaAccesoController::class, 'index'])->name('cuentas-acceso.index');

Route::view('/cuentas-acceso/create', 'cuentas-acceso.create')
    ->name('cuentas-acceso.create');


/*
|--------------------------------------------------------------------------
| Categorías
|--------------------------------------------------------------------------
*/

Route::get('/categorias', [CategoriaAlimentoController::class, 'index'])->name('categorias.index');

Route::view('/categorias/create', 'categorias.create')
    ->name('categorias.create');


/*
|--------------------------------------------------------------------------
| Alimentos
|--------------------------------------------------------------------------
*/

Route::get('/alimentos', [AlimentoController::class, 'index'])->name('alimentos.index');

Route::view('/alimentos/create', 'alimentos.create')
    ->name('alimentos.create');


/*
|--------------------------------------------------------------------------
| Donaciones
|--------------------------------------------------------------------------
*/

Route::get('/donaciones', [DonacionController::class, 'index'])->name('donaciones.index');

Route::view('/donaciones/create', 'donaciones.create')
    ->name('donaciones.create');


/*
|--------------------------------------------------------------------------
| Carritos
|--------------------------------------------------------------------------
*/

Route::get('/carritos', [CarritoController::class, 'index'])->name('carritos.index');

Route::view('/carritos/create', 'carritos.create')->name('carritos.create');

/*
|--------------------------------------------------------------------------
| Listas de deseos
|--------------------------------------------------------------------------
*/

Route::get('/listas-deseos', [ListaDeseoController::class, 'index'])->name('listas-deseos.index');

Route::view('/listas-deseos/create', 'listas-deseos.create')->name('listas-deseos.create');

/*
|--------------------------------------------------------------------------
| Solicitudes
|--------------------------------------------------------------------------
*/

Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');

Route::view('/solicitudes/create', 'solicitudes.create')->name('solicitudes.create');

/*
|--------------------------------------------------------------------------
| Entregas
|--------------------------------------------------------------------------
*/

Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');

Route::view('/entregas/create', 'entregas.create')->name('entregas.create');

/*
|--------------------------------------------------------------------------
| Acciones importantes
|--------------------------------------------------------------------------
*/

Route::get('/acciones-importantes', [AccionImportanteController::class, 'index'])->name('acciones.index');

Route::view('/acciones-importantes/create', 'acciones-importantes.create')->name('acciones.create');