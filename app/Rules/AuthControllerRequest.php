<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class AuthControllerRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'username'=>'required|min:6',
            // 'password'=>'required|confirmed|min:6',
            'password'=>'required|min:6',

            // 'full_name' => 'required',
            'email'=>'required|email',
            'phone' => 'required|min:10|numeric',
            // 'tencongty'=>'required',
            // 'masothue' => 'required',
            // 'emailcty' => 'required',
            // 'diachicongty' => 'required',
            // 'sdtcongty' => 'required|min:11|numeric',
            'address' => 'required',
            'district' => 'required',
            'city_province' => 'required',
        ]);
        return $validator;
    }
}