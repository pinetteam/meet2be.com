<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\Auth\LoginController;
use App\Http\Controllers\Site\Auth\LogoutController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
    Route::post('login', [LoginController::class, 'store'])->name('login.store')->middleware('guest');
    Route::post('logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');
}); 