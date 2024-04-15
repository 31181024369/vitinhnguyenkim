<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class BrandValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|min:3',
            'description' => 'required|min:3',
            'friendly_url' => 'required',
            'friendly_title' => 'required',
            'metakey' => 'required',
            'metadesc' => 'required'
        ]);
        return $validator;
    }
}