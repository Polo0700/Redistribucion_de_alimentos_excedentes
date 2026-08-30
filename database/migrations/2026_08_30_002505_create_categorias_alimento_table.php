<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_alimento', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 80);
            $table->string('descripcion', 200);
            $table->boolean('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_alimento');
    }
};
