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
                    ->join('categories','transactions.category_id', '=', 'categories.category_id')
                    ->where('transactions.user_id', '=', $userId)
                    ->whereYear('transaction_date','=', $year)
                    ->whereMonth('transaction_date','=', $month)
                    ->orderBy('categories.category_id')
                    ->get();
    }
}
