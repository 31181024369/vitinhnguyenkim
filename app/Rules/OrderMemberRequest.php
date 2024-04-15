<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class OrderMemberRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            // 'd_name' => 'required',
            // 'd_address' => 'required',
            // 'd_email' => 'email',
            // 'd_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'shipping_method' => 'required',
            'payment_method' => 'required'
        ]);
        return $validator;
    }
}