<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class CouponStatusRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
        ]);
        return $validator;
    }
}