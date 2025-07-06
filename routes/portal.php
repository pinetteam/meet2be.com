<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\Dashboard\DashboardController;
use App\Http\Controllers\Portal\User\UserController;
use App\Http\Controllers\Portal\Profile\ProfileController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::resource('user', UserController::class);