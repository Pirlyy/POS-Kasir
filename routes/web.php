<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'handleLogin'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');