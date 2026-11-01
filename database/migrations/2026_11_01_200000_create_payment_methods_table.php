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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->string('tipo')->default('digital'); // efectivo, bancario, cripto, digital
            $table->string('moneda')->default('USD'); // USD, VES, USDT, MIXTO
            $table->text('instrucciones')->nullable(); // Ej: Datos bancarios, teléfono Pago Móvil, Wallet USDT, etc.
            $table->boolean('requiere_referencia')->default(true);
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
