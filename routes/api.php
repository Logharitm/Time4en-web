<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::put('update-profile', [AuthController::class, 'updateProfile']);
        Route::put('change-password', [AuthController::class, 'changePassword']);

        // ----------------------- Folders --------------------------------------------
        Route::get('folders', [FolderController::class, 'index']);
        Route::post('folders', [FolderController::class, 'store']);
        Route::get('folders/{folder}', [FolderController::class, 'show']);
        Route::put('folders/{folder}', [FolderController::class, 'update']);
        Route::delete('folders/{folder}', [FolderController::class, 'destroy']);


        // ----------------------- Words ----------------------------------------------
        Route::get('words', [WordController::class, 'index']);
        Route::post('words', [WordController::class, 'store']);
        Route::get('words/{word}', [WordController::class, 'show']);
        Route::put('words/{word}', [WordController::class, 'update']);
        Route::delete('words/{word}', [WordController::class, 'destroy']);

    });
});
