<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminEmployeeController;


// ✅ Guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ✅ Protected (sudah login)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee: Leave Requests
    Route::middleware('role:employee')->group(function () {
        Route::resource('leave-requests', LeaveRequestController::class)
            ->except(['edit', 'update'])
            ->middleware('leave.owner');
    });

    // Admin Panel
        Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves');
        Route::get('/leaves/{id}', [AdminLeaveController::class, 'show'])->name('leaves.show');
        Route::get('/leaves/{leave}/attachment', [AdminLeaveController::class, 'attachment'])->name('leaves.attachment');
        Route::post('/leaves/{id}/approve', [AdminLeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{id}/reject', [AdminLeaveController::class, 'reject'])->name('leaves.reject');

        Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('employees', AdminEmployeeController::class);
        });

        Route::get('/employees', [AdminEmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [AdminEmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [AdminEmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}', [AdminEmployeeController::class, 'show'])->name('employees.show');
        Route::delete('/employees/{id}', [AdminEmployeeController::class, 'destroy'])->name('employees.destroy');

        Route::get('/employees', [\App\Http\Controllers\AdminEmployeeController::class, 'index'])
            ->name('employees.index');

        
        Route::get('/settings/holidays', [\App\Http\Controllers\AdminSettingController::class, 'holidays'])
            ->name('settings.holidays');
        Route::post('/settings/holidays', [\App\Http\Controllers\AdminSettingController::class, 'storeHoliday'])
            ->name('settings.holidays.store');
        Route::delete('/settings/holidays/{holiday}', [\App\Http\Controllers\AdminSettingController::class, 'destroyHoliday'])
            ->name('settings.holidays.destroy');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Root redirect
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
