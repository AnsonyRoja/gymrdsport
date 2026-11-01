<?php

namespace Database\Seeders;

use App\Models\Membresia;
use App\Models\MembresiaUsuario;
use App\Models\PaymentMethod;
use App\Models\Rol;
use App\Models\Rutina;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRol = Rol::firstOrCreate(['name' => 'Administrador']);
        $clienteRol = Rol::firstOrCreate(['name' => 'Cliente']);
        $entrenadorRol = Rol::firstOrCreate(['name' => 'Entrenador']);
        Rol::firstOrCreate(['name' => 'Administrador gym']);

        // 2. Planes de Membresía
        $m1 = Membresia::updateOrCreate(
            ['nombre' => 'Membresía Mensual Estándar'],
            [
                'costo_base' => 25.00,
                'duracion_dias' => 30,
                'tipo' => 'estandar',
                'es_vip' => false,
                'descripcion' => 'Acceso completo a máquinas de musculación, cardio y pesas libres por 1 mes (30 días).',
                'activo' => true,
            ]
        );

        $m2 = Membresia::updateOrCreate(
            ['nombre' => 'Membresía Mensual VIP Gold'],
            [
                'costo_base' => 45.00,
                'duracion_dias' => 30,
                'tipo' => 'vip',
                'es_vip' => true,
                'descripcion' => 'Acceso ilimitado por 1 mes (30 días), casillero privado, acceso a sauna, toallas y asesoría nutricional.',
                'activo' => true,
            ]
        );

        $m3 = Membresia::updateOrCreate(
            ['nombre' => 'Pase Quincenal'],
            [
                'costo_base' => 15.00,
                'duracion_dias' => 15,
                'tipo' => 'estandar',
                'es_vip' => false,
                'descripcion' => 'Acceso general al gimnasio por 15 días continuos.',
                'activo' => true,
            ]
        );

        $m4 = Membresia::updateOrCreate(
            ['nombre' => 'Plan Trimestral VIP Pro'],
            [
                'costo_base' => 110.00,
                'duracion_dias' => 90,
                'tipo' => 'vip',
                'es_vip' => true,
                'descripcion' => 'Plan de 3 meses (90 días) con todos los beneficios VIP y precio preferencial.',
                'activo' => true,
            ]
        );

        // 3. Catálogo de Métodos de Pago
        $metodos = [
            [
                'nombre' => 'USDT (Binance / Cripto)',
                'codigo' => 'usdt',
                'tipo' => 'cripto',
                'moneda' => 'USDT',
                'instrucciones' => 'Binance Pay ID: 819230192 o Red Tron TRC-20: TXYz9382109283019283. Indicar Hash o TxID.',
                'requiere_referencia' => true,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Pago Móvil',
                'codigo' => 'pago_movil',
                'tipo' => 'bancario',
                'moneda' => 'VES',
                'instrucciones' => 'Banco Banesco (0134) - RIF: J-50123894-0 - Teléfono: 0412-1234567. Enviar comprobante de 4 o 6 dígitos.',
                'requiere_referencia' => true,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Transferencia Bancaria',
                'codigo' => 'transferencia',
                'tipo' => 'bancario',
                'moneda' => 'VES',
                'instrucciones' => 'Banesco Banco Universal - Cuenta Corriente Nº 0134-0000-00-0000000000 - A nombre de: GYM RD SPORT C.A.',
                'requiere_referencia' => true,
                'activo' => true,
                'orden' => 3,
            ],
            [
                'nombre' => 'Efectivo USD',
                'codigo' => 'efectivo_usd',
                'tipo' => 'efectivo',
                'moneda' => 'USD',
                'instrucciones' => 'Cobro directo en caja/recepción en divisas en efectivo. Billetes en buen estado sin roturas.',
                'requiere_referencia' => false,
                'activo' => true,
                'orden' => 4,
            ],
            [
                'nombre' => 'Efectivo Bs',
                'codigo' => 'efectivo_bs',
                'tipo' => 'efectivo',
                'moneda' => 'VES',
                'instrucciones' => 'Cobro en taquilla en Bolívares en efectivo calculado a la tasa oficial del BCV del día.',
                'requiere_referencia' => false,
                'activo' => true,
                'orden' => 5,
            ],
            [
                'nombre' => 'Zelle',
                'codigo' => 'zelle',
                'tipo' => 'digital',
                'moneda' => 'USD',
                'instrucciones' => 'Enviar a: pagos@gymrdsport.com - Titular: Gym RD Sport LLC. Colocar en concepto el nombre del cliente.',
                'requiere_referencia' => true,
                'activo' => true,
                'orden' => 6,
            ],
            [
                'nombre' => 'Punto de Venta / Tarjeta',
                'codigo' => 'punto_venta',
                'tipo' => 'bancario',
                'moneda' => 'VES',
                'instrucciones' => 'Tarjeta de Débito / Crédito nacional en terminal de punto de venta físico del gimnasio.',
                'requiere_referencia' => true,
                'activo' => true,
                'orden' => 7,
            ],
        ];

        foreach ($metodos as $metodo) {
            PaymentMethod::updateOrCreate(
                ['codigo' => $metodo['codigo']],
                $metodo
            );
        }

        // 4. Usuarios Principales
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => '12345678',
                'roles_id' => $adminRol->id,
                'rol' => 'admin',
                'estado' => 'activo',
                'cedula' => 'V-10203040',
                'telefono' => '+58 412 1234567',
            ]
        );

        $entrenador = User::updateOrCreate(
            ['email' => 'entrenador@example.com'],
            [
                'name' => 'Carlos Entrenador',
                'password' => '12345678',
                'roles_id' => $entrenadorRol->id,
                'rol' => 'entrenador',
                'estado' => 'activo',
                'cedula' => 'V-19876543',
                'telefono' => '+58 414 7654321',
            ]
        );

        $cliente = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => '12345678',
                'roles_id' => $clienteRol->id,
                'rol' => 'cliente',
                'estado' => 'activo',
                'cedula' => 'V-25678901',
                'telefono' => '+58 424 9876543',
                'peso' => 78.5,
                'altura' => 176.0,
                'historial_medico' => 'Sin cirugías previas ni hipertensión. Molestia leve en rodilla izquierda.',
                'objetivos' => 'Hipertrofia muscular y reducción de porcentaje graso.',
                'dias_gracia' => 3,
            ]
        );

        // 4. Vincular cliente a entrenador
        \Illuminate\Support\Facades\DB::table('entrenador_cliente')->updateOrInsert(
            [
                'cliente_id' => $cliente->id,
                'entrenador_id' => $entrenador->id,
            ],
            [
                'fecha_asignacion' => now()->subDays(10)->toDateString(),
                'fecha_desvinculacion' => null,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Asignar membresía activa al cliente de prueba
        MembresiaUsuario::updateOrInsert(
            [
                'usuario_id' => $cliente->id,
                'membresia_id' => $m2->id,
            ],
            [
                'fecha_inicio' => now()->subDays(5)->toDateString(),
                'fecha_fin' => now()->addDays(25)->toDateString(),
                'estado_pago' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 6. Rutina de prueba asignada por el entrenador
        Rutina::firstOrCreate(
            [
                'cliente_id' => $cliente->id,
                'entrenador_id' => $entrenador->id,
                'titulo' => 'Pecho, Hombro y Tríceps (Fuerza)',
            ],
            [
                'dia_semana' => 'Lunes',
                'observaciones' => 'Mantener cadencia controlada 3-1-1. Calentar manguito rotador 5 min.',
                'estado' => 'activa',
                'ejercicios' => [
                    [
                        'nombre' => 'Press de Banca Plano con Barra',
                        'series' => 4,
                        'repeticiones' => '8-10',
                        'descanso' => '90 seg',
                        'notas' => 'Aumentar carga progresivamente en serie 3 y 4',
                    ],
                    [
                        'nombre' => 'Press Inclinado con Mancuernas',
                        'series' => 3,
                        'repeticiones' => '10-12',
                        'descanso' => '60 seg',
                        'notas' => 'Cuidar rango completo de movimiento',
                    ],
                    [
                        'nombre' => 'Aperturas en Polea (Cruces)',
                        'series' => 3,
                        'repeticiones' => '12-15',
                        'descanso' => '45 seg',
                        'notas' => 'Contracción pico de 1 segundo al centro',
                    ],
                    [
                        'nombre' => 'Fondos en Paralelas / Fondos en Banco',
                        'series' => 3,
                        'repeticiones' => 'Al fallo',
                        'descanso' => '60 seg',
                        'notas' => 'Cuerpo ligeramente inclinado hacia adelante',
                    ],
                ],
            ]
        );
    }
}
