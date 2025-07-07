<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\Dashboard\DashboardController;
use App\Http\Controllers\Portal\User\UserController;
use App\Http\Controllers\Portal\Profile\ProfileController;
use App\Http\Controllers\Portal\Settings\SettingsController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

// Users
Route::resource('user', UserController::class);