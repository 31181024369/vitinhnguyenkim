<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class ProductFlashSaleValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'product_id' => 'required',
            'time' => 'required',
        ]);
        return $validator;
    }
}