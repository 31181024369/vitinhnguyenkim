<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class OrderManagementRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'status' => 'required',
        ]);
        return $validator;
    }
}