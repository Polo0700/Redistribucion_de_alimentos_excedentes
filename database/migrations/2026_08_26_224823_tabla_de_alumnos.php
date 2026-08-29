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
        Schema::create('alumnos', function (Blueprint $table){
            $table->String("codigo");
            $table->String("nombre");
            $table->String("apellido");
            $table->date("nacimiento");
            $table->String("alergias");
            $table->String("url imagen");
            $table->String("status");
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
