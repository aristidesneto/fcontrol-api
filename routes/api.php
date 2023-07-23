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
// Route::post('register', [AuthController::class, 'register'])->name('register.api');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('authenticated/me', [AuthController::class, 'me'])->name('me.api');
    
    Route::apiResource('bank-accounts', BankAccountController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('credit-cards', CreditCardController::class);
    Route::apiResource('entries', EntryController::class);
    Route::apiResource('users', UserController::class);
});
