<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ImportController;
use App\Http\Controllers\Frontend\AttendanceController;
use App\Http\Controllers\Frontend\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/import', [ImportController::class,'index'])->name('import.index');
    Route::post('/import/preview', [ImportController::class,'preview'])->name('import.preview');
    Route::post('/import/process', [ImportController::class,'process'])->name('import.process');

    Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance.index');
    Route::post('/attendance/classify', [AttendanceController::class,'classify'])->name('attendance.classify');

    Route::get('/reports', [ReportController::class,'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class,'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
