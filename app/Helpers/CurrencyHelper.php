<?php

namespace App\Helpers;

use App\Models\Company;
use NumberFormatter;

class CurrencyHelper
{
    public static function formatCurrency($value)
    {

        $company = Company::first();
        $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $company->decimal);

        return $formatter->formatCurrency($value, $company->currency);
    }
}
