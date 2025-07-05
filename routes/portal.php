<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\Dashboard\DashboardController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
}); 