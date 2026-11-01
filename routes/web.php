<?php

use App\Http\Controllers\Admin\AccessKioskController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Trainer\TrainerDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerificarRolAdministrador;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/ejemplo', [HomeController::class, 'index'])->name('ejemplo');

// Rutas para usuarios invitados (no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/notifications/{notificacion}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Panel de Operaciones de Entrenador (Módulo C)
    Route::prefix('trainer')
        ->name('trainer.')
        ->group(function () {
            Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
            Route::post('/toggle-shift', [TrainerDashboardController::class, 'toggleShift'])->name('toggle-shift');
            Route::get('/athletes/{athlete}', [TrainerDashboardController::class, 'showAthlete'])->name('athletes.show');
            Route::post('/routines', [TrainerDashboardController::class, 'storeRoutine'])->name('routines.store');
            Route::delete('/routines/{routine}', [TrainerDashboardController::class, 'destroyRoutine'])->name('routines.destroy');
        });

    // Panel de administración (exclusivo para administradores)
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(VerificarRolAdministrador::class)
        ->group(function () {
            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
            Route::post('/entrenadores/reasignar', [AdminController::class, 'reasignarEntrenador'])->name('entrenadores.reasignar');

            // Gestión de usuarios y reasignación de entrenadores (RFC-03 / TRX-02)
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::post('/users/reassign-trainer', [AdminUserController::class, 'reassignTrainer'])->name('users.reassign-trainer');
            Route::post('/users/reassign-bulk', [AdminUserController::class, 'reassignBulk'])->name('users.reassign-bulk');

            // Gestión de entrenadores
            Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
            Route::post('/trainers', [TrainerController::class, 'store'])->name('trainers.store');
            Route::post('/trainers/promote', [TrainerController::class, 'promoteClient'])->name('trainers.promote');
            Route::put('/trainers/{trainer}', [TrainerController::class, 'update'])->name('trainers.update');
            Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
            Route::post('/trainers/assign-client', [TrainerController::class, 'assignClient'])->name('trainers.assign-client');
            Route::post('/trainers/unassign-client', [TrainerController::class, 'unassignClient'])->name('trainers.unassign-client');

            // Asistencia del personal
            Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

            // Módulo de Pagos y Facturación Multimoneda (RFC-02, TRX-01)
            Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
            Route::get('/payments/{order}/invoice', [PaymentController::class, 'showInvoice'])->name('payments.invoice');

            // Gestión de Planes de Membresía (1 Mes, VIP, Estándar, etc.)
            Route::resource('memberships', MembershipController::class)->except(['create', 'show', 'edit']);
            Route::post('memberships/{membership}/toggle', [MembershipController::class, 'toggleStatus'])->name('memberships.toggle');

            // Gestión de Métodos de Pago (USDT, Pago Móvil, Zelle, Efectivo, etc.)
            Route::resource('payment-methods', PaymentMethodController::class)->except(['create', 'show', 'edit']);
            Route::post('payment-methods/{paymentMethod}/toggle', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle');

            // Centro de Reportes Gerenciales y Operativos
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

            // Marcaje de Asistencia y Kiosco de Acceso (TRX-03, RFC-04)
            Route::get('/access/kiosk', [AccessKioskController::class, 'index'])->name('access.kiosk');
            Route::post('/access/scan', [AccessKioskController::class, 'scan'])->name('access.scan');

            // Planificación de Servicios y Horarios
            Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
            Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
            Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
            Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        });
});
