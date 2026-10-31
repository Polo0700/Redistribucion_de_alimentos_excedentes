<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Campos que necesita el CRUD de administradores: foto opcional, nivel de
     * acceso y borrado lógico (papelera, restauración y borrado físico).
     *
     * Se revisa columna por columna porque hay bases locales que ya las
     * tienen (se corrieron migraciones de la rama experimental) y no deben
     * romper el `migrate`.
     */
    public function up(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            if (! Schema::hasColumn('administradores', 'foto')) {
                $table->string('foto', 255)->nullable()->after('nombre_completo');
            }

            if (! Schema::hasColumn('administradores', 'nivel')) {
                // nivel 1 = consulta, nivel 2 = consulta + crear + editar, nivel 3 = todo
                $table->unsignedTinyInteger('nivel')->default(1)->after('activo');
            }

            if (! Schema::hasColumn('administradores', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            if (Schema::hasColumn('administradores', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            $columnas = array_values(array_filter(
                ['foto', 'nivel'],
                fn (string $columna) => Schema::hasColumn('administradores', $columna)
            ));

            if ($columnas !== []) {
                $table->dropColumn($columnas);
            }
        });
    }
};
