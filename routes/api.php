<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CallController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/registration', [LoginController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('point')->group(function () {
        Route::post('/', [PointController::class, 'store']);
        Route::get('/{point}', [PointController::class, 'showPoint']);
        Route::patch('/{point}', [PointController::class, 'updatePoint']);
        Route::delete('/{point}', [PointController::class, 'destroy']);
        Route::get('/mypoint', [PointController::class, 'showMyPoint']);
        Route::post('/nearest', [PointController::class, 'showNearPoints']);

        Route::prefix('/description')->group(function () {
            Route::get('/{point}', [PointController::class, 'showDescription']);
            Route::patch('/{point}', [PointController::class, 'updatePointDescription']);
        });
    });

    Route::prefix('call')->group(function () {
        Route::get('/send/{point}', [CallController::class, 'sendCall']);
        Route::get('/unsend/{point}', [CallController::class, 'unsendCall']);
        Route::get('/approve/{call}', [CallController::class, 'approveCall']);
        Route::get('/decline/{call}', [CallController::class, 'declineCall']);
        Route::get('/received', [CallController::class, 'showCallsForMe']);
        Route::get('/sent', [CallController::class, 'showMyCalls']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [ProfileController::class, 'getMyProfile']);
        Route::get('/{id}', [ProfileController::class, 'getProfile']);
        Route::patch('/', [ProfileController::class, 'updateMyProfile']);
    });
});
