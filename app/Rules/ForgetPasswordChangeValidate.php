<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class ForgetPasswordChangeValidate
{
    public static function validate(array $data)
    {
        // $validator = Validator::make($data, [
        //     'username'=>'required',
        //     'password'=>'required|min:6',
        // ]);
        $validator = Validator::make($data, [
            'password_token'=>'required',
            'password_new'=>'required|min:6',
        ]);

        return $validator;
    }
}
