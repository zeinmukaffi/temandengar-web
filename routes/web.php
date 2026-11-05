<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::view('/', 'public.home')->name('home');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

// Protected Dashboard
Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});