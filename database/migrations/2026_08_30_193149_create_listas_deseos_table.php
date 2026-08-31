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
        Schema::create('listas_deseos', function (Blueprint $table) {
            $table->id('id_lista');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->string('nombre', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas_deseos');
    }
};
