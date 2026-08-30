<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_acceso', function (Blueprint $table) {
            $table->id('id_cuenta');

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->string('proveedor', 30);
            $table->string('identificador_externo', 150);
            $table->string('contrasena_hash', 255);
            $table->dateTime('fecha_ultimo_acceso');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_acceso');
    }
};