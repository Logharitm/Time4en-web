<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactInfoController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;


Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {

    Route::get('clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('optimize:clear');
        return 'Done';
    });

    // --------------------------------- Auth ---------------------------------
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::get('/policies/{type}', [PolicyController::class, 'show']);

    Route::group(['middleware' => ['auth:sanctum', 'check.subscription']], function () {

        Route::get('/statistics', [StatisticsController::class, 'index']);
        Route::get('/plans/statistics', [StatisticsController::class, 'plans']);

        // --------------------------------- Auth ---------------------------------
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('update-device-token', [AuthController::class, 'updateDeviceToken']);

        // --------------------------------- Users ---------------------------------
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::post('/users/store', [UserController::class, 'store']);
        Route::post('/users/update/{user}', [UserController::class, 'update']);
        Route::post('/users/delete/{user}', [UserController::class, 'destroy']);

        // --------------------------------- Folders ---------------------------------
        Route::get('folders', [FolderController::class, 'index']);
        Route::post('folders', [FolderController::class, 'store']);
        Route::get('folders/{folder}', [FolderController::class, 'show']);
        Route::post('folders/{folder}/update', [FolderController::class, 'update']);
        Route::post('folders/{folder}/delete', [FolderController::class, 'destroy']);

        // --------------------------------- Words ---------------------------------
        Route::get('words', [WordController::class, 'index']);
        Route::post('words', [WordController::class, 'store']);
        Route::get('words/{word}', [WordController::class, 'show']);
        Route::post('words/{word}/update', [WordController::class, 'update']);
        Route::post('words/{word}/delete', [WordController::class, 'destroy']);

        // --------------------------------- Practice ---------------------------------
        Route::get('/practices', [PracticeController::class, 'index']);
        Route::post('/practice/start', [PracticeController::class, 'createQuiz']);
        Route::post('/practice/submit-answer', [PracticeController::class, 'submitAnswer']);
        Route::get('/practice/report/{practice}', [PracticeController::class, 'showReport']);

        // --------------------------------- FAQs ---------------------------------
        Route::post('/faqs', [FaqController::class, 'store']);
        Route::post('/faqs/{faq}', [FaqController::class, 'update']);
        Route::post('/faqs/{faq}/delete', [FaqController::class, 'destroy']);

        // --------------------------------- Messages ---------------------------------
        Route::get('/messages', [MessagesController::class, 'index']);
        Route::post('/messages', [MessagesController::class, 'store']);
        Route::post('/messages/{message}/delete', [MessagesController::class, 'destroy']);

        // --------------------------------- Contact Info ---------------------------------
        Route::get('/contact-info', [ContactInfoController::class, 'show']);
        Route::post('/contact-info', [ContactInfoController::class, 'update']);

        // --------------------------------- Policies ---------------------------------
        Route::post('/policies/{type}', [PolicyController::class, 'update']);

        // --------------------------------- Plans ---------------------------------
        Route::get('/plans', [PlanController::class, 'index']);
        Route::get('/plans/{plan}', [PlanController::class, 'show']);
        Route::post('/plans/store', [PlanController::class, 'store']);
        Route::post('/plans/update/{plan}', [PlanController::class, 'update']);
        Route::post('/plans/delete/{plan}', [PlanController::class, 'destroy']);

        // --------------------------------- Subscriptions ---------------------------------
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show']);
        Route::post('/subscriptions/store', [SubscriptionController::class, 'store']);
        Route::post('/subscriptions/update/{subscription}', [SubscriptionController::class, 'update']);
        Route::post('/subscriptions/delete/{subscription}', [SubscriptionController::class, 'destroy']);

        // --------------------------------- Payments ---------------------------------
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/payments/{payment}', [PaymentController::class, 'show']);
        Route::post('/payments/store', [PaymentController::class, 'store']);
        Route::post('/payments/update/{payment}', [PaymentController::class, 'update']);
        Route::post('/payments/delete/{payment}', [PaymentController::class, 'destroy']);

        // --------------------------------- Notifications ---------------------------------
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications', [NotificationController::class, 'store']);
        Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    });

});
