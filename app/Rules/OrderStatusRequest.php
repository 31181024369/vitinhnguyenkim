<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class OrderStatusRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'color' => 'required',
            
        ]);
        return $validator;
    }
}