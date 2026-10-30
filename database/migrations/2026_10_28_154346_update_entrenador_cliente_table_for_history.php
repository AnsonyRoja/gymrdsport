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
        Schema::table('entrenador_cliente', function (Blueprint $table) {
            $table->date('fecha_desvinculacion')->nullable()->after('fecha_asignacion');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('fecha_desvinculacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrenador_cliente', function (Blueprint $table) {
            $table->dropColumn(['fecha_desvinculacion', 'estado']);
        });
    }
};
