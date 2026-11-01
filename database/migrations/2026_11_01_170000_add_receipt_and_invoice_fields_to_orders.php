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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'tipo_comprobante')) {
                $table->enum('tipo_comprobante', ['comprobante', 'factura'])->default('comprobante')->after('estado');
            }
            if (!Schema::hasColumn('orders', 'numero_comprobante')) {
                $table->string('numero_comprobante')->nullable()->after('tipo_comprobante');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'numero_comprobante')) {
                $table->dropColumn('numero_comprobante');
            }
            if (Schema::hasColumn('orders', 'tipo_comprobante')) {
                $table->dropColumn('tipo_comprobante');
            }
        });
    }
};
