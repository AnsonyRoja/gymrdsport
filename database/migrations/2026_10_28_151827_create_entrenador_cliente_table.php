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
        Schema::create('entrenador_cliente', function (Blueprint $table) {
            $table->foreignId('entrenador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha_asignacion');
            $table->timestamps();

            // Llave primaria compuesta para asegurar 2FN
            $table->primary(['entrenador_id', 'cliente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenador_cliente');
    }
};
