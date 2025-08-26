<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);

        // ----------------------- Folders --------------------------------------------
        Route::get('folders', [FolderController::class, 'index']);
        Route::post('folders', [FolderController::class, 'store']);
        Route::get('folders/{folder}', [FolderController::class, 'show']);
        Route::post('folders/{folder}/update', [FolderController::class, 'update']);
        Route::post('folders/{folder}/delete', [FolderController::class, 'destroy']);


        // ----------------------- Words ----------------------------------------------
        Route::get('words', [WordController::class, 'index']);
        Route::post('words', [WordController::class, 'store']);
        Route::get('words/{word}', [WordController::class, 'show']);
        Route::post('words/{word}/update', [WordController::class, 'update']);
        Route::post('words/{word}/delete', [WordController::class, 'destroy']);


        // ----------------------- Practice -------------------------------------------
        Route::post('/practice/start', [PracticeController::class, 'createQuiz']);
        Route::post('/practice/submit-answer', [PracticeController::class, 'submitAnswer']);
        Route::get('/practice/report/{practice}', [PracticeController::class, 'showReport']);

    });
});
