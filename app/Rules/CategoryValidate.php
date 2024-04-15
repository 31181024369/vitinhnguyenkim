<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class CategoryValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
                'cat_name' => 'required',
                'home_title' => 'required',
                'description' => 'required',
                'friendly_title'=>'required'
        ]);
        return $validator;
    }
}