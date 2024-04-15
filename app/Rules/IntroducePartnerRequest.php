<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class IntroducePartnerRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'phone' => 'required|numeric|digits:10',
            'company' => 'required|string',
            'buyer' => 'required',
            'phonebuyer' => 'required|numeric|digits:10',
            'companybuyer' => 'required|string',
            'order' => 'required',
            'quanlity' => 'required|integer',
        ]);
        return $validator;
    }
}