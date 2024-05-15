<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('registration', [LoginController::class, 'registration'])->name('registration');
});

Route::name('points.')->prefix('point')->group(function () {
    Route::post('/', [PointController::class, 'store'])->name('store');
});
