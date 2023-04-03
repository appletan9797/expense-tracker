<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository
{
    protected $currency;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }

    public function getAllCurrencies()
    {
        return $this->currency->all();
    }

    public function getCurrencyById($currencyId)
    {
        return $this->currency->where('currency_id', $currencyId)->first();
    }

    public function createCurrency($request)
    {
        $currency = $this->currency;
        $currency->currency_country_en = $request->currencyCountryEn;
        $currency->currency_name = $request->currencyName;
        $currency->currency_symbol = $request->currencySymbol;
        $currency->save();
        return $currency;
    }

    public function updateCurrency($currency, $request)
    {
        $currency->currency_country_en = $request->currencyCountryEn;
        $currency->currency_name = $request->currencyName;
        $currency->currency_symbol = $request->currencySymbol;
        $currency->save();
        return $currency;
    }

    public function deleteCurrency($currency)
    {
        return $currency->delete();
    }
}
