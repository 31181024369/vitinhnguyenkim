<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class ShippingMethodRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'name' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'description' => 'required',
        ]);
        return $validator;
    }
}