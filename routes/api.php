<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthenticationController::class, 'login'])->middleware('throttle:10,1')->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('auth/logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('me', [AuthenticationController::class, 'me'])->name('me');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('documents', DocumentController::class);
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');

    Route::apiResource('folders', FolderController::class);

    Route::get('tags', TagController::class);
});
