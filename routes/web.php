<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ImportController;
use App\Http\Controllers\Frontend\AttendanceController;
use App\Http\Controllers\Frontend\ReportsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\DepartmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/import', [ImportController::class,'index'])->name('import.index');
    Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance.index');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/import/preview', [ImportController::class,'preview'])->name('import.preview');
    Route::post('/import/process', [ImportController::class,'process'])->name('import.process');
    Route::post('/attendance/classify', [AttendanceController::class,'classify'])->name('attendance.classify');

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

    Route::prefix('attendance')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::get('/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    });

    Route::get('/profile', function () {
    return view('dashboard.profile');
    })->name('profile');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Route::resource('departments', DepartmentController::class);

    Route::prefix('attendance')->group(function () {
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/checkin', [AttendanceController::class, 'showCheckin'])->name('checkin');
    Route::post('/checkin', [AttendanceController::class, 'checkin'])->name('checkin.store');
    Route::post('/checkout', [AttendanceController::class, 'checkout'])->name('checkout.store');
    Route::get('/export', [AttendanceController::class, 'export'])->name('export');
    Route::get('/export', [AttendanceController::class, 'export'])->name('attendance.export');
    Route::get('/{id}', [AttendanceController::class, 'show'])->name('show');
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::get('/export', [AttendanceController::class, 'export'])->name('attendance.export');
    });

    Route::get('/attendance/checkin', [AttendanceController::class, 'showCheckin'])->name('attendance.showCheckin');
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::get('/attendance/checkout', [AttendanceController::class, 'showCheckout'])->name('attendance.showCheckout');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
});

require __DIR__.'/auth.php';
