<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getTransactionsOfCurrentMonth($year, $month, $userId)
    {
        return $this->transaction
                ->where('user_id', $userId)
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date',$month)
                ->get();
    }

    public function getTransactionById($transactionId)
    {
        return $this->transaction
                ->where('transaction_id', $transactionId)
                ->first();
    }

    public function createTransaction($request)
    {
        $transaction = $this->transaction;
        $transaction->transaction_type=$request->transactionType;
        $transaction->transaction_details=$request->details;
        $transaction->transaction_amount=$request->amount;
        $transaction->category_id=$request->category;
        $transaction->transaction_date=$request->date;
        $transaction->currency_id=$request->currency;
        $transaction->payment_method = $request->paymentMethod;
        $transaction->user_id=1;
        $transaction->save();
        return $transaction;
    }

    public function updateTransaction($transaction, $request)
    {
        $transaction->transaction_details=$request->details;
        $transaction->transaction_amount=$request->amount;
        $transaction->category_id=$request->category;
        $transaction->transaction_date=$request->date;
        $transaction->currency_id=$request->currency;
        $transaction->payment_method = $request->paymentMethod;
        $transaction->user_id=1;
        $transaction->save();
        return $transaction;
    }

    public function deleteTransaction($transaction)
    {
        return $transaction->delete();
    }

    public function getDataForChart($year, $month, $userId, $transactionType)
    {
        return $this->transaction
                ->selectRaw('categories.category_id,
                    categories.category_name_en as label,
                    ROUND((SUM(transaction_amount) /(SELECT SUM(transaction_amount) FROM transactions
                    WHERE YEAR(transaction_date) = ? AND
                    MONTH(transaction_date) = ? AND
                    user_id = ? AND
                    transaction_type = ?)) * 100,1) as value',
                    [$year, $month, $userId, $transactionType])
                ->join('categories', 'transactions.category_id', '=', 'categories.category_id')
                ->where([
                        ['transactions.user_id', '=', $userId],
                        ['transactions.transaction_type','=', $transactionType]
                    ])
                ->whereYear('transaction_date','=', $year)
                ->whereMonth('transaction_date','=', $month)
                ->groupBy('categories.category_id')
                ->orderBy('categories.category_id')
                ->get();
    }
}
