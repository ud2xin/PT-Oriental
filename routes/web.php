<?php

use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ImportController;
use App\Http\Controllers\Frontend\AttendanceController;
use App\Http\Controllers\Frontend\ReportsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\DepartmentController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Protected routes (auth middleware)
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // IMPORT
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/preview', [ImportController::class, 'preview'])->name('import.preview');
    Route::post('/import/process', [ImportController::class, 'process'])->name('import.process');

    // REPORTS
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

    // USERS
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // DEPARTMENTS
    Route::resource('departments', DepartmentController::class);

    // PROFILE & SETTINGS
    Route::get('/profile', fn() => view('dashboard.profile'))->name('profile');
    Route::get('/settings', fn() => view('settings.index'))->name('settings.index');

    // ATTENDANCE
    Route::prefix('attendance')->group(function () {
        // Attendance index / history
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');

        // CHECKIN
        Route::get('/checkin', [AttendanceController::class, 'showCheckin'])->name('attendance.checkin.show');
        Route::post('/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin.post');

        // CHECKOUT
        Route::get('/checkout', [AttendanceController::class, 'showCheckout'])->name('attendance.checkout.show');
        Route::post('/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout.post');

        // EXPORT
        Route::get('/export', [AttendanceController::class, 'export'])->name('attendance.export');

        // SHOW DETAIL
        Route::get('/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    });
});

require __DIR__.'/auth.php';
