<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;

class CurrencyController extends Controller
{
    public function store(Request $request){
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

    public function update(Request $request, $userId){
        $userCurrencySettings = UserSetting::where('user_id',$userId)->first();

        if(!$userCurrencySettings) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        try{
            $userCurrencySettings->currency_id = $request->currency_id;
            $userCurrencySettings->save();
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Setting update failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'userCurrencySetting' => $userCurrencySettings
        ], 200);
    }
}
