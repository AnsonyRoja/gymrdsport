# 🏋️ GYM RD SPORT, C.A. - Sistema Integral de Gestión Deportiva y Administrativa

Sistema web de alto rendimiento desarrollado con **Laravel**, **MySQL** y **Tailwind CSS** para la administración integral, control de acceso físico automatizado, cobranza multimoneda a tasa oficial del BCV y supervisión técnica de atletas y entrenadores de **GYM RD SPORT, C.A.**

---

## 📁 1. Estructura del Proyecto

El proyecto está diseñado bajo una arquitectura limpia y desacoplada, separando la lógica de negocio en **Servicios de Dominio (`app/Services`)**, los puntos de entrada HTTP en **Controladores Especializados (`app/Http/Controllers`)** y las interfaces de usuario en plantillas **Blade modulares**:

```plaintext
gym-rd-sport/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── VerificarMorosidadCommand.php       # [RFC-01] Cron para marcar morosos y bloquear accesos
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AccessKioskController.php      # [TRX-03] Controlador del kiosco/torniquete de acceso
│   │   │   │   ├── AttendanceController.php       # Supervisión y registro de asistencia del personal
│   │   │   │   ├── MembershipController.php       # Gestión y planes de membresía (1 mes, VIP, estándar)
│   │   │   │   ├── PaymentController.php          # [TRX-01] Gestión de pagos, facturas y comprobantes
│   │   │   │   ├── PaymentMethodController.php    # Gestión de canales de pago (USDT, Pago Móvil, Zelle, etc.)
│   │   │   │   ├── ReportController.php           # Módulo de 4 reportes gerenciales imprimibles
│   │   │   │   ├── ServiceController.php          # Horarios de clases y servicios del gimnasio
│   │   │   │   ├── TrainerController.php          # Nómina técnica y asignación de atletas
│   │   │   │   └── UserController.php             # [TRX-02] Clientes y reasignación de entrenadores
│   │   │   ├── Trainer/
│   │   │   │   └── TrainerDashboardController.php # Panel operativo de entrenadores y rutinas
│   │   │   ├── AdminController.php                # Métricas globales del panel administrativo
│   │   │   ├── AuthController.php                 # Autenticación y redirección según rol
│   │   │   └── DashboardController.php            # Panel exclusivo de atletas (medidas corporales)
│   │   └── Middleware/
│   │       ├── BloquearAccesoMoroso.php           # Middleware de rechazo para clientes con deuda
│   │       └── VerificarRolAdministrador.php      # Seguridad de rutas para administradores
│   ├── Models/
│   │   ├── Attendance.php                         # Asistencias en sala y marcajes de torniquete
│   │   ├── Membresia.php                          # Planes deportivos y costos base
│   │   ├── MembresiaUsuario.php                   # Suscripciones y vigencias acumulativas
│   │   ├── Notificacion.php                       # Avisos internos a atletas
│   │   ├── Order.php                              # Órdenes de pago, facturas y comprobantes
│   │   ├── PaymentMethod.php                      # Métodos y pasarelas de pago (USDT, Pago Móvil, Efectivo)
│   │   ├── Rol.php                                # Catálogo de roles
│   │   ├── Rutina.php                             # Ejercicios y rutinas prescritas
│   │   ├── Service.php                            # Clases grupales y servicios
│   │   └── User.php                               # Usuarios (Admin, Entrenador, Cliente)
│   └── Services/
│       ├── AsignacionEntrenadorServicio.php       # [TRX-02] Lógica transaccional de reasignación
│       ├── ControlAccesoServicio.php              # [TRX-03] Validación de acceso y check-in/out
│       ├── PagoMembresiaServicio.php              # [TRX-01] Cobranza multimoneda y correlativos
│       └── TasaBcvServicio.php                    # [RFC-02] Consulta y cálculo de tasa oficial BCV
├── database/
│   ├── migrations/                                # Esquema de base de datos relacional
│   └── seeders/                                   # Datos iniciales (roles, admin, membresías)
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── access/kiosk.blade.php             # Terminal de escaneo para torniquete físico
│       │   ├── attendance/index.blade.php         # Historial de turnos y asistencias del personal
│       │   ├── memberships/index.blade.php        # Gestión de membresías (1 mes, VIP, estándar, tarifas)
│       │   ├── payment_methods/index.blade.php    # Gestión de canales de cobro y datos bancarios/wallet
│       │   ├── payments/
│       │   │   ├── index.blade.php                # Panel de cobros y modal de Factura/Comprobante
│       │   │   └── invoice.blade.php              # Vista imprimible formal de Factura o Recibo
│       │   ├── reports/index.blade.php            # Centro de 4 reportes con filtros y exportación PDF
│       │   ├── services/index.blade.php           # Gestión de servicios y clases del gimnasio
│       │   ├── trainers/index.blade.php           # Gestión de entrenadores y atletas asignados
│       │   ├── users/index.blade.php              # Gestión de clientes y reasignación masiva
│       │   └── dashboard.blade.php                # Métricas globales de administración
│       ├── layouts/
│       │   └── admin.blade.php                    # Plantilla base con navegación por roles
│       ├── trainer/
│       │   ├── dashboard.blade.php                # Panel técnico del entrenador
│       │   └── athletes/show.blade.php            # Prescripción semanal de rutinas al atleta
│       └── dashboard.blade.php                    # Mi Panel de Atleta (peso, estatura, IMC, rutinas)
├── routes/
│   ├── console.php                                # Programación del comando de morosidad
│   └── web.php                                    # Definición de rutas protegidas y públicas
└── tests/
    └── Feature/
        ├── AuthenticationTest.php                 # Pruebas de autenticación y redirección
        └── GymTechnicalAnalysisTest.php           # Pruebas de integración de las transacciones (TRX)
```

