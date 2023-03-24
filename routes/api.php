<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;

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

Route::post('/add-expense', [ExpenseController::class, 'saveExpense']);

Route::patch('expenses/{expenseId}', [ExpenseController::class, 'updateExpense']);

Route::delete('expenses/{expenseId}', [ExpenseController::class, 'deleteExpense']);
