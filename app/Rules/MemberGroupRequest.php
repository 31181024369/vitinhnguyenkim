<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class MemberGroupRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'g_name' => 'required|string',
        ]);
        return $validator;
    }
}