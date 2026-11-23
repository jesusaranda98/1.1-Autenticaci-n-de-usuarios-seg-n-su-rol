<?php

use App\Http\Controllers\RoleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [RoleAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RoleAuthController::class, 'login'])->name('login.submit');

// Ruta temporal y segura para la etapa 3 del Administrador (no expuesta en vistas Blade)
Route::get('/acceso-seguro/verificacion-admin', [RoleAuthController::class, 'showVerifyForm'])->name('admin.2fa.challenge');
Route::post('/acceso-seguro/verificacion-admin', [RoleAuthController::class, 'verifyCode'])->name('admin.2fa.verify');
Route::post('/acceso-seguro/reenviar-codigo', [RoleAuthController::class, 'resendCode'])->name('admin.2fa.resend');

Route::post('/logout', [RoleAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:empleado'])->group(function () {
    Route::get('/empleado/dashboard', function () {
        return view('dashboards.empleado');
    })->name('empleado.dashboard');
});