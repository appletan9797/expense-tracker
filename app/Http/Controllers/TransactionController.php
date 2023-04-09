<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserSettingRepository;

class TransactionController extends Controller
{
    public function __construct(private TransactionRepository $transactionRepository, private CategoryRepository $categoryRepository, private CurrencyRepository $currencyRepository, private UserSettingRepository $userSettingRepository)
    {

    }

    public function index($userId)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        $transaction = $this->getAllTransaction($currentYear, $currentMonth, $userId);
        $result = $this->processData($transaction->toJson());
        return $result;
    }

    public function getAllTransaction($year, $month, $userId)
    {
        $transaction = $this->transactionRepository->getTransactionsOfCurrentMonth($year, $month, $userId);
        return $transaction;
    }

    public function show($transactionId)
    {
        $transaction = $this->transactionRepository->getTransactionById($transactionId);
        return $transaction;
    }

    public function processData($transactionRecord)
    {
        $dataToArray = json_decode($transactionRecord, true);
        $groupedData = array_reduce($dataToArray, function($result, $eachTransaction){
            $date = $eachTransaction['transaction_date'];
            if(!isset($result[$date])){
                $result[$date] = [];
            }
            $result[$date][] = $eachTransaction;
            return $result;
        },[]);
        krsort($groupedData);
        return json_encode($groupedData);
    }

    public function store(Request $request)
    {
        try{
            $transaction = $this->transactionRepository->createTransaction($request);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Transaction creation failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully',
            'transaction' => $transaction
        ], 201);
    }

    public function update(Request $request, $transactionId)
    {
        $transaction = $this->show($transactionId);

        if(!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        try{
            $transaction = $this->transactionRepository->updateTransaction($transaction, $request);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Transaction record failed to updated: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction record updated successfully',
            'transaction' => $transaction
        ], 200);

    }

    public function destroy($transactionId)
    {
        $transaction = $this->show($transactionId);

        if(!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        try {
            $this->transactionRepository->deleteTransaction($transaction);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction deletion failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully'
        ], 200);
    }

    //TODO: check this
    public function getDataForChart($month = null, $year = null)
    {
        if(!$month){
            $month = date('m');
        }
        if(!$year){
            $year = date('Y');
        }
        $userId = 1;

        $transactionDataForChart = $this->transactionRepository->getDataForChart($year, $month, $userId);
        $transactionDetails = $this->getAllTransaction($year, $month, $userId);

        return response()->json([
            'chart_data' => $transactionDataForChart,
            'details_data' => $transactionDetails
        ]);
    }

}
