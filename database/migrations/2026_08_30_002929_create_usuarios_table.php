<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_rol')
                ->constrained('roles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('correo', 150);
            $table->integer('telefono');
            $table->string('direccion', 200);
            $table->dateTime('fecha_registro');
            $table->boolean('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};