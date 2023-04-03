<?php

namespace App\Repositories;

use App\Models\Expense;

class ExpenseRepository
{
    protected $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    public function getExpensesOfCurrentMonth($year, $month, $userId)
    {
        return $this->expense
                ->where('user_id', $userId)
                ->whereYear('expense_date', $year)
                ->whereMonth('expense_date',$month)
                ->get();
    }

    public function getExpenseById($expenseId)
    {
        return $this->expense
                ->where('expense_id', $expenseId)
                ->first();
    }

    public function createExpense($request)
    {
        $expense = $this->expense;
        $expense->expense_details=$request->details;
        $expense->expense_amount=$request->amount;
        $expense->category_id=$request->category;
        $expense->expense_date=$request->date;
        $expense->currency_id=$request->currency;
        $expense->payment_method = $request->paymentMethod;
        $expense->user_id=1;
        $expense->save();
        return $expense;
    }

    public function updateExpense($expense, $request)
    {
        $expense->expense_details=$request->details;
        $expense->expense_amount=$request->amount;
        $expense->category_id=$request->category;
        $expense->expense_date=$request->date;
        $expense->currency_id=$request->currency;
        $expense->payment_method = $request->paymentMethod;
        $expense->user_id=1;
        $expense->save();
        return $expense;
    }

    public function deleteExpense($expense)
    {
        return $expense->delete();
    }

    public function getDataForChart($year, $month, $userId)
    {
        return $this->expense
                ->selectRaw('categories.category_name_en as label,
                    ROUND((SUM(expense_amount) /
                    (SELECT SUM(expense_amount) FROM expenses
                    WHERE YEAR(expense_date) = ? AND
                    MONTH(expense_date) = ?)) * 100,2) as value',
                    [$year, $month])
                ->join('categories', 'expenses.category_id', '=', 'categories.category_id')
                ->where('user_id', $userId)
                ->whereYear('expense_date','=', $year)
                ->whereMonth('expense_date','=', $month)
                ->groupBy('categories.category_name_en')
                ->get();
    }
}
