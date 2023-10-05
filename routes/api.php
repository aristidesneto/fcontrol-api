<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\EntryController;

// public routes
Route::post('login', [AuthController::class, 'login'])->name('login.api');
Route::post('register', [AuthController::class, 'register'])->name('register.api');
Route::get('/verify-email/{uuid}', [AuthController::class, 'verifyEmail'])->name('verification.verify');


Route::get('authenticated/me', [AuthController::class, 'me'])->name('me.api');

Route::middleware('auth:sanctum')->group(function () {
    
    Route::apiResource('bank-accounts', BankAccountController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('credit-cards', CreditCardController::class);
    Route::apiResource('entries', EntryController::class);
    Route::put('entries/payday/{id}', [EntryController::class, 'payday']);
    Route::apiResource('users', UserController::class);
});