---

## ⚡ 2. Mención y Especificación de las Transacciones (TRX)

En concordancia con el documento de análisis técnico del gimnasio, las operaciones críticas se ejecutan bajo transacciones atómicas de base de datos (`DB::transaction`) para asegurar consistencia ACID:

### 🔹 TRX-01: Generación y Pago de Orden de Membresía
- **Servicio:** `App\Services\PagoMembresiaServicio::generarYProcesarOrden()`
- **Controlador:** `App\Http\Controllers\Admin\PaymentController@store`
- **Operaciones Atómicas Realizadas:**
  1. **Conversión Cambiaria Obligatoria (RFC-02):** Consulta la tasa legal vigente del **Banco Central de Venezuela (BCV)** para tasar el costo de la membresía en USD y su contravalor en Bolívares (VES).
  2. **Registro de la Orden Multimoneda:** Admite pagos en **USDT (Binance Cripto con TxID/Hash)**, **Pago Móvil**, **Efectivo USD/Bs**, **Zelle**, **Transferencia** y **Punto de Venta**.
  3. **Series Numéricas 100% Independientes:**
     - **Factura Fiscal (`numero_factura`):** Asigna una numeración fiscal correlativa (`FAC-000001`, `FAC-000002`...) junto con su **Número de Control Fiscal SENIAT (`00-000001`...)**. Ningún comprobante de caja altera esta secuencia.
     - **Comprobante de Pago (`numero_comprobante`):** Asigna una serie administrativa separada (`CMP-000001`, `CMP-000002`...) para recibos de control interno de caja.
  4. **Prórroga Acumulativa de Membresía:** Si el atleta tiene una suscripción vigente, la nueva fecha de vencimiento se suma a partir de la fecha fin existente; si no, inicia desde el día actual.
  5. **Levantamiento Inmediato de Morosidad:** Actualiza el estatus del cliente a `activo`, habilitando de inmediato su paso en el torniquete.
  6. **Documento Imprimible:** Genera el documento con membrete legal de **GYM RD SPORT, C.A. (RIF: J-50123894-0)** accesible en `/admin/payments/{order}/invoice`.

---

### 🔹 TRX-02: Reasignación de Entrenador y Transferencia Histórica de Rutinas
- **Servicio:** `App\Services\AsignacionEntrenadorServicio::reasignarEntrenador()` / `reasignarMasivamente()`
- **Controlador:** `App\Http\Controllers\Admin\UserController` (`reassignTrainer` y `reassignBulk`)
- **Operaciones Atómicas Realizadas:**
  1. **Cierre de Asignación Previa:** Marca la relación en `entrenador_cliente` como inactiva estableciendo `fecha_desvinculacion`, preservando la auditoría de quién entrenó al atleta en el pasado.
  2. **Vinculación con el Nuevo Entrenador:** Inserta el nuevo registro activo con fecha de asignación actual.
  3. **Clonación y Transferencia de Rutinas:** Localiza todas las rutinas activas del atleta y las traspasa al nuevo entrenador, garantizando que el nuevo preparador físico mantenga los ejercicios, repeticiones y cargas sin interrupciones.
  4. **Notificación al Atleta:** Genera una notificación en la base de datos para que el atleta vea en su panel quién es su nuevo entrenador responsable.

---

### 🔹 TRX-03: Marcaje de Asistencia y Verificación de Acceso en Kiosco / Torniquete
- **Servicio:** `App\Services\ControlAccesoServicio::procesarMarcaje()`
- **Controlador:** `App\Http\Controllers\Admin\AccessKioskController@scan`
- **Operaciones Atómicas Realizadas:**
  1. **Identificación Unificada:** Procesa en una sola entrada Cédula, Correo o Código ID del usuario.
  2. **Flujo de Cliente / Atleta:**
     - Valida vigencia de membresía.
     - Si está solvente: registra el acceso en `attendances` con resultado `autorizado` y habilita el paso del torniquete.
     - Si está moroso o vencido: bloquea el paso registrando resultado `denegado`, emite alerta visual y rechaza la entrada (RFC-01).
  3. **Flujo de Entrenador / Personal:**
     - Primer marcaje del día: Compara contra la hora de turno programada; si tiene más de 15 minutos de diferencia registra `Retardo`, o `Puntual` si llegó a tiempo (RFC-04).
     - Segundo marcaje del día: Registra la hora de salida con estatus `Turno Cumplido`.

