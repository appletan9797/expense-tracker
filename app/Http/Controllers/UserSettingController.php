<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserSettingRepository;

class UserSettingController extends Controller
{
    public function __construct(private UserSettingRepository $userSettingRepository)
    {

    }

    public function store(Request $request)
    {
        try{
           $userDefaultCurrency = $this->userSettingRepository->createUserDefaultCurrency($request);
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
            'userCurrencySetting' => $userDefaultCurrency
        ], 201);
    }

    public function show($userId)
    {
        $defaultCurrencySetting = $this->userSettingRepository->getUserDefaultCurrencySetting($userId);
        return $defaultCurrencySetting;
    }

    public function update(Request $request, $userId)
    {
        $userCurrencySettings = $this->userSettingRepository->getUserDefaultCurrencySetting($userId);

        if(!$userCurrencySettings) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        try{
            $userCurrencySettings = $this->userSettingRepository->updateUserDefaultCurrency($userCurrencySettings, $request);
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
