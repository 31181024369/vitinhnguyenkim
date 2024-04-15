<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class AdposValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'title' => 'required|min:3',
        ]);
        return $validator;
    }
}