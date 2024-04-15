<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class SupportGroupRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'titleSupport' => 'required',
            'groupName' => 'required',
        ]);
        return $validator;
    }
}