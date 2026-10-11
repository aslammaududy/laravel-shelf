<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthenticationController::class, 'login'])->middleware('throttle:10,1');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('auth/logout', [AuthenticationController::class, 'logout']);
    Route::get('me', [AuthenticationController::class, 'me']);
});
