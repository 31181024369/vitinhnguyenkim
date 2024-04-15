<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class PaymentMethodRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'name' => 'required',
        ]);
        return $validator;
    }
}