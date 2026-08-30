<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito_detalle', function (Blueprint $table) {
            $table->id('id_detalle_carrito');

            $table->foreignId('id_carrito')
                ->constrained('carritos', 'id_carrito')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_alimento')
                ->constrained('alimentos', 'id_alimento')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->decimal('cantidad', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito_detalle');
    }
};