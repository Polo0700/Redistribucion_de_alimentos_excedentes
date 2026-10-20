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
        // funcion admin
        // leer en /log/funcion_admin.md
        // propiedades de la tabla administradores
        Schema::create('administradores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre_completo', 50);
            $table->string('username', 30)->unique();
            $table->string('password', 100);
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};
