<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class ComboProductRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'nameCombo' => 'required',
            'priceCombo' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg'
        ]);
        return $validator;
    }
}