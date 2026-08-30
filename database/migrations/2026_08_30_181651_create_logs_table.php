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
        Schema::create('logs', function (Blueprint $table){
            $table->id('id_log');
            $table->foreignId('id_usuario')->nullable()->constrained('usuarios');
            $table->enum('tipo', ['login','crud','error','alerta'])->default('crud');
            $table->string('accion', 100);
            $table->text('detalle')->nullable();
            $table->timestamp('fecha')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('logs');
    }
};
