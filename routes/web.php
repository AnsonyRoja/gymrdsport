<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

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

            // Planificación de Servicios y Horarios
            Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
            Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
            Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
            Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        });
});
