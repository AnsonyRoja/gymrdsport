<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Membresia;
use App\Models\MembresiaUsuario;
use App\Models\Notificacion;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Rol;
use App\Models\Rutina;
use App\Models\User;
use App\Services\AsignacionEntrenadorServicio;
use App\Services\ControlAccesoServicio;
use App\Services\PagoMembresiaServicio;
use App\Services\TasaBcvServicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymTechnicalAnalysisTest extends TestCase
{
    use RefreshDatabase;

    protected Rol $adminRol;
    protected Rol $clienteRol;
    protected Rol $entrenadorRol;
    protected Membresia $membresiaMensual;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRol = Rol::create(['name' => 'Administrador']);
        $this->clienteRol = Rol::create(['name' => 'Cliente']);
        $this->entrenadorRol = Rol::create(['name' => 'Entrenador']);

        $this->membresiaMensual = Membresia::create([
            'nombre' => 'Membresía Mensual Full',
            'costo_base' => 30.00,
            'duracion_dias' => 30,
        ]);
    }

    /**
     * TRX-01 & RFC-02: Generación y Pago de Orden a la Tasa del Día BCV
     */
    public function test_trx01_generacion_y_pago_de_orden_membresia(): void
    {
        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
            'estado' => 'moroso',
        ]);

        $servicioPago = app(PagoMembresiaServicio::class);
        $resultado = $servicioPago->generarYProcesarOrden(
            $cliente->id,
            $this->membresiaMensual->id,
            30.00,
            'Pago Móvil',
            'REF-123456',
            0.0,
            'Pago regular'
        );

        $this->assertTrue($resultado['success']);
        $this->assertDatabaseHas('orders', [
            'usuario_id' => $cliente->id,
            'membresia_id' => $this->membresiaMensual->id,
            'monto_usd' => 30.00,
            'estado' => 'Pagada',
            'tipo_pago' => 'completo',
            'referencia_pago' => 'REF-123456',
        ]);

        // Verificar que extendió vigencia y el cliente ya no es moroso
        $cliente->refresh();
        $this->assertEquals('activo', $cliente->estado);
        $this->assertDatabaseHas('membresias_usuarios', [
            'usuario_id' => $cliente->id,
            'estado_pago' => 'activo',
        ]);
    }

    /**
     * TRX-01: Pago de membresía utilizando USDT (Cripto)
     */
    public function test_trx01_pago_con_usdt(): void
    {
        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);

        $servicioPago = app(PagoMembresiaServicio::class);
        $resultado = $servicioPago->generarYProcesarOrden(
            $cliente->id,
            $this->membresiaMensual->id,
            30.00,
            'USDT (Binance / Cripto)',
            'TXID-0x98a72b1c4d3e',
            0.0,
            'Transferencia USDT red TRC20 / Binance Pay'
        );

        $this->assertTrue($resultado['success']);
        $this->assertDatabaseHas('orders', [
            'usuario_id' => $cliente->id,
            'metodo_pago' => 'USDT (Binance / Cripto)',
            'referencia_pago' => 'TXID-0x98a72b1c4d3e',
            'monto_usd' => 30.00,
            'estado' => 'Pagada',
        ]);
    }

    /**
     * TRX-02 & RFC-03: Reasignación de Entrenador con Transferencia de Rutinas y Notificaciones
     */
    public function test_trx02_reasignacion_de_entrenador_con_rutinas_y_notificaciones(): void
    {
        $entrenador1 = User::factory()->create([
            'roles_id' => $this->entrenadorRol->id,
            'rol' => 'entrenador',
            'name' => 'Entrenador Uno',
        ]);

        $entrenador2 = User::factory()->create([
            'roles_id' => $this->entrenadorRol->id,
            'rol' => 'entrenador',
            'name' => 'Entrenador Dos',
        ]);

        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
            'name' => 'Atleta Marcos',
        ]);

        // Vincular cliente a entrenador1
        $cliente->entrenadores()->attach($entrenador1->id, [
            'fecha_asignacion' => now()->subDays(5)->toDateString(),
            'estado' => 'activo',
        ]);

        // Crear rutina asociada a entrenador1
        $rutina = Rutina::create([
            'cliente_id' => $cliente->id,
            'entrenador_id' => $entrenador1->id,
            'titulo' => 'Pierna y Abdomen',
            'dia_semana' => 'Martes',
            'ejercicios' => [
                ['nombre' => 'Sentadilla Libre', 'series' => 4, 'repeticiones' => '10', 'descanso' => '90s'],
            ],
            'estado' => 'activa',
        ]);

        // Ejecutar TRX-02
        $servicioAsignacion = app(AsignacionEntrenadorServicio::class);
        $resultado = $servicioAsignacion->reasignar($cliente->id, $entrenador1->id, $entrenador2->id);

        $this->assertEquals($entrenador2->id, $resultado['entrenador_nuevo_id']);

        // Verificar que la rutina fue transferida a entrenador2
        $rutina->refresh();
        $this->assertEquals($entrenador2->id, $rutina->entrenador_id);

        // Verificar notificaciones creadas
        $this->assertDatabaseHas('notificaciones', [
            'user_id' => $cliente->id,
            'titulo' => 'Actualización de Entrenador Asignado',
        ]);
        $this->assertDatabaseHas('notificaciones', [
            'user_id' => $entrenador2->id,
            'titulo' => 'Nuevo Atleta Asignado',
        ]);
    }

    /**
     * TRX-03 & RFC-01: Marcaje de Asistencia y Bloqueo por Morosidad
     */
    public function test_trx03_acceso_denegado_si_cliente_es_moroso(): void
    {
        $clienteMoroso = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
            'estado' => 'moroso',
            'email' => 'moroso@gym.com',
            'cedula' => 'V-99999999',
        ]);

        $servicioAcceso = app(ControlAccesoServicio::class);
        $resultado = $servicioAcceso->procesarMarcaje('moroso@gym.com');

        $this->assertFalse($resultado['autorizado']);
        $this->assertEquals('RECHAZADO_MOROSO', $resultado['codigo']);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $clienteMoroso->id,
            'resultado_acceso' => 'rechazado_moroso',
        ]);
    }

    /**
     * TRX-03 & RFC-04: Marcaje de Entrada y Salida para Entrenador
     */
    public function test_trx03_marcaje_checkin_checkout_entrenador(): void
    {
        $entrenador = User::factory()->create([
            'roles_id' => $this->entrenadorRol->id,
            'rol' => 'entrenador',
            'email' => 'profe@gym.com',
        ]);

        $servicioAcceso = app(ControlAccesoServicio::class);

        // 1. Check-in de entrada
        $checkin = $servicioAcceso->procesarMarcaje('profe@gym.com', '08:00');
        $this->assertTrue($checkin['autorizado']);
        $this->assertEquals('ENTRADA_ENTRENADOR', $checkin['codigo']);

        // 2. Check-out de salida
        $checkout = $servicioAcceso->procesarMarcaje('profe@gym.com');
        $this->assertTrue($checkout['autorizado']);
        $this->assertEquals('SALIDA_REGISTRADA', $checkout['codigo']);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $entrenador->id,
            'estatus' => 'Turno Cumplido',
        ]);
    }

    /**
     * RFC-01: Comando programado para marcar clientes morosos
     */
    public function test_rfc01_comando_marca_morosos_automaticamente(): void
    {
        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
            'estado' => 'activo',
            'dias_gracia' => 2,
        ]);

        // Membresía vencida hace 10 días
        MembresiaUsuario::create([
            'usuario_id' => $cliente->id,
            'membresia_id' => $this->membresiaMensual->id,
            'fecha_inicio' => now()->subDays(40)->toDateString(),
            'fecha_fin' => now()->subDays(10)->toDateString(),
            'estado_pago' => 'activo',
        ]);

        $this->artisan('gym:verificar-morosidad')
            ->assertSuccessful();

        $cliente->refresh();
        $this->assertEquals('moroso', $cliente->estado);
    }

    /**
     * Prueba de Emisión de Factura Fiscal vs Comprobante de Cobro según solicitud del cliente
     */
    public function test_emision_factura_y_comprobante_con_numeracion_secuencial(): void
    {
        $admin = User::factory()->create([
            'roles_id' => $this->adminRol->id,
            'rol' => 'admin',
        ]);

        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
        ]);

        $this->actingAs($admin);

        // 1. Registro inicial con Factura Fiscal (FAC-000001)
        $responseFactura1 = $this->post(route('admin.payments.store'), [
            'usuario_id' => $cliente->id,
            'membresia_id' => $this->membresiaMensual->id,
            'monto_pagado_usd' => 30.00,
            'metodo_pago' => 'USDT (Binance / Cripto)',
            'referencia_pago' => 'TXID-USDT-987654',
            'tipo_comprobante' => 'factura',
        ]);
        $responseFactura1->assertRedirect(route('admin.payments.index'));

        $ordenFactura1 = Order::orderByDesc('id')->first();
        $this->assertEquals('factura', $ordenFactura1->tipo_comprobante);
        $this->assertEquals('FAC-000001', $ordenFactura1->numero_factura);
        $this->assertEquals('00-000001', $ordenFactura1->numero_control);
        $this->assertNull($ordenFactura1->numero_comprobante);

        // 2. Registro con Comprobante (CMP-000001)
        $responseComprobante1 = $this->post(route('admin.payments.store'), [
            'usuario_id' => $cliente->id,
            'membresia_id' => $this->membresiaMensual->id,
            'monto_pagado_usd' => 30.00,
            'metodo_pago' => 'Pago Móvil',
            'tipo_comprobante' => 'comprobante',
        ]);
        $responseComprobante1->assertRedirect(route('admin.payments.index'));

        $ordenComprobante1 = Order::orderByDesc('id')->first();
        $this->assertEquals('comprobante', $ordenComprobante1->tipo_comprobante);
        $this->assertEquals('CMP-000001', $ordenComprobante1->numero_comprobante);
        $this->assertNull($ordenComprobante1->numero_factura);

        // 3. Segunda Factura Fiscal (debe ser FAC-000002 independiente del comprobante emitido)
        $this->post(route('admin.payments.store'), [
            'usuario_id' => $cliente->id,
            'membresia_id' => $this->membresiaMensual->id,
            'monto_pagado_usd' => 30.00,
            'metodo_pago' => 'Efectivo USD',
            'tipo_comprobante' => 'factura',
        ]);
        $ordenFactura2 = Order::orderByDesc('id')->first();
        $this->assertEquals('FAC-000002', $ordenFactura2->numero_factura);
        $this->assertEquals('00-000002', $ordenFactura2->numero_control);

        // 4. Segundo Comprobante (debe ser CMP-000002 independiente de las facturas)
        $this->post(route('admin.payments.store'), [
            'usuario_id' => $cliente->id,
            'membresia_id' => $this->membresiaMensual->id,
            'monto_pagado_usd' => 30.00,
            'metodo_pago' => 'Transferencia',
            'tipo_comprobante' => 'comprobante',
        ]);
        $ordenComprobante2 = Order::orderByDesc('id')->first();
        $this->assertEquals('CMP-000002', $ordenComprobante2->numero_comprobante);

        // 5. Vista imprimible de factura
        $responseInvoice = $this->get(route('admin.payments.invoice', $ordenFactura1));
        $responseInvoice->assertStatus(200);
        $responseInvoice->assertSee('GYM RD SPORT');
        $responseInvoice->assertSee('Factura Fiscal');
        $responseInvoice->assertSee('FAC-000001');
        $responseInvoice->assertSee('00-000001');

        // 6. Centro de Reportes Gerenciales
        $responseReports = $this->get(route('admin.reports.index'));
        $responseReports->assertStatus(200);
        $responseReports->assertSee('Centro de Reportes y Auditoría');
        $responseReports->assertSee('Financiero y Facturación');
    }

    /**
     * Gestión de Membresías: 1 mes, VIP, Estándar y tarifas para clientes
     */
    public function test_admin_can_manage_memberships_including_vip_and_duration(): void
    {
        $admin = User::factory()->create([
            'roles_id' => $this->adminRol->id,
            'rol' => 'admin',
        ]);

        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
        ]);

        // 1. Cliente no puede acceder
        $this->actingAs($cliente);
        $responseForbidden = $this->get(route('admin.memberships.index'));
        $responseForbidden->assertStatus(403);

        // 2. Admin accede al módulo de membresías
        $this->actingAs($admin);
        $responseIndex = $this->get(route('admin.memberships.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Gestión de Membresías');

        // 3. Admin crea Membresía de 1 Mes VIP
        $responseCreateVip = $this->post(route('admin.memberships.store'), [
            'nombre' => 'Plan Mensual VIP Gold',
            'costo_base' => 60.00,
            'duracion_dias' => 30,
            'tipo' => 'vip',
            'es_vip' => 1,
            'descripcion' => '1 mes con acceso a sauna, toallas y entrenador personalizado.',
            'activo' => 1,
        ]);
        $responseCreateVip->assertRedirect(route('admin.memberships.index'));

        $this->assertDatabaseHas('membresias', [
            'nombre' => 'Plan Mensual VIP Gold',
            'costo_base' => 60.00,
            'duracion_dias' => 30,
            'tipo' => 'vip',
            'es_vip' => 1,
        ]);

        $vipPlan = Membresia::where('nombre', 'Plan Mensual VIP Gold')->first();
        $this->assertTrue($vipPlan->isVip());
        $this->assertEquals('1 Mes (30 días)', $vipPlan->duracion_texto);

        // 4. Admin actualiza la membresía
        $responseUpdate = $this->put(route('admin.memberships.update', $vipPlan), [
            'nombre' => 'Plan Mensual VIP Platinum',
            'costo_base' => 65.00,
            'duracion_dias' => 30,
            'tipo' => 'vip',
            'es_vip' => 1,
            'descripcion' => 'Actualizado a Platinum con casillero exclusivo.',
            'activo' => 1,
        ]);
        $responseUpdate->assertRedirect(route('admin.memberships.index'));

        $this->assertDatabaseHas('membresias', [
            'id' => $vipPlan->id,
            'nombre' => 'Plan Mensual VIP Platinum',
            'costo_base' => 65.00,
        ]);

        // 5. Admin conmuta el estado de la membresía (toggle activo)
        $responseToggle = $this->post(route('admin.memberships.toggle', $vipPlan));
        $responseToggle->assertRedirect(route('admin.memberships.index'));
        $this->assertFalse((bool) $vipPlan->fresh()->activo);
    }

    /**
     * Gestión de Métodos de Pago: CRUD, activación/pausa y visualización en caja
     */
    public function test_admin_can_manage_payment_methods(): void
    {
        $admin = User::factory()->create([
            'roles_id' => $this->adminRol->id,
            'rol' => 'admin',
        ]);

        $cliente = User::factory()->create([
            'roles_id' => $this->clienteRol->id,
            'rol' => 'cliente',
        ]);

        // 1. Cliente no autorizado
        $this->actingAs($cliente);
        $responseForbidden = $this->get(route('admin.payment-methods.index'));
        $responseForbidden->assertStatus(403);

        // 2. Admin accede al módulo de métodos de pago
        $this->actingAs($admin);
        $responseIndex = $this->get(route('admin.payment-methods.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Métodos de Pago');

        // 3. Crear nuevo método (ej: Binance Pay USDT)
        $responseStore = $this->post(route('admin.payment-methods.store'), [
            'nombre' => 'Binance Pay USDT',
            'codigo' => 'binance_pay_usdt',
            'tipo' => 'cripto',
            'moneda' => 'USDT',
            'instrucciones' => 'ID de Binance Pay: 99887766. Confirmar TxID en caja.',
            'requiere_referencia' => 1,
            'activo' => 1,
            'orden' => 1,
        ]);
        $responseStore->assertRedirect(route('admin.payment-methods.index'));

        $this->assertDatabaseHas('payment_methods', [
            'codigo' => 'binance_pay_usdt',
            'nombre' => 'Binance Pay USDT',
            'moneda' => 'USDT',
            'tipo' => 'cripto',
        ]);

        $metodo = PaymentMethod::where('codigo', 'binance_pay_usdt')->first();
        $this->assertTrue($metodo->isCripto());
        $this->assertTrue($metodo->requiere_referencia);

        // 4. Actualizar método de pago
        $responseUpdate = $this->put(route('admin.payment-methods.update', $metodo), [
            'nombre' => 'Binance Pay USDT VIP',
            'codigo' => 'binance_pay_usdt',
            'tipo' => 'cripto',
            'moneda' => 'USDT',
            'instrucciones' => 'Instrucción actualizada para miembros.',
            'requiere_referencia' => 1,
            'activo' => 1,
            'orden' => 2,
        ]);
        $responseUpdate->assertRedirect(route('admin.payment-methods.index'));
        $this->assertDatabaseHas('payment_methods', [
            'id' => $metodo->id,
            'nombre' => 'Binance Pay USDT VIP',
        ]);

        // 5. Toggle de estado (activar / pausar)
        $responseToggle = $this->post(route('admin.payment-methods.toggle', $metodo));
        $responseToggle->assertRedirect(route('admin.payment-methods.index'));
        $this->assertFalse((bool) $metodo->fresh()->activo);

        // 6. Eliminar método de pago sin órdenes
        $responseDelete = $this->delete(route('admin.payment-methods.destroy', $metodo));
        $responseDelete->assertRedirect(route('admin.payment-methods.index'));
        $this->assertDatabaseMissing('payment_methods', [
            'id' => $metodo->id,
        ]);
    }
}


