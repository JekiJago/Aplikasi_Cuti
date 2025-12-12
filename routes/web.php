<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminEmployeeController;
use App\Http\Controllers\AdminSettingController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Register hanya untuk admin create employee
    Route::get('/register', function () {
        return redirect()->route('login');
    })->name('register');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard utama (untuk semua user) - FIX INI!
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Email Verification Route (FIX ERROR)
    |--------------------------------------------------------------------------
    */
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    /*
    |--------------------------------------------------------------------------
    | Employee Leave Requests
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:employee')->group(function () {
        Route::resource('leave-requests', LeaveRequestController::class)
            ->except(['edit', 'update'])
            ->middleware('leave.owner');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Panel
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin - TAMBAHKAN INI!
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Leaves Management
        Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/{id}', [AdminLeaveController::class, 'show'])->name('leaves.show');
        Route::get('/leaves/{leave}/attachment', [AdminLeaveController::class, 'attachment'])->name('leaves.attachment');
        Route::post('/leaves/{id}/approve', [AdminLeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{id}/reject', [AdminLeaveController::class, 'reject'])->name('leaves.reject');

        // Employees Management
        Route::resource('employees', AdminEmployeeController::class);
        
        // TAMBAH ROUTE UNTUK RESET PASSWORD
        Route::post('/employees/{employee}/reset-password', [AdminEmployeeController::class, 'resetPassword'])
            ->name('employees.reset-password');

        // Settings Holidays
        Route::get('/settings/holidays', [AdminSettingController::class, 'holidays'])->name('settings.holidays');
        Route::post('/settings/holidays', [AdminSettingController::class, 'storeHoliday'])->name('settings.holidays.store');
        Route::delete('/settings/holidays/{holiday}', [AdminSettingController::class, 'destroyHoliday'])->name('settings.holidays.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Notification Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});