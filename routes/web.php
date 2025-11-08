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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

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
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/checkin', [AttendanceController::class, 'storeCheckin'])->name('attendance.storeCheckin');
    Route::get('/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::post('/checkout', [AttendanceController::class, 'storeCheckout'])->name('attendance.storeCheckout');
    });
});



require __DIR__.'/auth.php';
