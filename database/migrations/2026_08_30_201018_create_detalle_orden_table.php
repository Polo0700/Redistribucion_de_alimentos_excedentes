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
        Schema::create('detalle_orden', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->foreignId('id_orden')->constrained('ordenes');
            $table->foreignId('id_alimento')->constrained('alimentos');
            $table->integer('cantidad')->default(1);
            $table->decimal('subtotal',10,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden');
    }
};
