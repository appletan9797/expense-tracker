<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function showAll(){
        $currentDate = Carbon::now();

        $expense = Expense::whereYear('expense_date','=', $currentDate->year)
                    ->whereMonth('expense_date','=', $currentDate->month)
                    ->get();
        $result = $this->processData($expense->toJson());
        return view('index', ['allExpense' => json_decode($result)]);
    }

    public function processData($expenseRecord){
        $dataToArray = json_decode($expenseRecord, true);
        $groupedData = array_reduce($dataToArray, function($result, $eachExpense){
            $date = $eachExpense['expense_date'];
            if(!isset($result[$date])){
                $result[$date] = [];
            }
            $result[$date][] = $eachExpense;
            return $result;
        },[]);
        krsort($groupedData);
        return json_encode($groupedData);
    }

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
