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
        // 1. Ampliar campos de users si no existen
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['admin', 'entrenador', 'cliente'])->default('cliente')->after('password');
            }
            if (!Schema::hasColumn('users', 'estado')) {
                $table->string('estado')->default('activo')->after('rol');
            }
            if (!Schema::hasColumn('users', 'cedula')) {
                $table->string('cedula')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono')->nullable()->after('cedula');
            }
            if (!Schema::hasColumn('users', 'peso')) {
                $table->decimal('peso', 5, 2)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('users', 'altura')) {
                $table->decimal('altura', 5, 2)->nullable()->after('peso');
            }
            if (!Schema::hasColumn('users', 'historial_medico')) {
                $table->text('historial_medico')->nullable()->after('altura');
            }
            if (!Schema::hasColumn('users', 'objetivos')) {
                $table->text('objetivos')->nullable()->after('historial_medico');
            }
            if (!Schema::hasColumn('users', 'dias_gracia')) {
                $table->unsignedInteger('dias_gracia')->default(3)->after('objetivos');
            }
        });

        // 2. Tabla de órdenes de pago y facturación (RFC-02, TRX-01)
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('membresia_id')->constrained('membresias')->cascadeOnDelete();
                $table->decimal('monto_usd', 10, 2);
                $table->decimal('tasa_bcv', 10, 2);
                $table->decimal('monto_bs', 12, 2);
                $table->decimal('monto_pagado_usd', 10, 2)->default(0);
                $table->decimal('monto_pagado_bs', 12, 2)->default(0);
                $table->decimal('descuento_usd', 10, 2)->default(0);
                $table->enum('tipo_pago', ['completo', 'parcial', 'adelanto'])->default('completo');
                $table->string('metodo_pago')->default('Pago Móvil');
                $table->string('referencia_pago')->nullable();
                $table->enum('estado', ['Pagada', 'Parcial', 'Pendiente'])->default('Pagada');
                $table->date('fecha_pago');
                $table->text('notas')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabla de rutinas personalizadas (Módulo C, TRX-02)
        if (!Schema::hasTable('rutinas')) {
            Schema::create('rutinas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('entrenador_id')->constrained('users')->cascadeOnDelete();
                $table->string('titulo');
                $table->enum('dia_semana', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])->default('Lunes');
                $table->json('ejercicios');
                $table->text('observaciones')->nullable();
                $table->enum('estado', ['activa', 'completada', 'inactiva'])->default('activa');
                $table->timestamps();
            });
        }

        // 4. Tabla de notificaciones para usuarios y entrenadores (TRX-02)
        if (!Schema::hasTable('notificaciones')) {
            Schema::create('notificaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('titulo');
                $table->text('mensaje');
                $table->enum('tipo', ['info', 'alerta', 'exito'])->default('info');
                $table->boolean('leida')->default(false);
                $table->timestamps();
            });
        }

        // 5. Ampliar tabla de attendances (RFC-04, TRX-03)
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'tipo_registro')) {
                $table->enum('tipo_registro', ['acceso_gimnasio', 'jornada_entrenador'])->default('acceso_gimnasio')->after('salida');
            }
            if (!Schema::hasColumn('attendances', 'resultado_acceso')) {
                $table->enum('resultado_acceso', ['autorizado', 'rechazado_moroso', 'rechazado_horario'])->default('autorizado')->after('tipo_registro');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'resultado_acceso')) {
                $table->dropColumn('resultado_acceso');
            }
            if (Schema::hasColumn('attendances', 'tipo_registro')) {
                $table->dropColumn('tipo_registro');
            }
        });

        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('rutinas');
        Schema::dropIfExists('orders');

        Schema::table('users', function (Blueprint $table) {
            $cols = ['dias_gracia', 'objetivos', 'historial_medico', 'altura', 'peso', 'telefono', 'cedula'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
