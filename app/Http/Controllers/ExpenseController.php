<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserSettingRepository;

class ExpenseController extends Controller
{
    public function __construct(private ExpenseRepository $expenseRepository, private CategoryRepository $categoryRepository, private CurrencyRepository $currencyRepository, private UserSettingRepository $userSettingRepository)
    {

    }

    public function index()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $userId = 1;

        $expense = $this->getAllExpense($currentYear, $currentMonth, $userId);
        $result = $this->processData($expense->toJson());
        return $result;
    }

    public function getAllExpense($year, $month, $userId)
    {
        $expense = $this->expenseRepository->getExpensesOfCurrentMonth($year, $month, $userId);
        return $expense;
    }

    public function show($expenseId)
    {
        $expense = $this->expenseRepository->getExpenseById($expenseId);
        return $expense;
    }

    public function processData($expenseRecord)
    {
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

    public function getExpenseFormFields()
    {
        //TODO : to pass user_id to the function
        $userId = 2;
        $categoryList = $this->categoryRepository->getCategoriesByUserId();
        $currencyList = $this->currencyRepository->getAllCurrencies();
        $defaultCurrency = $this->userSettingRepository->getUserDefaultCurrencySetting($userId);

        return response()->json([
            'categories' => $categoryList,
            'currencies' => $currencyList,
            'defaultCurrency' => $defaultCurrency
        ]);
    }

    public function store(Request $request)
    {
        try{
            $expense = $this->expenseRepository->createExpense($request);
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

    public function update(Request $request, $expenseId)
    {
        $expense = $this->show($expenseId);

        if(!$expense) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        try{
            $expense = $this->expenseRepository->updateExpense($expense, $request);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Expense record failed to updated: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Expense record updated successfully',
            'expense' => $expense
        ], 200);

    }

    public function destroy($expenseId)
    {
        $expense = $this->show($expenseId);

        if(!$expense) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        try {
            $this->expenseRepository->deleteExpense($expense);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Expense deletion failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully'
        ], 200);
    }

    public function getDataForChart($month = null, $year = null)
    {
        if(!$month){
            $month = date('m');
        }
        if(!$year){
            $year = date('Y');
        }
        $userId = 1;

        $expenseDataForChart = $this->expenseRepository->getDataForChart($year, $month, $userId);
        $expenseDetails = $this->getAllExpense($year, $month, $userId);

        return response()->json([
            'chart_data' => $expenseDataForChart,
            'details_data' => $expenseDetails
        ]);
    }

}
