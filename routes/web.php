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
| ROOT ROUTE
|--------------------------------------------------------------------------
| - Belum login  → /login
| - Login admin  → /admin/dashboard
| - Login pegawai→ /dashboard
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PEGAWAI
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | LEAVE REQUESTS (PEGAWAI)
    |--------------------------------------------------------------------------
    */
    Route::prefix('leave-requests')
        ->name('leave-requests.')
        ->controller(LeaveRequestController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{leaveRequest}', 'show')->name('show');
            Route::delete('/{leaveRequest}', 'destroy')->name('destroy');
            
            // TAMBAHKAN ROUTES INI untuk approve/reject/cancel
            Route::post('/{leaveRequest}/approve', 'approve')->name('approve');
            Route::post('/{leaveRequest}/reject', 'reject')->name('reject');
            Route::post('/{leaveRequest}/cancel', 'cancel')->name('cancel');
        });

    /*
    |--------------------------------------------------------------------------
    | USER PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Dashboard Admin
            Route::get('/dashboard', [DashboardController::class, 'admin'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEES MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::prefix('employees')
                ->name('employees.')
                ->group(function () {
                    Route::get('/', [AdminEmployeeController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [AdminEmployeeController::class, 'create'])
                        ->name('create');

                    Route::post('/', [AdminEmployeeController::class, 'store'])
                        ->name('store');

                    Route::get('/{employee}', [AdminEmployeeController::class, 'show'])
                        ->name('show');

                    Route::get('/{employee}/edit', [AdminEmployeeController::class, 'edit'])
                        ->name('edit');

                    Route::put('/{employee}', [AdminEmployeeController::class, 'update'])
                        ->name('update');

                    Route::delete('/{employee}', [AdminEmployeeController::class, 'destroy'])
                        ->name('destroy');

                    // Reset quota dan password
                    Route::post('/{employee}/reset-quota', [AdminEmployeeController::class, 'resetQuota'])
                        ->name('reset-quota');

                    Route::post('/{employee}/reset-password', [AdminEmployeeController::class, 'resetPassword'])
                        ->name('reset-password');
                });

            /*
            |--------------------------------------------------------------------------
            | ADMIN LEAVE REQUESTS MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::prefix('leaves')
                ->name('leaves.')
                ->group(function () {
                    Route::get('/', [AdminLeaveController::class, 'index'])
                        ->name('index');

                    Route::get('/{leaveRequest}', [AdminLeaveController::class, 'show'])
                        ->name('show');

                    // ROUTE ATTACHMENT - YANG DITAMBAHKAN
                    Route::get('/{leaveRequest}/attachment', [AdminLeaveController::class, 'attachment'])
                        ->name('attachment');

                    Route::put('/{leaveRequest}/approve', [AdminLeaveController::class, 'approve'])
                        ->name('approve');

                    Route::put('/{leaveRequest}/reject', [AdminLeaveController::class, 'reject'])
                        ->name('reject');

                    Route::put('/{leaveRequest}/cancel', [AdminLeaveController::class, 'cancelApproval'])
                        ->name('cancel');

                    // Statistics (jika diperlukan)
                    Route::get('/statistics', [AdminLeaveController::class, 'statistics'])
                        ->name('statistics');

                    // Balance management
                    Route::get('/{user}/balance', [AdminLeaveController::class, 'viewBalance'])
                        ->name('balance.view');

                    Route::post('/{user}/adjust-quota', [AdminLeaveController::class, 'adjustQuota'])
                        ->name('balance.adjust');
                });

            /*
            |--------------------------------------------------------------------------
            | ADMIN SETTINGS
            |--------------------------------------------------------------------------
            */
            Route::prefix('settings')
                ->name('settings.')
                ->group(function () {
                    // General Settings
                    Route::get('/', [AdminSettingController::class, 'index'])
                        ->name('index');

                    Route::put('/general', [AdminSettingController::class, 'updateGeneral'])
                        ->name('update-general');

                    Route::put('/leave-types', [AdminSettingController::class, 'updateLeaveTypes'])
                        ->name('update-leave-types');

                    // Holidays Management
                    Route::prefix('holidays')->group(function () {
                        Route::get('/', [AdminSettingController::class, 'holidays'])
                            ->name('holidays');

                        Route::get('/create', [AdminSettingController::class, 'createHoliday'])
                            ->name('holidays.create');

                        Route::post('/', [AdminSettingController::class, 'storeHoliday'])
                            ->name('holidays.store');

                        Route::get('/{holiday}/edit', [AdminSettingController::class, 'editHoliday'])
                            ->name('holidays.edit');

                        Route::put('/{holiday}', [AdminSettingController::class, 'updateHoliday'])
                            ->name('holidays.update');

                        Route::delete('/{holiday}', [AdminSettingController::class, 'destroyHoliday'])
                            ->name('holidays.destroy');
                    });

                    // Work Schedule
                    Route::get('/work-schedule', [AdminSettingController::class, 'workSchedule'])
                        ->name('work-schedule');

                    Route::put('/work-schedule', [AdminSettingController::class, 'updateWorkSchedule'])
                        ->name('update-work-schedule');
                });
        });

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    Route::prefix('notifications')
        ->name('notifications.')
        ->group(function () {
            Route::get('/', [NotificationController::class, 'index'])
                ->name('index');

            Route::put('/{notification}/read', [NotificationController::class, 'markAsRead'])
                ->name('read');

            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])
                ->name('mark-all-read');
        });
});