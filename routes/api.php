<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', [AuthController::class,'show']);

Route::get('users/{userId}/transactions', [TransactionController::class, 'index']);

Route::get('transactions/{transactionId}', [TransactionController::class, 'show']);

Route::get('transaction/chart-data/{month?}/{year?}',[TransactionController::class, 'getDataForChart']);

Route::post('transactions', [TransactionController::class, 'store']);

Route::patch('transactions/{transactionId}', [TransactionController::class, 'update']);

Route::delete('transactions/{transactionId}', [TransactionController::class, 'destroy']);

Route::post('register', [AuthController::class,'register']);

Route::post('login', [AuthController::class,'login']);

Route::get('user/{email}', [AuthController::class,'check']);

Route::post('users/default-currency', [UserSettingController::class, 'store']);

Route::patch('users/default-currency/{userId}', [UserSettingController::class, 'update']);

Route::get('users/default-currency/{userId}', [UserSettingController::class, 'show']);

Route::get('categories/{userId?}', [CategoryController::class, 'index']);

Route::post('categories', [CategoryController::class, 'store']);

Route::patch('categories/{categoryId}', [CategoryController::class, 'update']);

Route::delete('categories/{categoryId}', [CategoryController::class, 'destroy']);

Route::patch('users/{userId}/password', [PasswordController::class, 'update']);

Route::post('users/forgot-password', [PasswordController::class, 'handleForgotPassword']);

Route::post('users/reset-password', [PasswordController::class, 'handleResetPassword']);

Route::get('currencies', [CurrencyController::class,'index']);
