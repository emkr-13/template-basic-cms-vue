<?php

use App\Http\Controllers\ApiCredentialController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login')->name('login.store');
    Route::get('/forgot-password', [PasswordController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'email'])->middleware('throttle:password-reset')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'update'])->name('password.update');
});

Route::middleware(['auth', 'force.password.change'])->group(function (): void {
    Route::get('/', fn () => Inertia::render('Home'))->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'editChangePassword'])->name('password.change.edit');
    Route::put('/change-password', [PasswordController::class, 'changePassword'])->name('password.change.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:role.view')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:role.create')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:role.create')->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:role.update')->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:role.update')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:role.delete')->name('roles.destroy');

    Route::get('/users', [UserController::class, 'index'])->middleware('permission:user.view')->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:user.create')->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:user.create')->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:user.update')->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:user.update')->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:user.delete')->name('users.destroy');
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->middleware('permission:user.export.pdf')->name('users.export.pdf');
    Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->middleware('permission:user.export.excel')->name('users.export.excel');

    Route::middleware('role:super_admin')->group(function (): void {
        Route::get('/api-credentials', [ApiCredentialController::class, 'index'])->name('api-credentials.index');
        Route::post('/api-credentials', [ApiCredentialController::class, 'store'])->name('api-credentials.store');
        Route::delete('/api-credentials/{apiCredential}', [ApiCredentialController::class, 'destroy'])->name('api-credentials.destroy');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
