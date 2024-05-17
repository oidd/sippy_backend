<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('registration', [LoginController::class, 'register'])->name('registration');
});

Route::post('sendMessage', function (Request $request) {
    $request->validate(
        [
            'message' => 'required',
        ]
    );

    \App\Events\MessageSent::dispatch($request->input('message'), $request->user());

    return response()->json('check chat to know if message sent', 200);

})->middleware(['auth:api']);

Route::prefix('point')->middleware('auth:api')->group(function () {
    Route::post('/', [PointController::class, 'store']);
    Route::get('/{id}', [PointController::class, 'showPoint']);
    Route::put('/{id}', [PointController::class, 'updatePoint']);
    Route::delete('/{id}', [PointController::class, 'destroy']);

    Route::post('/nearest', [PointController::class, 'showNearPoints']);

    Route::prefix('/description')->group(function () {
        Route::get('/description/{id}', [PointController::class, 'showDescription']);
        Route::put('/description/{id}', [PointController::class, 'updatePointDescription']);
    });
});

Route::get('testingg', function (Request $request) {
    $p = Broadcast::pusher(app()['config']['broadcasting.connections.reverb']);
    dd($p->getPresenceUsers('presence-messagechannel'));
});
