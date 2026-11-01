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
            if (!Schema::hasColumn('orders', 'numero_factura')) {
                $table->string('numero_factura')->nullable()->after('numero_comprobante');
            }
            if (!Schema::hasColumn('orders', 'numero_control')) {
                $table->string('numero_control')->nullable()->after('numero_factura');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'numero_control')) {
                $table->dropColumn('numero_control');
            }
            if (Schema::hasColumn('orders', 'numero_factura')) {
                $table->dropColumn('numero_factura');
            }
        });
    }
};
