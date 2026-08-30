<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deseos_detalle', function (Blueprint $table) {
            $table->id('id_deseo');

            $table->foreignId('id_lista')
                ->constrained('listas_deseos', 'id_lista')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_alimento')
                ->constrained('alimentos', 'id_alimento')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->dateTime('fecha_agregado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deseos_detalle');
    }
};