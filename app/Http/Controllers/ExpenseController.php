<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function addExpense(){
        $categoryList = Category::all();
        $currencyList = Currency::all();
        return view('add-expense', ['categories' => $categoryList, 'currencies' => $currencyList]);
    }

    public function save(Request $request){
        $expense = new Expense();
        $expense->expense_details=$request->details;
        $expense->expense_amount=$request->amount;
        $expense->category_id=$request->category;
        $expense->expense_date=$request->date;
        $expense->currency_id=$request->currency;
        $expense->user_id=1;
        $expense->save();

        return $this->addExpense();
    }
}
