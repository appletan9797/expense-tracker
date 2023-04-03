<?php

namespace App\Repositories;

use App\Models\UserSetting;

class UserSettingRepository
{
    protected $userDefaultCurrency;

    public function __construct(UserSetting $userDefaultCurrency)
    {
        $this->userDefaultCurrency = $userDefaultCurrency;
    }

    public function createUserDefaultCurrency($request)
    {
        $userCurrencySettings = $this->userDefaultCurrency;
        $userCurrencySettings->user_id = $request->user_id;
        $userCurrencySettings->default_currency_id = $request->currency_id;
        $userCurrencySettings->save();
        return $userCurrencySettings;
    }

    public function getUserDefaultCurrencySetting($userId)
    {
       return $this->userDefaultCurrency
                ->where('user_id',$userId)
                ->first();
    }

    public function updateUserDefaultCurrency($userCurrencySettings, $request)
    {
        $userCurrencySettings->default_currency_id = $request->currency_id;
        $userCurrencySettings->save();
        return $userCurrencySettings;
    }
}
