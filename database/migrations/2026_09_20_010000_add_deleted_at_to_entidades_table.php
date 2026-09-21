<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablas = [
            'roles',
            'categorias_alimento',
            'usuarios',
            'alimentos',
            'cuentas_acceso',
            'acciones_importantes',
            'carritos',
            'listas_deseos',
            'solicitudes',
            'donaciones',
            'entregas',
        ];

        foreach ($tablas as $tabla) {
            Schema::table($tabla, fn(Blueprint $t) => $t->softDeletes());
        }
    }

    public function down(): void
    {
        $tablas = [
            'roles',
            'categorias_alimento',
            'usuarios',
            'alimentos',
            'cuentas_acceso',
            'acciones_importantes',
            'carritos',
            'listas_deseos',
            'solicitudes',
            'donaciones',
            'entregas',
        ];

        foreach ($tablas as $tabla) {
            Schema::table($tabla, fn(Blueprint $t) => $t->dropSoftDeletes());
        }
    }
};