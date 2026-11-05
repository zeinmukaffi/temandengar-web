<?php

use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\ConsultationRequestController;
use Illuminate\Support\Facades\Route;

// ========================================
// PUBLIC WEBSITE
// ========================================

Route::view('/', 'public.home')->name('home');

Route::get('/konsultasi', [
    ConsultationRequestController::class,
    'create'
])->name('consultations.create');

Route::post('/konsultasi', [
    ConsultationRequestController::class,
    'store'
])
    ->middleware('throttle:5,1')
    ->name('consultations.store');

Route::get('/konsultasi/berhasil', [
    ConsultationRequestController::class,
    'success'
])->name('consultations.success');


// ========================================
// AUTHENTICATION
// ========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        LoginController::class,
        'create'
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'store'
    ])
        ->middleware('throttle:5,1')
        ->name('login.store');

});


// ========================================
// COUNSELOR DASHBOARD
// ========================================

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');

    Route::post('/logout', [
        LoginController::class,
        'destroy'
    ])->name('logout');


    // Consultation Management

    Route::get('/dashboard/permintaan', [
        ConsultationController::class,
        'index'
    ])->name('admin.consultations.index');

    Route::get('/dashboard/permintaan/{consultationRequest}', [
        ConsultationController::class,
        'show'
    ])->name('admin.consultations.show');

    Route::patch('/dashboard/permintaan/{consultationRequest}/status', [
        ConsultationController::class,
        'updateStatus'
    ])->name('admin.consultations.update-status');

    Route::delete('/dashboard/permintaan/{consultationRequest}', [
        ConsultationController::class,
        'destroy'
    ])->name('admin.consultations.destroy');

    // ========================================
// SCHEDULE MANAGEMENT
// ========================================

    Route::get('/dashboard/jadwal', [
        ScheduleController::class,
        'index'
    ])->name('admin.schedules.index');

    // Create schedule from consultation request
    Route::get('/dashboard/permintaan/{consultationRequest}/jadwal/create', [
        ScheduleController::class,
        'create'
    ])->name('admin.schedules.create');

    Route::post('/dashboard/permintaan/{consultationRequest}/jadwal', [
        ScheduleController::class,
        'store'
    ])->name('admin.schedules.store');

    // Edit existing schedule
    Route::get('/dashboard/jadwal/{consultation}/edit', [
        ScheduleController::class,
        'edit'
    ])->name('admin.schedules.edit');

    Route::put('/dashboard/jadwal/{consultation}', [
        ScheduleController::class,
        'update'
    ])->name('admin.schedules.update');

    // Complete schedule
    Route::patch('/dashboard/jadwal/{consultation}/complete', [
        ScheduleController::class,
        'complete'
    ])->name('admin.schedules.complete');

    // Cancel schedule
    Route::patch('/dashboard/jadwal/{consultation}/cancel', [
        ScheduleController::class,
        'cancel'
    ])->name('admin.schedules.cancel');

});