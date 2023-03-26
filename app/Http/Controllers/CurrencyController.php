<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;

class CurrencyController extends Controller
{
    public function addDefaultCurrency(Request $request){
        try{
            $userCurrencySettings = new UserSetting();
            $userCurrencySettings->user_id = $request->user_id;
            $userCurrencySettings->currency_id = $request->currency_id;
            $userCurrencySettings->save();
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Setting creation failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Setting created successfully',
            'userCurrencySetting' => $userCurrencySettings
        ], 201);
    }
}
