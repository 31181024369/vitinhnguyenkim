<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class ProductPriceRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);
        return $validator;
    }
}