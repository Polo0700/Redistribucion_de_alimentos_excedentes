<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('acciones_importantes', function (Blueprint $table){
            $table->id('id_accion');
            $table->foreignId('id_usuario')->nullable()->constrained('usuarios');
            $table->string('accion', 100);
            $table->string('descripcion', 250)->nullable();
            $table->timestamp('fecha_hora')->useCurrent();
            $table->string('tabla_afectada', 80);
            $table->string('ip_origen', 45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('acciones_importantes');
    }
};