---

## 📌 3. Reglas de Negocio Implementadas (RFC)

- **RFC-01: Verificación Automatizada de Morosidad:**
  Comando de consola `php artisan gym:verificar-morosidad` programado en `routes/console.php`. Evalúa a diario los clientes vencidos (respetando sus días de gracia) y actualiza su estado a `moroso`, bloqueando su paso por el torniquete.
- **RFC-02: Cobro Multimoneda a Tasa Oficial del BCV:**
  Cálculo de conversión a bolívares respaldado legalmente por `TasaBcvServicio`.
- **RFC-03: Balance de Carga de Atletas por Entrenador:**
  Monitoreo del número de atletas a cargo de cada preparador para evitar asimetrías de trabajo.
- **RFC-04: Supervisión de Asistencia y Cumplimiento de Turnos:**
  Auditoría de puntualidad, retardos e inasistencias del personal técnico en sala.

---

## 👥 4. Separación de Responsabilidades por Rol

1. **Administrador (`admin`):**
   - **Responsabilidad:** Administrar el negocio, finanzas, cobros, reportes, kiosco y personal.
   - **Restricción:** No tiene acceso al panel atlético (peso, kilogramos, estatura ni rutinas). En la barra de navegación visualiza exclusivamente el **Panel Administrativo** y módulos gerenciales. Al iniciar sesión se le redirige automáticamente a `/admin/dashboard`.
2. **Entrenador (`entrenador`):**
   - **Responsabilidad:** Gestión de su jornada laboral, revisión de atletas asignados y prescripción de rutinas técnicas.
   - **Acceso:** Panel de Entrenador (`/trainer/dashboard`).
3. **Cliente / Atleta (`cliente`):**
   - **Responsabilidad:** Registro de su ficha antropométrica (peso en kg, altura en cm, historial médico/lesiones), consulta de su membresía y seguimiento de sus rutinas diarias.
   - **Acceso:** Mi Panel de Atleta (`/dashboard`).

---

## 📊 5. Centro de Reportes Gerenciales (`/admin/reports`)

Permite auditar la operación del gimnasio con filtros dinámicos (*Hoy*, *Esta Semana*, *Este Mes*, *Año*) y botón de impresión / descarga en PDF con membrete institucional:
1. **Reporte Financiero y Recaudación:** Ingresos en USD y Bolívares (BCV), recaudación por método de pago (USDT, Pago Móvil, etc.), desglose comparativo de Facturas Fiscales emitidas vs Comprobantes de Pago.
2. **Reporte de Membresías y Morosidad (RFC-01):** Lista clasificada de morosos con días de vencimiento y botón de cobro rápido, junto al listado de membresías por vencer en los próximos 7 días.
3. **Reporte de Control de Acceso y Personal:** Flujo de validaciones en torniquete (accesos permitidos vs rebotados por morosidad) y control de puntualidad del personal.
4. **Reporte Técnico de Entrenadores:** Distribución de carga de atletas por entrenador y estatus de turno en sala.

---

## 🚀 6. Instalación y Puesta en Marcha

### 1. Clonar el Proyecto e Instalar Dependencias
```bash
git clone <url-del-repositorio>
cd "Gym rd sport"

composer install
npm install
```

### 2. Configurar Entorno
```bash
cp .env.example .env
php artisan key:generate
```
Configura tus credenciales de base de datos MySQL en `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ggymrdsport
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Ejecutar Migraciones y Seeders
```bash
php artisan migrate --seed
```

### 4. Iniciar Servidores de Desarrollo
```bash
# Servidor backend PHP
php artisan serve

# Compilador de assets Vite
npm run dev
```

---

## 🧪 7. Pruebas Automatizadas

El sistema cuenta con una batería de pruebas de integración que valida todas las transacciones técnicas:

```bash
php artisan test
```

### Resultados de la Suite de Pruebas:
```sh
PASS  Tests\Feature\AuthenticationTest
✓ login screen can be rendered
✓ users can authenticate using email
✓ users can authenticate using username
✓ users can not authenticate with invalid password
✓ users can logout
✓ unauthenticated users cannot access dashboard
✓ authenticated users can access dashboard
✓ admin is redirected to admin dashboard
✓ users can register

PASS  Tests\Feature\GymTechnicalAnalysisTest
✓ trx01 generacion y pago de orden membresia
✓ trx02 reasignacion automatica y transferencia de rutinas
✓ trx03 marcaje asistencia y acceso torniquete
✓ modulo b dashboard cliente y perfil fisico
✓ modulo c panel entrenador y rutinas
✓ rfc01 comando marca morosos automaticamente
✓ emision factura y comprobante con numeracion secuencial (FAC vs CMP)

Tests: 18 passed (75 assertions)
```