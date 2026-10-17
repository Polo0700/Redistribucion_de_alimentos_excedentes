<?php

// ============================================================
// RUTAS WEB (agregar en routes/web.php por CADA entidad)
// ============================================================
// QUÉ DEBE CONTENER, EN ESTE ORDEN (ver nota del show):
//
// Route::get('/ENTIDAD',              [Ctrl::class, 'index'])->name('ENTIDAD.index');
// Route::get('/ENTIDAD/eliminados',   [Ctrl::class, 'trashed'])->name('ENTIDAD.trashed');
// Route::get('/ENTIDAD/create',       [Ctrl::class, 'create'])->name('ENTIDAD.create');
// Route::post('/ENTIDAD',             [Ctrl::class, 'store'])->name('ENTIDAD.store');
// Route::get('/ENTIDAD/{id}',         [Ctrl::class, 'show'])->name('ENTIDAD.show');
// Route::get('/ENTIDAD/{id}/edit',    [Ctrl::class, 'edit'])->name('ENTIDAD.edit');
// Route::put('/ENTIDAD/{id}',         [Ctrl::class, 'update'])->name('ENTIDAD.update');
// Route::delete('/ENTIDAD/{id}',      [Ctrl::class, 'destroy'])->name('ENTIDAD.destroy');
// Route::patch('/ENTIDAD/{id}/restore', [Ctrl::class, 'restore'])->name('ENTIDAD.restore');
// Route::delete('/ENTIDAD/{id}/force',  [Ctrl::class, 'forceDestroy'])->name('ENTIDAD.forceDestroy');
//
// NOTA IMPORTANTE: '/ENTIDAD/eliminados' DEBE ir ANTES de
// '/ENTIDAD/{id}', si no Laravel interpreta "eliminados" como un id.
// ============================================================