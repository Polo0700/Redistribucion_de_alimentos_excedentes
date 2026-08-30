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
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id('id_orden');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->timestamp('hora_compra')->useCurrent();
            $table->decimal('total', 10,  2);
            $table->enum('estado',['pendiente','completada','cancelada'])->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
