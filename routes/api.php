<?php

use App\Http\Controllers\AccionImportanteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\CategoriaAlimentoController;
use App\Http\Controllers\CuentaAccesoController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthClienteController;
use App\Http\Controllers\ClienteDonacionController;
use App\Http\Controllers\ClienteSolicitudController;
use App\Http\Middleware\EnsureEsAdmin;
use App\Http\Middleware\EnsureEsCliente;
use Illuminate\Support\Facades\Route;

// Raíz de la API: respuesta JSON informativa (API REST pura, sin vistas)
Route::get('/', function () {
    return response()->json([
        'message' => 'API Redistribución de Alimentos Excedentes. Usa POST /api/v1/admin/login para autenticarte.',
        'endpoints' => [
            'login' => 'POST /api/v1/admin/login',
            'logout' => 'POST /api/v1/admin/logout',
            'recursos' => 'GET /api/v1/admin/{recursos}',
        ],
    ]);
});

Route::prefix('v1/admin')->group(function () {

    Route::get('/login', [AuthAdminController::class, 'login'])->name('admin.login');

    Route::post('/login', [AuthAdminController::class, 'in'])->name('admin.in');

    Route::post('/logout', [AuthAdminController::class, 'out'])->middleware('auth:sanctum')->name('admin.logout');

    Route::middleware(['auth:sanctum', EnsureEsAdmin::class])->group(function () {

        Route::get('/administradores', [AdministradorController::class, 'index'])->name('administradores.index');
        Route::post('/administradores', [AdministradorController::class, 'store'])->name('administradores.store');
        Route::get('/administradores/eliminados', [AdministradorController::class, 'trashed'])->name('administradores.trashed');
        Route::put('/administradores/{id}', [AdministradorController::class, 'update'])->name('administradores.update');
        Route::patch('/administradores/{id}/restore', [AdministradorController::class, 'restore'])->name('administradores.restore');
        Route::delete('/administradores/{id}/force', [AdministradorController::class, 'forceDestroy'])->name('administradores.forceDestroy');
        Route::get('/administradores/{id}', [AdministradorController::class, 'show'])->name('administradores.show');
        Route::delete('/administradores/{id}', [AdministradorController::class, 'destroy'])->name('administradores.destroy');

        Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
        Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
        Route::get('/roles/eliminados', [RolController::class, 'trashed'])->name('roles.trashed');
        Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show');
        Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
        Route::patch('/roles/{id}/restore', [RolController::class, 'restore'])->name('roles.restore');
        Route::delete('/roles/{id}/force', [RolController::class, 'forceDestroy'])->name('roles.forceDestroy');

        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
        Route::get('/usuarios/eliminados', [UsuarioController::class, 'trashed'])->name('usuarios.trashed');
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::patch('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
        Route::delete('/usuarios/{id}/force', [UsuarioController::class, 'forceDestroy'])->name('usuarios.forceDestroy');

        Route::get('/cuentas-acceso', [CuentaAccesoController::class, 'index'])->name('cuentas-acceso.index');
        Route::post('/cuentas-acceso', [CuentaAccesoController::class, 'store'])->name('cuentas-acceso.store');
        Route::put('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'update'])->name('cuentas-acceso.update');
        Route::get('/cuentas-acceso/eliminados', [CuentaAccesoController::class, 'trashed'])->name('cuentas-acceso.trashed');
        Route::get('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'show'])->name('cuentas-acceso.show');
        Route::delete('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'destroy'])->name('cuentas-acceso.destroy');
        Route::patch('/cuentas-acceso/{id}/restore', [CuentaAccesoController::class, 'restore'])->name('cuentas-acceso.restore');
        Route::delete('/cuentas-acceso/{id}/force', [CuentaAccesoController::class, 'forceDestroy'])->name('cuentas-acceso.forceDestroy');

        Route::get('/categorias', [CategoriaAlimentoController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaAlimentoController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{id}', [CategoriaAlimentoController::class, 'update'])->name('categorias.update');
        Route::get('/categorias/eliminados', [CategoriaAlimentoController::class, 'trashed'])->name('categorias.trashed');
        Route::get('/categorias/{id}', [CategoriaAlimentoController::class, 'show'])->name('categorias.show');
        Route::delete('/categorias/{id}', [CategoriaAlimentoController::class, 'destroy'])->name('categorias.destroy');
        Route::patch('/categorias/{id}/restore', [CategoriaAlimentoController::class, 'restore'])->name('categorias.restore');
        Route::delete('/categorias/{id}/force', [CategoriaAlimentoController::class, 'forceDestroy'])->name('categorias.forceDestroy');

        Route::get('/alimentos', [AlimentoController::class, 'index'])->name('alimentos.index');
        Route::get('/alimentos/eliminados', [AlimentoController::class, 'trashed'])->name('alimentos.trashed');
        Route::post('/alimentos', [AlimentoController::class, 'store'])->name('alimentos.store');
        Route::get('/alimentos/{id}', [AlimentoController::class, 'show'])->name('alimentos.show');
        Route::put('/alimentos/{id}', [AlimentoController::class, 'update'])->name('alimentos.update');
        Route::delete('/alimentos/{id}', [AlimentoController::class, 'destroy'])->name('alimentos.destroy');
        Route::patch('/alimentos/{id}/restore', [AlimentoController::class, 'restore'])->name('alimentos.restore');
        Route::delete('/alimentos/{id}/force', [AlimentoController::class, 'forceDestroy'])->name('alimentos.forceDestroy');

        Route::get('/donaciones', [DonacionController::class, 'index'])->name('donaciones.index');
        Route::post('/donaciones', [DonacionController::class, 'store'])->name('donaciones.store');
        Route::put('/donaciones/{id}', [DonacionController::class, 'update'])->name('donaciones.update');
        Route::get('/donaciones/eliminados', [DonacionController::class, 'trashed'])->name('donaciones.trashed');
        Route::get('/donaciones/{id}', [DonacionController::class, 'show'])->name('donaciones.show');
        Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy'])->name('donaciones.destroy');
        Route::patch('/donaciones/{id}/restore', [DonacionController::class, 'restore'])->name('donaciones.restore');
        Route::delete('/donaciones/{id}/force', [DonacionController::class, 'forceDestroy'])->name('donaciones.forceDestroy');

        Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
        Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
        Route::put('/solicitudes/{id}', [SolicitudController::class, 'update'])->name('solicitudes.update');
        Route::get('/solicitudes/eliminados', [SolicitudController::class, 'trashed'])->name('solicitudes.trashed');
        Route::get('/solicitudes/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show');
        Route::delete('/solicitudes/{id}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy');
        Route::patch('/solicitudes/{id}/restore', [SolicitudController::class, 'restore'])->name('solicitudes.restore');
        Route::delete('/solicitudes/{id}/force', [SolicitudController::class, 'forceDestroy'])->name('solicitudes.forceDestroy');

        Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');
        Route::post('/entregas', [EntregaController::class, 'store'])->name('entregas.store');
        Route::put('/entregas/{id}', [EntregaController::class, 'update'])->name('entregas.update');
        Route::get('/entregas/eliminados', [EntregaController::class, 'trashed'])->name('entregas.trashed');
        Route::get('/entregas/{id}', [EntregaController::class, 'show'])->name('entregas.show');
        Route::delete('/entregas/{id}', [EntregaController::class, 'destroy'])->name('entregas.destroy');
        Route::patch('/entregas/{id}/restore', [EntregaController::class, 'restore'])->name('entregas.restore');
        Route::delete('/entregas/{id}/force', [EntregaController::class, 'forceDestroy'])->name('entregas.forceDestroy');

        Route::get('/acciones-importantes', [AccionImportanteController::class, 'index'])->name('acciones.index');
        Route::post('/acciones-importantes', [AccionImportanteController::class, 'store'])->name('acciones.store');
        Route::put('/acciones-importantes/{id}', [AccionImportanteController::class, 'update'])->name('acciones.update');
        Route::get('/acciones-importantes/eliminados', [AccionImportanteController::class, 'trashed'])->name('acciones.trashed');
        Route::get('/acciones-importantes/{id}', [AccionImportanteController::class, 'show'])->name('acciones.show');
        Route::delete('/acciones-importantes/{id}', [AccionImportanteController::class, 'destroy'])->name('acciones.destroy');
        Route::patch('/acciones-importantes/{id}/restore', [AccionImportanteController::class, 'restore'])->name('acciones.restore');
        Route::delete('/acciones-importantes/{id}/force', [AccionImportanteController::class, 'forceDestroy'])->name('acciones.forceDestroy');

    });
});

Route::prefix('v1/cliente')->group(function () {

    Route::get('/login', [AuthClienteController::class, 'login'])->name('cliente.login');

    Route::post('/login', [AuthClienteController::class, 'in'])->name('cliente.in');

    Route::middleware(['auth:sanctum', EnsureEsCliente::class])->group(function () {

        Route::post('/logout', [AuthClienteController::class, 'out'])->name('cliente.logout');

        Route::get('/perfil', [AuthClienteController::class, 'perfil'])->name('cliente.perfil');

        Route::patch('/perfil', [AuthClienteController::class, 'actualizarPerfil'])->name('cliente.perfil.update');

        Route::get('/alimentos', [AlimentoController::class, 'index'])->name('cliente.alimentos.index');

        Route::get('/alimentos/{id}', [AlimentoController::class, 'show'])->whereNumber('id')->name('cliente.alimentos.show');

        Route::get('/solicitudes', [ClienteSolicitudController::class, 'index'])->name('cliente.solicitudes.index');

        Route::post('/solicitudes', [ClienteSolicitudController::class, 'store'])->name('cliente.solicitudes.store');

        Route::get('/donaciones', [ClienteDonacionController::class, 'index'])->name('cliente.donaciones.index');

        Route::post('/donaciones', [ClienteDonacionController::class, 'store'])->name('cliente.donaciones.store');

    });
});
