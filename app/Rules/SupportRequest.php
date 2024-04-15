<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class SupportRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'group' => 'required',
            'email' => 'required|email',
            'name' => 'required',
        ]);
        return $validator;
    }
}