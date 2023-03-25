<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('expenses', [ExpenseController::class, 'showAll']);

Route::get('expenses/{expenseId}', [ExpenseController::class, 'showExpense']);

Route::get('get-form-fields', [ExpenseController::class, 'getExpenseFormFields']);

Route::get('get-chart-data/{month?}/{year?}',[ExpenseController::class, 'getDataForChart']);

Route::post('/add-expense', [ExpenseController::class, 'saveExpense']);

Route::patch('expenses/{expenseId}', [ExpenseController::class, 'updateExpense']);

Route::delete('expenses/{expenseId}', [ExpenseController::class, 'deleteExpense']);

Route::post('users', [AuthController::class,'register']);
