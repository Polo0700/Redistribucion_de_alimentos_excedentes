<?php
//MORENO PEÑA JORGE ADRIAN
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('bienvenido');
});

Route::view('/admin', 'administradores');
Route::view('/cliente', 'cliente');
