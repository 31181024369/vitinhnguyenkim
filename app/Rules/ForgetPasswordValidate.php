<?php

namespace App\Rules;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'email'=>'required|min:6',
        ]);

        return $validator;
    }
}
