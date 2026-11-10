<?php

use Illuminate\Support\Facades\Route;

Route::view('/cliente', 'cliente.login')->name('cliente.login.view');

Route::view('/cliente/panel', 'cliente.panel')->name('cliente.panel');
