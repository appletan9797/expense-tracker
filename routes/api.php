<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
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

Route::get('expenses', [ExpenseController::class, 'index']);

Route::get('expenses/{expenseId}', [ExpenseController::class, 'show']);

Route::get('expense/form-fields', [ExpenseController::class, 'getExpenseFormFields']);

Route::get('expense/chart-data/{month?}/{year?}',[ExpenseController::class, 'getDataForChart']);

Route::post('expenses', [ExpenseController::class, 'store']);

Route::patch('expenses/{expenseId}', [ExpenseController::class, 'update']);

Route::delete('expenses/{expenseId}', [ExpenseController::class, 'destroy']);

Route::post('register', [AuthController::class,'register']);

Route::post('login', [AuthController::class,'login']);

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
