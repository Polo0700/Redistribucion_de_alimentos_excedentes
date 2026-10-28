<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Borrado lógico para administradores (papelera), igual que el resto de entidades.
     */
    public function up(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
