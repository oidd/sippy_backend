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

Route::name('points.')->prefix('point')->group(function () {
    Route::post('/', [PointController::class, 'store']);
    Route::get('/{id}', [PointController::class, 'show']);
});

Route::get('testingg', function (Request $request) {
    $p = Broadcast::pusher(app()['config']['broadcasting.connections.reverb']);
    dd($p->getPresenceUsers('presence-messagechannel'));
});
