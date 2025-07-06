<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\Dashboard\DashboardController;
use App\Http\Controllers\Portal\User\UserController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::resource('user', UserController::class);
}); 