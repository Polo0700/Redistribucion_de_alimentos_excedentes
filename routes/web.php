<?php

use Illuminate\Support\Facades\Route;


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

Route::view('/roles', 'roles.index')
    ->name('roles.index');

Route::view('/roles/create', 'roles.create')
    ->name('roles.create');


/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/

Route::view('/usuarios', 'usuarios.index')
    ->name('usuarios.index');

Route::view('/usuarios/create', 'usuarios.create')
    ->name('usuarios.create');


/*
|--------------------------------------------------------------------------
| Cuentas de acceso
|--------------------------------------------------------------------------
*/

Route::view('/cuentas-acceso', 'cuentas-acceso.index')
    ->name('cuentas-acceso.index');

Route::view('/cuentas-acceso/create', 'cuentas-acceso.create')
    ->name('cuentas-acceso.create');


/*
|--------------------------------------------------------------------------
| Categorías
|--------------------------------------------------------------------------
*/

Route::view('/categorias', 'categorias.index')
    ->name('categorias.index');

Route::view('/categorias/create', 'categorias.create')
    ->name('categorias.create');


/*
|--------------------------------------------------------------------------
| Alimentos
|--------------------------------------------------------------------------
*/

Route::view('/alimentos', 'alimentos.index')
    ->name('alimentos.index');

Route::view('/alimentos/create', 'alimentos.create')
    ->name('alimentos.create');


/*
|--------------------------------------------------------------------------
| Donaciones
|--------------------------------------------------------------------------
*/

Route::view('/donaciones', 'donaciones.index')
    ->name('donaciones.index');

Route::view('/donaciones/create', 'donaciones.create')
    ->name('donaciones.create');


/*
|--------------------------------------------------------------------------
| Carritos
|--------------------------------------------------------------------------
*/

Route::view('/carritos', 'carritos.index')
    ->name('carritos.index');

Route::view('/carritos/create', 'carritos.create')
    ->name('carritos.create');


/*
|--------------------------------------------------------------------------
| Listas de deseos
|--------------------------------------------------------------------------
*/

Route::view('/listas-deseos', 'listas-deseos.index')
    ->name('listas-deseos.index');

Route::view('/listas-deseos/create', 'listas-deseos.create')
    ->name('listas-deseos.create');


/*
|--------------------------------------------------------------------------
| Solicitudes
|--------------------------------------------------------------------------
*/

Route::view('/solicitudes', 'solicitudes.index')
    ->name('solicitudes.index');

Route::view('/solicitudes/create', 'solicitudes.create')
    ->name('solicitudes.create');


/*
|--------------------------------------------------------------------------
| Entregas
|--------------------------------------------------------------------------
*/

Route::view('/entregas', 'entregas.index')
    ->name('entregas.index');

Route::view('/entregas/create', 'entregas.create')
    ->name('entregas.create');


/*
|--------------------------------------------------------------------------
| Acciones importantes
|--------------------------------------------------------------------------
*/

Route::view('/acciones-importantes', 'acciones-importantes.index')
    ->name('acciones.index');

Route::view('/acciones-importantes/create', 'acciones-importantes.create')
    ->name('acciones.create');