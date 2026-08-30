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
        Schema::create('carrito', function (Blueprint $table){
            $table->id('id_carrito');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->foreignId('id_alimento')->constrained('alimentos');
            $table->integer('cantidad')->default(1);
            $table->timestamp('fecha_agregado')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('carrito');
    }
};
