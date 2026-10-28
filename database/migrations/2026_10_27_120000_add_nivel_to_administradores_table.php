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
        Schema::table('administradores', function (Blueprint $table) {
            $table->unsignedTinyInteger('nivel')->default(1)->after('activo');
            // nivel 1 = consulta, nivel 2 = consulta + crear + editar, nivel 3 = todo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            $table->dropColumn('nivel');
        });
    }
};
