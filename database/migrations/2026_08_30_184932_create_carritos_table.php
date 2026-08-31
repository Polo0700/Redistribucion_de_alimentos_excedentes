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
        Schema::create('carritos', function (Blueprint $table){
            $table->id('id_carrito');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->string('estado', 30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('carritos');
    }
};
