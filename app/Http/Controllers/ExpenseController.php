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
        return $result;
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

    public function getExpenseFormFields(){
        $categoryList = Category::all();
        $currencyList = Currency::all();

        return response()->json([
            'categories' => $categoryList,
            'currencies' => $currencyList
        ]);
    }

    public function saveExpense(Request $request){
        try{
            $expense = new Expense();
            $expense->expense_details=$request->details;
            $expense->expense_amount=$request->amount;
            $expense->category_id=$request->category;
            $expense->expense_date=$request->date;
            $expense->currency_id=$request->currency;
            $expense->user_id=1;
            $expense->save();
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Expense creation failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Expense created successfully',
            'expense' => $expense
        ], 201);
    }
}
