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
        Schema::create( 'direcciones', function(Blueprint $table){
            $table->id();
            $table->unsignedInteger('alumno_id');
            $table->text('domicilio');
            $table->foreign('alumno_id')->references('id')->on('alumnos');
            $table->timestamps();
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
