<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CurrencyRepository;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyRepository $currencyRepository)
    {

    }

    public function index()
    {
        return $this->currencyRepository->getAllCurrencies();
    }

    public function show($currencyId)
    {
        return $this->currencyRepository->getCurrencyById($currencyId);
    }

    public function store(Request $request)
    {
        try{
            $currency = $this->currencyRepository->createCurrency($request);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Currency creation failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Currency created successfully',
            'Currency' => $currency
        ], 201);
    }

    public function update(Request $request, $currencyId)
    {
        $currency = $this->show($currencyId);

        if(!$currency) {
            return response()->json([
                'success' => false,
                'message' => 'Currency not found'
            ], 404);
        }

        try{
            $currency = $this->currencyRepository->updateCurrency($currency, $request);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Currency update failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Currency updated successfully',
            'currency' => $currency
        ], 200);
    }

    public function destroy($currencyId)
    {
        $currency = $this->show($currencyId);

        if(!$currency) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        try {
            $this->currencyRepository->deleteCurrency($currency);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Currency deletion failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Currency deleted successfully'
        ], 200);
    }
}
