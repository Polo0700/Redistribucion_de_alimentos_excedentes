<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // agrega la columna deleted_at para la papelera
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
