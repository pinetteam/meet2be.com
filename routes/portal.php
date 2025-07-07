<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\Dashboard\DashboardController;
use App\Http\Controllers\Portal\User\UserController;
use App\Http\Controllers\Portal\Setting\SettingController;
use App\Http\Controllers\Portal\Profile\ProfileController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::resource('users', UserController::class)->names('user');
Route::resource('profile', ProfileController::class)->only(['index', 'update']);

Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
Route::put('/settings', [SettingController::class, 'update'])->name('setting.update');
