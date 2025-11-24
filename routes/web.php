<?php

use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Frontend\AttendanceController;
use App\Http\Controllers\Frontend\ReportsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\DepartmentController;
use App\Http\Controllers\Frontend\EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Protected routes
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
    Route::resource('users', UserController::class);

    // DEPARTMENTS
    Route::resource('departments', DepartmentController::class);

    // PROFILE
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update'); // update foto & data
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    // SETTINGS
    Route::get('/settings', fn() => view('settings.index'))->name('settings.index');

    // ATTENDANCE
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/checkin', [AttendanceController::class, 'showCheckin'])->name('attendance.checkin.show');
        Route::post('/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin.post');
        Route::get('/checkout', [AttendanceController::class, 'showCheckout'])->name('attendance.checkout.show');
        Route::post('/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout.post');
        Route::get('/export', [AttendanceController::class, 'export'])->name('attendance.export');
        Route::get('/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    });

    // EMPLOYEES
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');

    //Overtime
    Route::get('/overtime', [App\Http\Controllers\Frontend\OvertimeController::class, 'index']);
});

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

require __DIR__ . '/auth.php';
