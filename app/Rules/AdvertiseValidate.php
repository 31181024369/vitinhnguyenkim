<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class AdvertiseValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'pos' => 'required',
            'id_pos' => 'required',
            'width' => 'required',
            'height' => 'required',
            'description' => 'required'
        ]);
        return $validator;
    }
}