<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donaciones', function (Blueprint $table) {
            $table->id('id_donacion');

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->dateTime('fecha_donacion');
            $table->date('fecha_limite');
            $table->string('ubicacion', 200);
            $table->string('estado', 30);
            $table->string('observaciones', 250);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donaciones');
    }
};
