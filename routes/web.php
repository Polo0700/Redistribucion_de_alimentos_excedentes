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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Autenticación administrativa
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthAdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AuthAdminController::class, 'in'])->name('admin.in');
Route::post('/admin/logout', [AuthAdminController::class, 'out'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Panel administrativo (requiere sesión de administrador con auth:admin)
| Restricciones por nivel centralizadas mediante middleware 'can:<habilidad>'
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')->group(function () {

    // Dashboard general (Niveles 1, 2 y 3)
    Route::view('/dashboard', 'dashboard')->name('dashboard')->middleware('can:consultar');

    /*
    |--------------------------------------------------------------------------
    | Administradores (gestión de cuentas - Exclusivo Nivel 3)
    | Todas las operaciones exigen además la habilidad específica del Gate
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:administrar-administradores')->group(function () {

        Route::get('/administradores', [AdministradorController::class, 'index'])->name('administradores.index')->middleware('can:consultar');
        Route::get('/administradores/eliminados', [AdministradorController::class, 'trashed'])->name('administradores.trashed')->middleware('can:consultar');
        Route::get('/administradores/matriz-niveles', fn () => view('administradores.niveles'))->name('administradores.niveles')->middleware('can:consultar');
        Route::get('/administradores/{id}', [AdministradorController::class, 'show'])->name('administradores.show')->middleware('can:consultar')->whereNumber('id');

        Route::get('/administradores/create', [AdministradorController::class, 'create'])->name('administradores.create')->middleware('can:crear');
        Route::post('/administradores', [AdministradorController::class, 'store'])->name('administradores.store')->middleware('can:crear');

        Route::get('/administradores/{id}/edit', [AdministradorController::class, 'edit'])->name('administradores.edit')->middleware('can:editar')->whereNumber('id');
        Route::put('/administradores/{id}', [AdministradorController::class, 'update'])->name('administradores.update')->middleware('can:editar')->whereNumber('id');

        Route::delete('/administradores/{id}', [AdministradorController::class, 'destroy'])->name('administradores.destroy')->middleware('can:eliminar')->whereNumber('id');
        Route::patch('/administradores/{id}/restore', [AdministradorController::class, 'restore'])->name('administradores.restore')->middleware('can:eliminar')->whereNumber('id');
        Route::delete('/administradores/{id}/force', [AdministradorController::class, 'forceDestroy'])->name('administradores.forceDestroy')->middleware('can:eliminar')->whereNumber('id');
    });

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index')->middleware('can:consultar');
    Route::get('/roles/eliminados', [RolController::class, 'trashed'])->name('roles.trashed')->middleware('can:consultar');
    Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create')->middleware('can:crear');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store')->middleware('can:crear');

    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/roles/{id}/restore', [RolController::class, 'restore'])->name('roles.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/roles/{id}/force', [RolController::class, 'forceDestroy'])->name('roles.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Usuarios
    |--------------------------------------------------------------------------
    */
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index')->middleware('can:consultar');
    Route::get('/usuarios/eliminados', [UsuarioController::class, 'trashed'])->name('usuarios.trashed')->middleware('can:consultar');
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create')->middleware('can:crear');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store')->middleware('can:crear');

    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/usuarios/{id}/force', [UsuarioController::class, 'forceDestroy'])->name('usuarios.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Cuentas de acceso
    |--------------------------------------------------------------------------
    */
    Route::get('/cuentas-acceso', [CuentaAccesoController::class, 'index'])->name('cuentas-acceso.index')->middleware('can:consultar');
    Route::get('/cuentas-acceso/eliminados', [CuentaAccesoController::class, 'trashed'])->name('cuentas-acceso.trashed')->middleware('can:consultar');
    Route::get('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'show'])->name('cuentas-acceso.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/cuentas-acceso/create', [CuentaAccesoController::class, 'create'])->name('cuentas-acceso.create')->middleware('can:crear');
    Route::post('/cuentas-acceso', [CuentaAccesoController::class, 'store'])->name('cuentas-acceso.store')->middleware('can:crear');

    Route::get('/cuentas-acceso/{id}/edit', [CuentaAccesoController::class, 'edit'])->name('cuentas-acceso.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'update'])->name('cuentas-acceso.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/cuentas-acceso/{id}', [CuentaAccesoController::class, 'destroy'])->name('cuentas-acceso.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/cuentas-acceso/{id}/restore', [CuentaAccesoController::class, 'restore'])->name('cuentas-acceso.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/cuentas-acceso/{id}/force', [CuentaAccesoController::class, 'forceDestroy'])->name('cuentas-acceso.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Categorías
    |--------------------------------------------------------------------------
    */
    Route::get('/categorias', [CategoriaAlimentoController::class, 'index'])->name('categorias.index')->middleware('can:consultar');
    Route::get('/categorias/eliminados', [CategoriaAlimentoController::class, 'trashed'])->name('categorias.trashed')->middleware('can:consultar');
    Route::get('/categorias/{id}', [CategoriaAlimentoController::class, 'show'])->name('categorias.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/categorias/create', [CategoriaAlimentoController::class, 'create'])->name('categorias.create')->middleware('can:crear');
    Route::post('/categorias', [CategoriaAlimentoController::class, 'store'])->name('categorias.store')->middleware('can:crear');

    Route::get('/categorias/{id}/edit', [CategoriaAlimentoController::class, 'edit'])->name('categorias.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/categorias/{id}', [CategoriaAlimentoController::class, 'update'])->name('categorias.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/categorias/{id}', [CategoriaAlimentoController::class, 'destroy'])->name('categorias.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/categorias/{id}/restore', [CategoriaAlimentoController::class, 'restore'])->name('categorias.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/categorias/{id}/force', [CategoriaAlimentoController::class, 'forceDestroy'])->name('categorias.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Alimentos
    |--------------------------------------------------------------------------
    */
    Route::get('/alimentos', [AlimentoController::class, 'index'])->name('alimentos.index')->middleware('can:consultar');
    Route::get('/alimentos/eliminados', [AlimentoController::class, 'trashed'])->name('alimentos.trashed')->middleware('can:consultar');
    Route::get('/alimentos/{id}', [AlimentoController::class, 'show'])->name('alimentos.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/alimentos/create', [AlimentoController::class, 'create'])->name('alimentos.create')->middleware('can:crear');
    Route::post('/alimentos', [AlimentoController::class, 'store'])->name('alimentos.store')->middleware('can:crear');

    Route::get('/alimentos/{id}/edit', [AlimentoController::class, 'edit'])->name('alimentos.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/alimentos/{id}', [AlimentoController::class, 'update'])->name('alimentos.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/alimentos/{id}', [AlimentoController::class, 'destroy'])->name('alimentos.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/alimentos/{id}/restore', [AlimentoController::class, 'restore'])->name('alimentos.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/alimentos/{id}/force', [AlimentoController::class, 'forceDestroy'])->name('alimentos.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Donaciones
    |--------------------------------------------------------------------------
    */
    Route::get('/donaciones', [DonacionController::class, 'index'])->name('donaciones.index')->middleware('can:consultar');
    Route::get('/donaciones/eliminados', [DonacionController::class, 'trashed'])->name('donaciones.trashed')->middleware('can:consultar');
    Route::get('/donaciones/{id}', [DonacionController::class, 'show'])->name('donaciones.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/donaciones/create', [DonacionController::class, 'create'])->name('donaciones.create')->middleware('can:crear');
    Route::post('/donaciones', [DonacionController::class, 'store'])->name('donaciones.store')->middleware('can:crear');

    Route::get('/donaciones/{id}/edit', [DonacionController::class, 'edit'])->name('donaciones.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/donaciones/{id}', [DonacionController::class, 'update'])->name('donaciones.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy'])->name('donaciones.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/donaciones/{id}/restore', [DonacionController::class, 'restore'])->name('donaciones.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/donaciones/{id}/force', [DonacionController::class, 'forceDestroy'])->name('donaciones.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Solicitudes
    |--------------------------------------------------------------------------
    */
    Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index')->middleware('can:consultar');
    Route::get('/solicitudes/eliminados', [SolicitudController::class, 'trashed'])->name('solicitudes.trashed')->middleware('can:consultar');
    Route::get('/solicitudes/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create')->middleware('can:crear');
    Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store')->middleware('can:crear');

    Route::get('/solicitudes/{id}/edit', [SolicitudController::class, 'edit'])->name('solicitudes.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/solicitudes/{id}', [SolicitudController::class, 'update'])->name('solicitudes.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/solicitudes/{id}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/solicitudes/{id}/restore', [SolicitudController::class, 'restore'])->name('solicitudes.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/solicitudes/{id}/force', [SolicitudController::class, 'forceDestroy'])->name('solicitudes.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Entregas
    |--------------------------------------------------------------------------
    */
    Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index')->middleware('can:consultar');
    Route::get('/entregas/eliminados', [EntregaController::class, 'trashed'])->name('entregas.trashed')->middleware('can:consultar');
    Route::get('/entregas/{id}', [EntregaController::class, 'show'])->name('entregas.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/entregas/create', [EntregaController::class, 'create'])->name('entregas.create')->middleware('can:crear');
    Route::post('/entregas', [EntregaController::class, 'store'])->name('entregas.store')->middleware('can:crear');

    Route::get('/entregas/{id}/edit', [EntregaController::class, 'edit'])->name('entregas.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/entregas/{id}', [EntregaController::class, 'update'])->name('entregas.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/entregas/{id}', [EntregaController::class, 'destroy'])->name('entregas.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/entregas/{id}/restore', [EntregaController::class, 'restore'])->name('entregas.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/entregas/{id}/force', [EntregaController::class, 'forceDestroy'])->name('entregas.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

    /*
    |--------------------------------------------------------------------------
    | Acciones importantes
    |--------------------------------------------------------------------------
    */
    Route::get('/acciones-importantes', [AccionImportanteController::class, 'index'])->name('acciones.index')->middleware('can:consultar');
    Route::get('/acciones-importantes/eliminados', [AccionImportanteController::class, 'trashed'])->name('acciones.trashed')->middleware('can:consultar');
    Route::get('/acciones-importantes/{id}', [AccionImportanteController::class, 'show'])->name('acciones.show')->middleware('can:consultar')->whereNumber('id');

    Route::get('/acciones-importantes/create', [AccionImportanteController::class, 'create'])->name('acciones.create')->middleware('can:crear');
    Route::post('/acciones-importantes', [AccionImportanteController::class, 'store'])->name('acciones.store')->middleware('can:crear');

    Route::get('/acciones-importantes/{id}/edit', [AccionImportanteController::class, 'edit'])->name('acciones.edit')->middleware('can:editar')->whereNumber('id');
    Route::put('/acciones-importantes/{id}', [AccionImportanteController::class, 'update'])->name('acciones.update')->middleware('can:editar')->whereNumber('id');

    Route::delete('/acciones-importantes/{id}', [AccionImportanteController::class, 'destroy'])->name('acciones.destroy')->middleware('can:eliminar')->whereNumber('id');
    Route::patch('/acciones-importantes/{id}/restore', [AccionImportanteController::class, 'restore'])->name('acciones.restore')->middleware('can:eliminar')->whereNumber('id');
    Route::delete('/acciones-importantes/{id}/force', [AccionImportanteController::class, 'forceDestroy'])->name('acciones.forceDestroy')->middleware('can:eliminar')->whereNumber('id');

}); // fin auth:admin
