<?php

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

// Test route pour diagnostiquer les erreurs
Route::get('/install-test', function () {
    return view('install.test', [
        'message' => 'Test installateur CloudPanel',
        'currentStep' => 1,
        'steps' => [
            1 => 'integrity',
            2 => 'requirements', 
            3 => 'database',
            4 => 'admin',
            5 => 'retell',
            6 => 'validation',
            7 => 'finish'
        ],
        'totalSteps' => 7
    ]);
});

// Installation routes
Route::middleware('guest')->group(function () {
    Route::get('/install', [InstallController::class, 'index'])->name('install');
    Route::post('/install', [InstallController::class, 'install'])->name('install.process');
    Route::get('/install/check-requirements', [InstallController::class, 'checkRequirementsApi']);
    Route::post('/install/test-database', [InstallController::class, 'testDatabase']);
});

// Middleware to check if installation is completed
Route::middleware(['installation.check'])->group(function () {
    
    // Redirect root to admin
    Route::get('/', function () {
        return redirect('/admin');
    });
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
});

// Health check
Route::get('/up', function () {
    return response()->json(['status' => 'ok']);
});