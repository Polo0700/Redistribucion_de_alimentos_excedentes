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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->string('direccion_entrega', 200);
            $table->string('observaciones', 250)->nullable();
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->string('estado', 30)->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
