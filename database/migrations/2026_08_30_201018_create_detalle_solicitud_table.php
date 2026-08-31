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
        Schema::create('detalle_solicitud', function (Blueprint $table) {
            $table->id('id_detalle_solicitud');
            $table->foreignId('id_solicitud')->constrained('solicitudes', 'id_solicitud');
            $table->foreignId('id_alimento')->constrained('alimentos', 'id_alimento');
            $table->decimal('cantidad',10,2)->default(1);
            $table->string('estado', 30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_solicitud');
    }
};
