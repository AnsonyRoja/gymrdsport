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
        Schema::table('membresias', function (Blueprint $table) {
            if (!Schema::hasColumn('membresias', 'es_vip')) {
                $table->boolean('es_vip')->default(false)->after('duracion_dias');
            }
            if (!Schema::hasColumn('membresias', 'tipo')) {
                $table->string('tipo')->default('estandar')->after('es_vip');
            }
            if (!Schema::hasColumn('membresias', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('tipo');
            }
            if (!Schema::hasColumn('membresias', 'activo')) {
                $table->boolean('activo')->default(true)->after('descripcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membresias', function (Blueprint $table) {
            if (Schema::hasColumn('membresias', 'activo')) {
                $table->dropColumn('activo');
            }
            if (Schema::hasColumn('membresias', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            if (Schema::hasColumn('membresias', 'tipo')) {
                $table->dropColumn('tipo');
            }
            if (Schema::hasColumn('membresias', 'es_vip')) {
                $table->dropColumn('es_vip');
            }
        });
    }
};
