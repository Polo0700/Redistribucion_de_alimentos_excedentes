<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_donacion', function (Blueprint $table) {
            $table->id('id_detalle_donacion');

            $table->foreignId('id_donacion')
                ->constrained('donaciones', 'id_donacion')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_alimento')
                ->constrained('alimentos', 'id_alimento')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->decimal('cantidad', 10, 2);
            $table->string('observaciones', 200);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_donacion');
    }
};